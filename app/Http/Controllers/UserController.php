<?php

namespace App\Http\Controllers;

use App\Mail\User as MailUser;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return view('user');
    }

    public function read(Request $request)
    {
        if ($request->ajax()) {
            $users = [];
            if (Auth::user()->hasRole('super-admin')) {
                $users = User::role(['admin', 'warehouse'])->get();
            } else if (Auth::user()->hasRole('admin')) {
                $users = User::role('warehouse')->get();
            }
            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-success" data-name="' . $row->name . '" data-email="' . $row->email . '" data-role="' . $row->getRoleNames()[0] . '"data-id="' . $row->id . '" onclick="showModalUpdate(event)">
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
                ->addColumn('role', function ($row) {
                    return $row->getRoleNames()[0];
                })
                ->rawColumns(['action', 'created_at', 'updated_at', 'role'])
                ->make(true);
        }
    }

    public function create(Request $request)
    {
        $roles = ['warehouse'];
        if (Auth::user()->hasRole('super-admin')) {
            $roles[] = 'admin';
        }
        $rules = [
            'email' => ['required', 'string', 'max:255', 'unique:users,email'],
            'name' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:255', Rule::in($roles)]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/users')->withErrors($validator)->with('status', 'create')->withInput();
        }
        $password = Str::random(8);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'email_verified_at' => now()
        ]);
        if ($request->role === 'admin') {
            $user->assignRole('admin');
        } else {
            $user->assignRole('warehouse');
        }
        Mail::to($request->email)->send(new MailUser([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role
        ]));

        return redirect('/users')->with('message', 'Successfully added a new user and user information is sent to the user email');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = User::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $roles = ['warehouse'];
        if (Auth::user()->hasRole('super-admin')) {
            $roles[] = 'admin';
        }
        $rules = [
            'name_update' => ['required', 'string', 'max:255'],
            'email_update' => ['required', 'email', 'max:255'],
            'role_update' => ['required', 'string', 'max:255', Rule::in($roles)],
        ];
        if ($result->email !== $request->email_update) {
            $rules['email_update'][] = 'unique:users,email';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/users')->withErrors($validator)->with('status', 'update')->withInput();
        }
        $result->update([
            'name' => $request->name_update,
            'email' => $request->email_update,
        ]);
        if ($request->role_update === 'admin') {
            $result->syncRoles('admin');
        } else {
            $result->syncRoles('warehouse');
        }
        return redirect('/users')->with('message', 'Successfully updated user data');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'max:255'],
        ]);
        $result = User::find($request->id);
        if (!$result) {
            return abort(404);
        }
        $result->delete();
        return redirect('/users')->with('message', 'Successfully delete user data');
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ];
        $user = Auth::user();
        if ($user->email !== $request->email) {
            $rules['email'][] = 'unique:users,email';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/profile')->withErrors($validator)->with('status', 'update-profile')->withInput();
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return redirect('/profile')->with('message', 'Successfully update profile');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'password' => ['required', 'string', 'max:255', 'min:8', function ($attribute, $value, $fail) {
                $request = Request::capture();
                $user = Auth::user();
                if (!Hash::check($request->password, $user->password)) {
                    $fail("The :attribute is wrong");
                }
            }],
            'new_password' => ['required', 'string', 'max:255', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['required', 'string', 'max:255', 'min:8'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/profile')->withErrors($validator)->with('status', 'change-password')->withInput();
        }
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);
        return redirect('/profile')->with('message', 'Successfully change password');
    }
}
