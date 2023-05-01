<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\anatomy;
use App\Models\phoneNumber;
use App\Models\User;
use App\Models\usersImage;
use App\Models\Xray;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
   use apiResponsetrait;

    public function index(){

        $user = User::select(['id','name'])->get();
        $userimage = usersImage::select(['profile_image','User-id'])->get();
        $phone = Phonenumber::get();
        $anatomy = anatomy::get();
        $xray = Xray::get();
        $questions =DB::table('questions_answers')->get();
      
        return $this->apiResponse($user,$userimage,$phone,$questions,$anatomy,$xray,'sucess',200);
    }
    public function doctorlogin(Request $request){

        $doctoraccount = DB::table('doctors')->select('account')->get();
        $doctorpassword = DB::table('doctors')->select('password')->get();
        $doctorname = DB::table('doctors')->select('name')->get();
        $email = $doctoraccount->value('account');
        $password = $doctorpassword->value('password');

        if ($email == $request->email && $password == $request->password) 

            return response()->json([
                'doctor name' =>$doctorname,
               'all users data'=> $this->index()
            ]);

            else
                 return response('Email or Password wrong please try again');
            
    }


public function userupdate(Request $request,$id){
    $user = DB::table('users')->where('id',$id)->update(['name'=>$request->name]);

    $phone = DB::table('phone_numbers')->where('User-id',$id)->insert(['User-id'=>$id,'phone_number'=>$request->phone_number]);

    $userimagepath = $request->file('profile_image')->store('userImages','images');
    $userimageUrl = asset('img/'.$userimagepath);
    usersImage::create([
        'User-id'=>$id,
        'profile_image'=>$userimageUrl
    ]);

        return response()->json([
            'message' => 'patient compeleted successfully ',
        ], 201);
}

public function questions(Request $request,$id){

    $questions = DB::table('questions_answers')->where('id',$id)->insert([
        'User-id'=>$id,'question_id'=>$request->question_id,'answer'=>$request->answer ]);
    return response()->json([
        'message' => 'question added successfully ',
    ], 201);
    
}

}