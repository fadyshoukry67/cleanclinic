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
            return response()->json([
                'status' => 'false',
                'message' => 'Email or password is wrong please try again',
                'access_token' => 'null',
                'token_type' => 'null',
                // 'expires_in' => auth()->factory()->getTTL() * 60,
                'name' => 'null',
                'id' => 'null',
                'profile_image'=> 'null',
            ],);
        }
        return $this->createNewToken($token);
    }
    protected function createNewToken($token){

        $profile_image = DB::table('users_images')->select('profile_image')->where('User-id',auth()->user()->id)->get();
        $profImage=$profile_image->value('profile_image');

        return response()->json([
            'status' => 'true',
            'message' => 'welcome '.auth()->user()->name,
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60,
            'name' => auth()->user()->name,
            'id' => (string) auth()->user()->id,
            'profile_image'=>$profImage,
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
            return response()->json([
                'statues' => 'false',
                'message' => $validator->errors()->toArray(),
                'id'=> 'null'
            ]);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['name'=>'null'],
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'statues' => 'true',
            'message' => 'User successfully registered',
            'id' =>(string) $user->id,
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
            'statues' => 'true',
            'message' => 'anatomy added successfully ',
        ]);

        
    }

    public function xray(Request $request){

        $xraypath = $request->file('xray')->store('xrayImages','images');
        $xrayUrl = asset('img/'.$xraypath);
        xray::create([
            'xray'=>$xrayUrl,
            'User-id'=> $request->user()->id
        ]);
        return response()->json([
            'statues' => 'true',
            'message' => 'xray added successfully ',
        ]);
    }


    public function deleteanatomy($id){

        DB::table('anatomies')->where('id',$id)->delete();
            return response()->json([
            'statues' => 'true',
            'message' => 'anatomy deleted successfully ',
        ]);
        
    }
    
public function deletexray($id){

    DB::table('xrays')->where('id',$id)->delete();
        return response()->json([
        'statues' => 'true',
        'message' => 'xray deleted successfully ',
    ]);
    
}

}

