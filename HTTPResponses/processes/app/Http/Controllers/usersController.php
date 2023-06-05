<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class usersController extends Controller
{
    public function createAccount(Request $request) {
         $errors = array();
         $nextprocess = array();
         if(isset($request->username)) {
            $nextprocess['username'] = true;
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
            $nextprocess['password'] = true;
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
}
