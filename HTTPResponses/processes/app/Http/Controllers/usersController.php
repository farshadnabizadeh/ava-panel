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
                if ($this->specialCharacterCheck($request->username) && !str_contains($request->username, ' ')) {
                    session()->put('username', true);
                    return response()->json([
                        "status" => true,
                        "data" => 'Username set successfully',
                    ]);
                } else {
                    session()->put('username', false);
                    return response()->json([
                        "status" => false,
                        "data" => 'special characters are not allowed',
                    ]);
                }
            }
        } else {
            session()->put('username', false);
            return response()->json([
                "status" => false,
                "data" => 'this username is already in use',
            ]);
        }
    }
    public function setEmail(Request $request)
    {
        if ($this->emailSpecialCharacterCheck($request->email) && !str_contains($request->email, ' ') && $this->emailValidation($request->email)) {
            session()->put('email', true);
            return response()->json([
                "status" => true,
                "data" => 'Email Set Successfully',
            ]);
        } else {
            session()->put('email', false);
            return response()->json([
                "status" => false,
                "data" => 'Email is invalid',
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
    public function specialCharacterCheck($data)
    {
        if (preg_match('/[\'^�$%&*()}{@#~?><>,|=_+�-]/', $data)) {
            return false;
        } else {
            return true;
        }
    }
    public function emailSpecialCharacterCheck($data)
    {
        if (preg_match('/[\'^�$%&*()}{#~?><>,|=_+�-]/', $data)) {
            return false;
        } else {
            return true;
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
