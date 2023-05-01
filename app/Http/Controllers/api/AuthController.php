<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\anatomy;
use App\Models\User;
use App\Models\xray;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    protected function createNewToken($token){

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,

        ]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'gander' => 'required|string',
            'height' => 'required|integer',
            'weight' => 'required|integer',
            'birth_date' => 'required|date',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['name'=>'null'],
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function userProfile() {
        $phoneNumber = DB::table('phone_numbers')->get()->where('User-id',auth()->user()->id);
        $profile_image = DB::table('users_images')->select('profile_image')->where('User-id',auth()->user()->id)->get();
        $xray = DB::table('xrays')->select('xray')->where('User-id',auth()->user()->id)->get();
        $anatomy = DB::table('anatomies')->select('anatomy')->where('User-id',auth()->user()->id)->get();
        return response()->json([
            'user' => auth()->user(),
            'phone'=> $phoneNumber,
            'profile_image'=>$profile_image,
            'xrays'=>$xray,
            'anatomies'=>$anatomy,]          
    );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function anatomy(Request $request){
        $anatomypath = $request->file('anatomy')->store('anatomyImages','images');
        $anatomyUrl = asset('img/'.$anatomypath);
        anatomy::create([
            'anatomy'=>$anatomyUrl,
            'User-id'=> $request->user()->id
        ]);
        return response()->json([
            'message' => 'anatomy added successfully ',
        ], 201);

        
    }

    public function xray(Request $request){

        $xraypath = $request->file('xray')->store('xrayImages','images');
        $xrayUrl = asset('img/'.$xraypath);
        xray::create([
            'xray'=>$xrayUrl,
            'User-id'=> $request->user()->id
        ]);
        return response()->json([
            'message' => 'xray added successfully ',
        ], 201);
    }


    public function deleteanatomy($id){

        DB::table('anatomies')->where('id',$id)->delete();
            return response()->json([
            'message' => 'anatomy deleted successfully ',
        ], 201);
        
    }
    
public function deletexray($id){

    DB::table('xrays')->where('id',$id)->delete();
        return response()->json([
        'message' => 'xray deleted successfully ',
    ], 201);
    
}

}

