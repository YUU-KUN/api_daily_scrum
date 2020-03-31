<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    //LOGIN
    public function login (Request $request){
        $credentials = $request->only('email','password');
        try{
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'logged'=> false,
                    'message' => 'invalid_credentials',
                    ]);
            }
        } catch (JWTException $e){
            return response()->json([
                'logged' 	=> false,
                'message' 	=> 'Generate Token Failed'
                ]);            
        }
        return response()->json([
                    "logged"    => true,
                    "token"     => $token,
                    "message" 	=> 'Yay! Kamu berhasil login!'
        ]);
    }

    //LOGIN CHECK
    public function LoginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					]);
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token expired'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token absent'
					], $e->getStatusCode());
		}

		 return response()->json([
		 		"auth"      => true,
                "user"    => $user
		 ]);
	}

    // REGISTER
    public function register (Request $request){
        
        $validator = Validator::make($request->all(), [
            'Firstname' => 'required|string|max:255',
            'Lastname' => 'required|string|max:255',            
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required_with:password_verify|same:password_verify|string|min:6',
            'password_verify' => 'string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson());
        }
        
        $user = User::create([
            'Firstname' => $request->get('Firstname'),
            'Lastname' => $request->get('Lastname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'password_verify' => Hash::make($request->get('password_verify')),           
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'));
    }

    // LOGOUT
    public function logout(Request $request)
    {

        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                "logged"    => false,
                "message"   => 'Logout berhasil'
            ], 201);
        } else {
            return response()->json([
                "logged"    => true,
                "message"   => 'Logout gagal'
            ], 201);
        }
    }

}
