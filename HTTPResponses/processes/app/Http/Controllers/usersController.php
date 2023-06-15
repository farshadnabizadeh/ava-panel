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
        return session('email') && session('username');
    }
    public function getPasswordSession()
    {
        return session('password') && session('email') && session('username');
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
                "data" => 'This username is already in use',
            ]);
        }
    }
    public function setEmail(Request $request)
    {
        if ($this->emailSpecialCharacterCheck($request->email) && !str_contains($request->email, ' ') && $this->emailValidation($request->email)) {
            if (User::where('email', $request->email)->count() == 0) {
                session()->put('email', true);
                return response()->json([
                    "status" => true,
                    "data" => 'Email Set Successfully',
                ]);
            } else {
                session()->put('email', false);
                return response()->json([
                    "status" => false,
                    "data" => 'This Email already exists',
                ]);
            }
        } else {
            session()->put('email', false);
            return response()->json([
                "status" => false,
                "data" => 'Email is invalid',
            ]);
        }
    }
    public function setPassword(Request $request)
    {
        if (!$this->specialCharacterCheck($request->password) || str_contains($request->password, ' ')) {
            session()->put('password', false);
            return response()->json([
                "status" => false,
                "data" => "special characters and using Space are not allowed",
            ]);
        } else {
            if (strlen($request->password) != 8) {
                session()->put('password', false);
                return response()->json([
                    "status" => false,
                    "data" => "password must be 8 characters long",
                ]);
            } else {
                session()->put('password', true);
                return response()->json([
                    "status" => true,
                    "data" => "Password Set Successfully",
                ]);
            }
        }
    }
    public function setconfirmationpassword(Request $request)
    {
        if ($request->password == $request->confirmationpassword) {
            return response()->json([
                "status" => true,
                "data" => "password confirmed Successfully",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "data" => "Passwords are Not Same",
            ]);
        }
    }
    public function resetAllSession()
    {
        session()->put('username', false);
        session()->put('email', false);
        session()->put('password', false);
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
        if (preg_match('/[\'^�$%&*()}{@#~?><>,|;=.+�-]/', $data)) {
            return false;
        } else {
            return true;
        }
    }
    public function emailSpecialCharacterCheck($data)
    {
        if (preg_match('/[\'^�$%&*()}{#~?><>,|;=+�]/', $data)) {
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
    public function createAccount(Request $request)
    {
        if ($request->acceptTerms == 'enabled' && session('username') && session('password') && session('email')) {
            if (User::where('username', $request->username)->count() == 0 && User::where('email', $request->email)->count() == 0) {
                $createAccount = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'email_verified' => false,
                    'password' => $request->password,
                    'remember_token' => md5('password'),
                ]);
                if ($createAccount) {
                    return response()->json([
                        "status" => true,
                        "data" => "Your Account Created Successfully",
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "data" => "Oops .. Creating Account Failed",
                    ]);
                }
            } else {
                return response()->json([
                    "status" => false,
                    "data" => "Oops .. This username is already in use",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "data" => "Oops .. Creating Account Failed",
            ]);
        }
    }
    public function userLogin(Request $request)
    {
    }
    public function setCookie(Request $request)
    {
        $value = $request->cookie('rememberMe');
        return $value;
    }
}
