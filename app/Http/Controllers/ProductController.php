<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Type;
use App\Models\Unit;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $types = Type::all(['name', 'id']);
        $units = Unit::all(['name', 'id']);
        return view('product', [
            'types' => $types,
            'units' => $units
        ]);
    }

    public function read(Request $request)
    {
        if ($request->ajax()) {
            $results = Product::orderBy('id', 'desc')->get();
            return Datatables::of($results)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-success" data-name="' . $row->name . '" data-price="' . $row->price . '" data-type="' . $row->type_id . '" data-unit="' . $row->unit_id . '" data-id="' . $row->id . '" onclick="showModalUpdate(event)">
                            <i class="mdi mdi-pencil"></i>
                            Update
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="destroy(' . $row->id . ')">
                            <i class="mdi mdi-delete"></i>
                            Delete
                        </button>
                    </div>
                    ';
                    return $actionBtn;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d M Y');
                })
                ->addColumn('type', function ($row) {
                    return $row->type->name;
                })
                ->addColumn('unit', function ($row) {
                    return $row->unit->name;
                })
                ->rawColumns(['action', 'created_at', 'updated_at', 'type', 'unit'])
                ->make(true);
        }
    }

    public function create(Request $request)
    {
        $types = Type::pluck('id')->toArray();
        $units = Unit::pluck('id')->toArray();
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'type' => ['required', 'numeric', Rule::in($types)],
            'unit' => ['required', 'numeric', Rule::in($units)],
            'price' => ['required', 'numeric'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/products')->withErrors($validator)->with('status', 'create')->withInput();
        }
        Product::create([
            'name' => $request->name,
            'product_code' => strtoupper(Str::random(10)),
            'type_id' => $request->type,
            'unit_id' => $request->unit,
            'price' => $request->price,
        ]);
        return redirect('/products')->with('message', 'Successfully added a new product');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = Product::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $types = Type::pluck('id')->toArray();
        $units = Unit::pluck('id')->toArray();
        $rules = [
            'name_update' => ['required', 'string', 'max:255'],
            'type_update' => ['required', 'numeric', Rule::in($types)],
            'unit_update' => ['required', 'numeric', Rule::in($units)],
            'price_update' => ['required', 'numeric'],
        ];
        if ($result->name !== $request->name_update) {
            $rules['name_update'][] = 'unique:products,name';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/products')->withErrors($validator)->with('status', 'update')->withInput();
        }
        $result->update([
            'name' => $request->name_update,
            'type_id' => $request->type_update,
            'unit_id' => $request->unit_update,
            'price' => $request->price_update,
        ]);
        return redirect('/products')->with('message', 'Successfully updated product data');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = Product::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $result->delete();
        return redirect('/products')->with('message', 'Successfully delete product data');
    }
}
