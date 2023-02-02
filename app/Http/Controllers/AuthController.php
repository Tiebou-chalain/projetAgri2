<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Support\Str;
use Mail;
use Dirape\Token\Token;

use Illuminate\Http\Request;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('jwtauth', ['except' => ['login', 'register', 'jointe','verifyAccount',
    //         'refrechmail','generate','resestpassword','end_register','checkEnd_register']]);
    // }

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

   

    // function of login
    
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
            'user' => auth()->user()
        ]);
    }


    //function of register

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'numero' => 'required|string',
            'email' => 'required|string|email|max:100|unique:users',
            'role'=>'required|string',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
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

    

    //function of end of register
    public function end_register(Request $request,$token){

        $user = User::where('token_code',$token)->first();

        if($user != null ){
            if((Hash::check($request->password, $user->password))){
                $user->password = Hash::make($request->newpassword);

                $user->is_email_verified =true;
                $user->save();
                return response()->json($user,200);
            }

            return null;

        }else{
            return response()->json(null,200);
        }

    }

    //verifie si le token est encore valide
    public function checkEnd_register($token){
        $user = User::where('token_code', $token)->first();

        if($user != null  ) {
            if($user->is_email_verified===true) {
                return null;
            }
            return response()->json($user, 200);
        }else{
            return null;
        }
    }

    // function of reset password
    public function resestpassword(Request $request)    {

        $verifyUser = User::where($request->email)->first();
            $token1 = random_int(100000, 999999);
            $verifyUser->tokencode = $token1;
            $verifyUser->save();

            // Send email to user
            Mail::send('email.verificationemail', ['codeData' =>$verifyUser->tokencode], function ($message) use ($verifyUser) {
                $message->to($verifyUser->email);
                $message->subject('verification d\'email');
            });

            return response()->json(['status' =>true,  200]);



    }

    // function of code check
    public function codecheck (Request $request)
    {
        $request->validate([
            'tokencode' => 'required|string|exists:users',
            'password' => 'required',
        ]);

        // find the code
        $passwordReset = User::firstWhere('tokencode', $request->tokencode);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update($request->only('password'));

        // delete current code
        $passwordReset->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }


    //function to check validation of token
    public function checktoken()
    {
        return response()->json(['statut' => true], 200);

    }

    // function to save an image in the db
    public function jointe(Request $request)
    {
        //$file = new attachment();
        if($request->hasFile('image')){
          $save=  saveFile($request->file('image'));
            return response()->json(['status' =>true, 'message' =>$save, 200]);

        }
    }

    //function to verify an account
    public function verifyAccount($user_id,$token)
    {
        //$verifyUser = User::where('id',$user_id)->first();
        $verifyUser = User::find($user_id);

        if(!is_null($verifyUser) ){
           // $user = $verifyUser->user;

            if($verifyUser->token_code === $token ) {
                $verifyUser->is_email_verified = true;
                $verifyUser->save();
                $message = "Votre adresse mail à present verifier. vous pouvez-vous connecter.";
                return response()->json([ 'message' =>$message ],200);

            } else {
//                $message = "Your e-mail is already verified. You can now login.";
//                $status = 403;


            }
        }

    }

    // function to refresh mail
    public function refrechmail($userId){


        $verifyUser = User::find($userId);
        $token = random_int(100000, 999999);

        $verifyUser->token_code = $token;
        $verifyUser->save();

        Mail::send('email.verificationemail', ['token' =>$verifyUser->token_code], function($message) use($verifyUser){
            $message->to($verifyUser->email);
            $message->subject('Code de verification de email');
        });

        return response()->json(['status' =>true,  200]);
    }
}
