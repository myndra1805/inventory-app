<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use DataTables;

class UnitController extends Controller
{
    public function index()
    {
        return view('unit');
    }

    public function read(Request $request)
    {
        if ($request->ajax()) {
            $results = Unit::orderBy('id', 'desc')->get();
            return Datatables::of($results)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-success" data-name="' . $row->name . '" data-id="' . $row->id . '" onclick="showModalUpdate(event)">
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
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name']
        ]);
        Unit::create([
            'name' => $request->name
        ]);
        return redirect('/units')->with('message', 'Successfully added a new unit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
            'name_update' => ['required', 'string', 'max:255']
        ]);
        $result = Unit::find($request->id);
        if (!$result) {
            return abort(404);
        }
        if ($result->name !== $request->name_update) {
            $request->validate([
                'name_update' => ['unique:units,name']
            ]);
        }
        $result->update([
            'name' => $request->name_update
        ]);
        return redirect('/units')->with('message', 'Successfully updated unit data');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = Unit::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $result->delete();
        return redirect('/units')->with('message', 'Successfully delete unit data');
    }
}
