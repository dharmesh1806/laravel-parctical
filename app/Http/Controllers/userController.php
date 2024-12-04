<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $fname = $request->input('fname');
        $lname = $request->input('lname');
        $email = $request->input('email');
        $contactNumber = $request->input('contactNumber');
        $postCode = $request->input('postCode');
        $password = $request->input('password');
        $role = $request->input('role');
        $gender = $request->input('gender');
        $hb = $request->input('hb');
        $file = $request->file('files');
        $existEmail = User::where('email', $email)->first();
        if ($existEmail) {
            return response()->json(['message' => 'Entered email is already exist'], 400);
        }
        $fileName = [];
        if (sizeof($file) > 0) {
            foreach ($request->file('files') as $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName_ = time() . '_' . uniqid() . '.' . $extension;
                $file->storeAs('uploads', $fileName_,'public');
                $fileName[] = $fileName_;
            }
        }
        User::create([
            'firstName' => $fname,
            'lastName' => $lname,
            'email' => $email,
            'contactNumber' => $contactNumber,
            'postCode' => $postCode,
            'password' => bcrypt($password),
            'role' => (int)$role,
            'hobbies' =>  $hb,
            'gender' => (int)$gender,
            'files' => implode(',', $fileName)
        ]);
        return response()->json(['message' => 'Register successfully']);
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();
        if ($user) {
            $user = $user->toArray();
            if (Hash::check($password, $user['password'])) {
                $token = generateToken(json_encode(['email' => $user['email'], 'id' => $user['id']]));
                return response()->json(['message' => 'Login successfully', 'token' => $token]);
            }
        }
        return response()->json(['message' => 'Invalid email or password'], 400);
    }

    public function roleList(Request $request)
    {
        $offset = $request->input('start');
        $limit = $request->input('length');
        $draw = $request->input('draw');
        $list = DB::table('roles')->offset($offset)->limit($limit)->get();
        $count = DB::table('roles')->count();
        return response()->json(['data' => $list, 'recordsFiltered' => $count, 'recordsTotal' => $count, 'draw' => $draw]);
    }

    public function roleListForRegister(Request $request)
    {
        $list = DB::table('roles')->select('id', 'role')->get();
        return response()->json(['data' => $list->toArray()]);
    }

    public function deleteRole(Request $request)
    {
        $id = $request->input('roleId');
        if ($id) {
            $existRole = Role::where('id', $id)->first();
            if ($existRole) {
                Role::find($id)->delete();
                return response()->json(['message' => 'Delete successfully']);
            }
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function addRole(Request $request)
    {
        $role = $request->input('role');
        if (!$role) {
            return response()->json(['message' => 'Enter role'], 400);
        }
        $existRole = Role::where('role', strtolower($role))->first();
        if ($existRole) {
            return response()->json(['message' => 'Entered role is alredy exist'], 400);
        }
        Role::create(['role' => strtolower($role)]);
        return response()->json(['message' => 'Role successfully created']);
    }

    public function editRole(Request $request)
    {
        $role = $request->input('role');
        $id = $request->input('id');
        if (!$role) {
            return response()->json(['message' => 'Enter role'], 400);
        }
        if ($id) {
            $existRole = Role::where('role', strtolower($role), 'id')->where('id', '!=', $id)->first();
            if ($existRole) {
                return response()->json(['message' => 'Entered role is alredy exist'], 400);
            }
            Role::where('id', $id)->update(['role' => $role]);
            return response()->json(['message' => 'Role successfully created']);
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function userList(Request $request)
    {
        $offset = $request->input('start');
        $limit = $request->input('length');
        $draw = $request->input('draw');
        $list = DB::table('users')->leftJoin('roles', 'users.role', '=', 'roles.id')->select('users.id', 'users.firstName', 'users.lastName', 'users.email', 'users.gender', 'users.hobbies', 'roles.role')->where('users.id', '!=', $request['userId'])->orderBy('users.id', 'desc')->offset($offset)->limit($limit)->get()->toArray();
        $count = DB::table('users')->leftJoin('roles', 'users.role', '=', 'roles.id')->select('users.id', 'users.firstName', 'users.lastName', 'users.email', 'users.gender', 'roles.role')->where('users.id', '!=', $request['userId'])->limit(10)->offset(0)->count();
        foreach ($list as $l => $data) {
            $list[$l]->gender = $data->gender == 0 ? 'Male' : 'Female';
        }
        return response()->json(['data' => $list, 'recordsFiltered' => $count, 'recordsTotal' => $count, 'draw' => $draw]);
    }

    public function deleteUser(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $existUser = User::where('id', $id)->first();
            if ($existUser) {
                $existUser = $existUser->toArray();
                if ($existUser['id'] != $request['userId']) {
                    User::find($id)->delete();
                    return response()->json(['message' => 'Delete successfully']);
                } else {
                    return response()->json(['message' => 'You can not delete yourself'], 400);
                }
            }
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function userData(Request $request)
    {
        $id = $request['userId'];
        if ($id) {
            $user = User::where('id', $id)->select('email')->first();
            if ($user) {
                $user = $user->toArray();
                return response()->json(['message' => 'Delete successfully', 'data' => $user]);
            }
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }
}
