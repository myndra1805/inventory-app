<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Transaction;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transaction');
    }

    public function detail($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return abort(404);
        }
        return view('transaction.detail', [
            'transaction' => $transaction
        ]);
    }

    public function read(Request $request)
    {
        if ($request->ajax()) {
            $results = Transaction::orderBy('id', 'desc')->get();
            return Datatables::of($results)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnUpdate = '
                    <a href="/transactions/update/' . $row->id . '" class="btn btn-sm btn-success">
                        <i class="mdi mdi-pencil"></i>
                        Update
                    </a>
                    ';
                    $btnView = '
                    <a href="/transactions/' . $row->id . '" class="btn btn-sm btn-info">
                        <i class="mdi mdi-eye"></i>
                        View
                    </a>
                    ';
                    $btnDelete = '
                    <button class="btn btn-sm btn-danger" onclick="destroy(' . $row->id . ')">
                        <i class="mdi mdi-delete"></i>
                        Delete
                    </button>
                    ';
                    $actionBtn = '';
                    if (Auth::user()->hasRole('super-admin')) {
                        $actionBtn = "
                            <div class='d-flex justify-content-between'>
                                $btnView $btnUpdate $btnDelete
                            </div>";
                    } else if (Auth::user()->hasRole('admin')) {
                        $actionBtn = "
                        <div class='d-flex justify-content-between'>
                        $btnView $btnUpdate
                        </div>";
                    } else if (Auth::user()->hasRole('warehouse')) {
                        $actionBtn = "
                            <div class='d-flex justify-content-center'>
                                $btnView
                            </div>
                        ";
                    }
                    return $actionBtn;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d M Y');
                })
                ->addColumn('status', function ($row) {
                    return $row->status ? 'Incoming Transaction' : 'Outgoing Transaction';
                })
                ->addColumn('supplier', function ($row) {
                    return $row->supplier_id !== 0 ? $row->supplier->name : '';
                })
                ->addColumn('products', function ($row) {
                    $products = [];
                    foreach ($row->products as $product) {
                        $products[] = [
                            'name' => $product->name,
                            'price' => $product->price,
                            'amount' => $product->pivot->amount,
                        ];
                    }
                    return $products;
                })
                ->rawColumns(['action', 'created_at', 'updated_at', 'status', 'supplier', 'products'])
                ->make(true);
        }
    }

    public function showCreate()
    {
        $suppliers = Supplier::all(['id', 'name']);
        $products = Product::all(['id', 'name']);
        return view('transaction.create', [
            'suppliers' => $suppliers,
            'products' => $products
        ]);
    }

    public function showUpdate($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return abort(404);
        }
        $suppliers = Supplier::all(['id', 'name']);
        $products = Product::all(['id', 'name']);
        return view('transaction.update', [
            'transaction' => $transaction,
            'suppliers' => $suppliers,
            'products' => $products
        ]);
    }

    public function create(Request $request)
    {
        $products = Product::pluck('id')->toArray();
        $rules = [
            'status' => ['required', 'boolean', 'max:255'],
            'note' => ['required', 'string', 'max:255'],
            'products' => ['required', 'array', 'max:255', Rule::in($products)],
            'amounts' => ['required', 'array', 'max:255', function ($attribute, $values, $fail) {
                foreach ($values as $value) {
                    if ((int) $value === 0) {
                        $fail("The :attribute must integer");
                    }
                }
            }],
        ];
        if ($request->status == 1) {
            $suppliers = Supplier::pluck('id')->toArray();
            $rules['supplier'] = ['required', 'numeric', Rule::in($suppliers)];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/transactions/add')->withErrors($validator)->with('status', 'create')->withInput();
        }
        $transaction = Transaction::create([
            'transaction_code' => strtoupper(Str::random(10)),
            'supplier_id' => $request->status ? $request->supplier : 0,
            'note' => $request->note,
            'status' => $request->status,
        ]);
        foreach ($request->products as $i => $product_id) {
            ProductTransaction::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product_id,
                'amount' => $request->amounts[$i]
            ]);
        }
        return redirect('/transactions/' . $transaction->id)->with('message', 'Successfully added a new transaction');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = Transaction::find($request->id);
        if (!$result) {
            throw ValidationException::withMessages([
                'error' => 'Data not found'
            ]);
        }
        $products = Product::pluck('id')->toArray();
        $rules = [
            'status' => ['required', 'boolean', 'max:255'],
            'note' => ['required', 'string', 'max:255'],
            'products' => ['required', 'array', 'max:255', Rule::in($products), function ($attribute, $values, $fail) {
                $request = Request::capture();
                if (count($values) != count($request->amounts)) {
                    $fail("The :attribute value is invalid");
                }
            }],
            'amounts' => ['required', 'array', 'max:255', function ($attribute, $values, $fail) {
                $request = Request::capture();
                if (count($values) != count($request->products)) {
                    $fail("The :attribute value is invalid");
                }
                foreach ($values as $value) {
                    if ((int) $value === 0) {
                        $fail("The :attribute must integer");
                    }
                }
            }],
        ];
        if ($request->status == 1) {
            $suppliers = Supplier::pluck('id')->toArray();
            $rules['supplier'] = ['required', 'numeric', Rule::in($suppliers)];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/transactions/update/' . $request->id)->withErrors($validator)->with('status', 'create')->withInput();
        }
        $dataUpdate = [
            'note' => $request->note,
            'status' => $request->status,
        ];
        if ($request->status) {
            $dataUpdate['supplier_id'] = $request->supplier;
        }
        $result->update($dataUpdate);

        foreach ($result->products as $product) {
            ProductTransaction::where([
                ['product_id', $product->id],
                ['transaction_id', $result->id],
            ])->first()->delete();
        }
        foreach ($request->products as $i => $product_id) {
            ProductTransaction::create([
                'transaction_id' => $result->id,
                'product_id' => $product_id,
                'amount' => $request->amounts[$i]
            ]);
        }
        return redirect('/transactions/' . $request->id)->with('message', 'Successfully updated transaction data');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = Transaction::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $transactionProducts = ProductTransaction::where('transaction_id', $result->id)->get();
        foreach ($transactionProducts as $transactionProduct) {
            $transactionProduct->delete();
        }
        $result->delete();
        return redirect('/transactions')->with('message', 'Successfully delete transaction data');
    }

    public function invoice($id)
    {
        $transaction = Transaction::find($id);
        $pdf = PDF::loadview('pdf.invoice', [
            'transaction' => $transaction
        ]);
        return $pdf->download('invoice');
    }
}
