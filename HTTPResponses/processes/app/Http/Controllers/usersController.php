<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Models\User;
use Session;

class usersController extends Controller
{
    public function getUserSession()
    {
        return session('username');
    }
    public function getEmailSession()
    {
        return session('email') ? true : false;
    }
    public function getPasswordSession()
    {
        return session('password') ? true : false;
    }
    public function setUsername(Request $request)
    {
        if ($this->uniqueUsername($request->username)) {
            if (strlen($request->username) <= 5) {
                session()->put('username', false);
                return response()->json([
                    "status" => false,
                    "data" => 'Username must be up to 5 characters long',
                ]);

            } else {
                session()->put('username', true);
                return response()->json([
                    "status" => true,
                    "data" => 'Username set successfully',
                ]);
            }
        } else {
            session()->put('username', false);
            return response()->json([
                "status" => false,
                "data" => 'this username is already in use',
            ]);
        }
    }
    public function emailValidation($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
    }
    public function uniqueUsername($username)
    {
        if (User::where('username', $username)->count() == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function passwordCheck($password)
    {
        if (strlen($password) == 8) {
            return true;
        } else {
            return false;
        }
    }
}
