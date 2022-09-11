<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        // password lai encrypt gareko with the help of hash funciton
        $password = Hash::make($password);
        $ldate = date('Y-m-d H:i:s');

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->email_verified_at = $ldate;

        $user->save();

        $success['token'] =  $user->createToken('classecom')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User created successfully.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();

            $success['token'] =  $user->createToken('classecom')->plainTextToken;
            $success['name'] =  $user->name;


            return $this->sendResponse($success, 'User Login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Credettials doesnot match']);
        }
    }
}
