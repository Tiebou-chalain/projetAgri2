<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register(Request $req)
    {
        $data =  $req->only('nom','prenom', 'email', 'password', 'numero','role');


        $validator = Validator::make($data, [
            'nom' => 'required|string',
            'role' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|min:6',
            'numero' => 'required|string|min:8'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 2 ], 200);
        }

       $user =  User::create([
            'nom'=> $req->nom,
            'role'=> $req->role,
            'prenom'=> $req->nom,
            'email'=> $req->email, 
            'password'=> bcrypt($req->password)  
        ]);

        if($user)
        {
            $user =  User::create([
                'numero'=> $req->numero,
                'user_id'=> $user->id,  
            ]);


            if($user)
            {
                //User created, return success response
            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_OK);
            }
            
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'code' => 1,
            'message' => 'Login Successfully',
            'token' => $token,
            'user_details' => $credentials['email']
        ]);
    }


    // public function get_user(Request $request)
    // {

    //     $data =  $request->only('token');


    //     $validator = Validator::make($data, [
    //         'token' => 'required',
    //     ]);

    //     //Send failed response if request is not valid
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors(), "status" => "error" ], 200);
    //     }
        
        
        
       
 
        // $user = JWTAuth::authenticate($request->token);
 
        // return response()->json(['data' => $user]);
    //}


    // public function get_one_user(Request $request)
    // {

    //     $inserted_id = $request->id;
        
        
    //     $result = DB::table('student')->where("id", $inserted_id)->get()->first();
        
    //     if(!empty($result))
    //     {
    //         return response()->json(
    //             [
    //                 "body" => 
    //                 [
    //                     "user" => [
    //                         "id" => $result->id,
    //                         "name" =>  $result->name,
    //                         "age" => $result->age,
    //                         "profile_image" => $result->profile_image,
    //                         "created_at"=> $result->created_at,
    //                         "updated_at"=> $result->updated_at
    //                     ]
    //                 ],
    //                 "status" => "successful"
    //             ]
    //                 );
    //     }else{
            
        
    //     return response()->json(
    //         [
    //             "body" => "No user found",
    //             "status" => "Error"
    //         ]
    //             );
    //         }
    // }


    public function logout(Request $request)
    {
       
        if(empty($request->token))
        {
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Token is required'
            ]);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUsers(){
        return response()->json(User::orderByDesc('id')->get(),200);
    }

    public function getVendeur(){
        $vendeur = User::where('role','vendeur')->get();
        return response()->json($vendeur,200);
    }

    public function deleteVendeur(Request $request,$id){
        $vendeur = User::find($id);
        if(is_null($vendeur)){
            return response()->json(['message'=>'Vendeur non trouvé'],404);
        }
        $vendeur -> delete();
        return response()->json(null,204);
    }

    public function getAdmin(){
        $admin = User::where('role','supadmin')->get();
        return response()->json($admin,200);
    }

    public function deleteAdmin(Request $request,$id){
        $admin = User::find($id);
        if(is_null($admin)){
            return response()->json(['message'=>'admin non trouvé'],404);
        }
        $admin -> delete();
        return response()->json(null,204);
    }

    
}