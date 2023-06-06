<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Models\User;

class usersController extends Controller
{
    public function createAccount(Request $request) {
         $errors = array();
         $nextprocess = array();
         if(isset($request->username)) {
            if($this->uniqueUsername($request->username)){
                $nextprocess['username'] = true;
            }else{
                $errors['username']='username already exists';
                $nextprocess['username'] = false;
            }
         }else{
            $errors['username'] = 'username is required';
            $nextprocess['username'] = false;
         }
         if(isset($request->email)){
            if($this->emailValidation($request->email)){
                $nextprocess['email'] = true;
            }else{
                $errors['email'] = 'email is invalid';
                $nextprocess['email'] = false;
            }
         }else{
            $errors['email'] = 'email is required';
            $nextprocess['email'] = false;
         }
         if(isset($request->password)){
             if($this->passwordCheck($request->password)){
                $nextprocess['password'] = true;
             }else{
                $errors['password'] = 'Password must be 8 Charachters';
                $nextprocess['password'] = false;
             }
         }else{
            $errors['password'] = 'password is required';
            $nextprocess['password'] = false;
         }
         if(isset($request->confirmpassword)){
            if($request->confirmpassword==$request->password){
                $nextprocess['confirmpassword'] = true;
            }else{
                $errors['confirmpassword'] = 'Passwords are not Same';
                $nextprocess['confirmpassword'] = false;
            }
         }else{
            $errors['confirmpassword'] = 'Confirm your Password';
            $nextprocess['confirmpassword'] = false;
         }
         if($request->acceptTerms=='enabled'){
            $nextprocess['acceptTerms'] = true;
         }else{
            $errors['acceptTerms'] = 'agreement is required';
            $nextprocess['acceptTerms'] = false;
         }
         return Response::json([
            'data' => $errors,
         ], 200);
    }
    public function emailValidation($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
    }
    public function uniqueUsername($username){
        if(User::where('username',$username)->count()==0){
            return true;
        }else{
            return false;
        }
    }
    public function passwordCheck($password){
        if(strlen($password)==8){
            return true;
        }else{
            return false;
        }
    }
}
