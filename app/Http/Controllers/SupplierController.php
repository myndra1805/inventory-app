<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use DataTables;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier');
    }

    public function read(Request $request)
    {
        if ($request->ajax()) {
            $results = Supplier::orderBy('id', 'desc')->get();
            return Datatables::of($results)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-success" data-name="' . $row->name . '" data-address="' . $row->address . '" data-phone_number="' . $row->phone_number . '" data-id="' . $row->id . '" onclick="showModalUpdate(event)">
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
                ->rawColumns(['action', 'created_at', 'updated_at'])
                ->make(true);
        }
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/suppliers')->withErrors($validator)->with('status', 'create')->withInput();
        }
        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);
        return redirect('/suppliers')->with('message', 'Successfully added a new supplier');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = Supplier::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $rules = [
            'name_update' => ['required', 'string', 'max:255'],
            'address_update' => ['required', 'string', 'max:255'],
            'phone_number_update' => ['required', 'string', 'max:255'],
        ];
        if ($result->name !== $request->name_update) {
            $rules['name_update'][] = 'unique:suppliers,name';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/suppliers')->withErrors($validator)->with('status', 'update')->withInput();
        }
        $result->update([
            'name' => $request->name_update,
            'phone_number' => $request->phone_number_update,
            'address' => $request->address_update,
        ]);
        return redirect('/suppliers')->with('message', 'Successfully updated supplier data');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = Supplier::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $result->delete();
        return redirect('/suppliers')->with('message', 'Successfully delete supplier data');
    }
}
