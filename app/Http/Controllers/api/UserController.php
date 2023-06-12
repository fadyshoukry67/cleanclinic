<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\anatomy;
use App\Models\User;
use App\Models\usersImage;
use App\Models\Xray;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function userdata(){

        $users = User::select(['name', 'id','weight','height','birth_date'])->get();


        $userIds = $users->pluck('id')->toArray();
        $phone = DB::table('phone_numbers')->select('phone_number')->where('User-id',$userIds)->get()->value('phone_number');


        $userImages = usersImage::whereIn('User-id', $userIds)->get(['User-id', 'profile_image']);

        $userData = [];

        foreach($users as $user){
            $userImage = $userImages->where('User-id', $user->id)->first();
            $dateOfBirth = $user->birth_date;
            $age = Carbon::parse($dateOfBirth)->age;

            $userData[] = [
                'name' => $user->name,
                'height'=>$user->height,
                'weight'=>$user->weight,
                'phone'=>$phone,
                'age'=>$age,
                'id' =>(string) $user->id,
                'profile_image' => $userImage ? $userImage->profile_image : null
            ];
        }

        return response()->json([
            'status' => 'true',
            'message' => 'All user data retrieved successfully',
            'data' => $userData
        ]);
    }
    
    public function getusersanatomy($id){

        $anatomy = anatomy::select(['anatomy','id'])->where('User-id',$id)->get();
            
        return response()->json([
            'status' => 'true',
            'message' => 'Anatomies retrieved successfully',
            'anatomies' => $anatomy
         
        ]);
    }
    public function getusersxray($id){
        $xray = Xray::select(['xray','id'])->where('User-id',$id)->get();
            
        return response()->json([
            'status' => 'true',
            'message' => 'Xrays retrieved successfully',
            'xrays' => $xray
         
        ]);
    }
    public function doctorlogin(Request $request){

        $doctoraccount = DB::table('doctors')->select('account')->get();
        $doctorpassword = DB::table('doctors')->select('password')->get();
        $doctorname = DB::table('doctors')->select('name')->get();
        $name=$doctorname->value('name');
        $email = $doctoraccount->value('account');
        $password = $doctorpassword->value('password');

        if ($email == $request->email && $password == $request->password) 

            return response()->json([
                'statues'=> 'true',
                'message'=>'welcome doctor '.$name,
            ]);

            return response()->json([ 
                    'statues'=> 'false',
                    'message'=>'Email or password is wrong please try again',
                    ]);
    }


public function userupdate(Request $request,$id){
    $user = DB::table('users')->where('id',$id)->update(['name'=>$request->name]);

    $phone = DB::table('phone_numbers')->insert(['user-id'=>$id,'phone_number'=>$request->phone_number]);

    $userimagepath = $request->file('profile_image')->store('userImages','images');
    $userimageUrl = asset('img/'.$userimagepath);
    usersImage::create([
        'User-id'=>$id,
        'profile_image'=>$userimageUrl
    ]);

        return response()->json([
            'statues'=> 'true',
            'message' => 'patient compeleted successfully ',
        ],);
}

public function questions(Request $request,$id){

    $questions = DB::table('questions_answers')->insert([
        'User-id'=>$id,
        'question_id'=>$request->question_id,
        'answer'=>$request->answer
     ]);
    return response()->json([
        'message' => 'question added successfully ',
    ], 201);
    
}

}