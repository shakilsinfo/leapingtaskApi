<?php
namespace App\ApiResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticResource{

	public function getRegistered($requestData){
		try {

            $user = new User;
            $user->name = $requestData->input('name');
            $user->email = $requestData->input('email');
            $plainPassword = $requestData->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json([
                'status' => 200,
                'message' => 'User Registration Successfull',
                'user' => [
                    'user_name' => $user->name,
                    'user_email' => is_null($user->email)?'':$user->email,
                ] 
            ], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json([
                'status' => 409,
                'message' => 'User Registration Failed!',
                'errorMsg' => $e->getMessage()
            ], 409);
        }
	}

    public function getUserPaginate(){
        $userList = User::orderBy('name','asc')->paginate(3);

        try {
           if(!empty($userList)){
               return response()->json([
                    'status' => 200,
                    'message' => 'User Found',
                    'data' => $userList
                ], 200); 
            }else{
                return response()->json([
                    'status' => 200,
                    'message' => 'No data found!',
                    'data' => ''
                ], 200);
            } 
        } catch (Exception $e) {
            return response()->json([
                'status' => 409,
                'message' => $e->getMessage()
            ], 409);
        }
        
    }
}