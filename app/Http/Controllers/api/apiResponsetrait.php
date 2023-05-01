<?php

namespace App\Http\Controllers\api;

trait apiResponsetrait 
{
    public function apiResponse($user=null ,$userimage =null ,$phone =null,$questions, $anatomy= null ,$xray=null ,$message=null,$statues = null){
    
        $array=[
            'patient data'=> $user,
            'patient image'=>$userimage,
            'patient phone number'=>$phone,
            'patient questions answers'=>$questions,
            'anatomies'=>$anatomy,
            'xrays'=>$xray,
            'message'=>$message,
            'statues'=>$statues
        ];
        return response()->json($array,$statues);
    }

}