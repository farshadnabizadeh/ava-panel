<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class usersController extends Controller
{
    public function createAccount(Request $request) {
         $errors = array();
         $nextprocess = true;
         if(isset($request->username)) {
            $nextprocess = true;
         }else{
            array_push($errors,'username is required');
         }
         if(isset($request->email)){
            $nextprocess = true;
         }else{
            array_push($errors,'email is required');
         }
         if(isset($request->password)){
            $nextprocess = true;
         }else{
            array_push($errors,'password is required');
         }

         return Response::json([
            'data' => $errors
         ], 200);
    }
    public function emailValidation($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
    }
}
