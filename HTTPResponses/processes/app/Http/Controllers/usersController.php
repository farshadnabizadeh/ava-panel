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
                array_push($errors,'username already exists');
                $nextprocess['username'] = false;
            }
         }else{
            array_push($errors,'username is required');
            $nextprocess['username'] = false;
         }
         if(isset($request->email)){
            if($this->emailValidation($request->email)){
                $nextprocess['email'] = true;
            }else{
                array_push($errors,'email is invalid');
                $nextprocess['email'] = false;
            }
         }else{
            array_push($errors,'email is required');
            $nextprocess['email'] = false;
         }
         if(isset($request->password)){
             if($this->passwordCheck($request->password)){
                $nextprocess['password'] = true;
             }else{
                array_push($errors,'Password must be 8 Charachter');
                $nextprocess['password'] = false;
             }
         }else{
            array_push($errors,'password is required');
            $nextprocess['password'] = false;
         }
         if(isset($request->confirmpassword)){
            $nextprocess['confirmpassword'] = true;
         }else{
            array_push($errors,'Confirm your Password');
            $nextprocess['confirmpassword'] = false;
         }
         if($request->acceptTerms=='enabled'){
            $nextprocess['acceptTerms'] = true;
         }else{
            array_push($errors,'agreement is required');
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
