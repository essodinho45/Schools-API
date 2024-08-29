<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Faker\Generator as Faker;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request, Faker $faker)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'phone' => 'required|unique:users,name',
                    //'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->phone,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'phone' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::firstWhere('name', $request['phone']);
            if ($user == NULL) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone & Password does not match with our record.',
                ], 401);
            }

            if (($user->freezed == true)) {
                $user->tokens()->delete();
                return response()->json([
                    'status' => false,
                    'message' => 'User Is Freezed.',
                ], 401);
            }

            if (!Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $user->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'old_password' => 'required',
                    'new_password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = auth()->user();
            if (!(Hash::check($request->old_password, $user->password))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Old password does not match current user.',
                ], 401);
            }
            $user->password = Hash::make($request->new_password);
            if (!$user->save()) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occured. User has not been saved.',
                ], 401);
            }
            // return true;

            $user->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'User Password Updated',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function addDeviceKey(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'device_key' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $old_device_user = User::where('device_key', $request->device_key)->first();
            $user = auth()->user();
            if ($old_device_user != null) {
                if ($old_device_user->id != $user->id) {
                    $old_device_user->device_key = null;
                    $old_device_user->save();
                }
            }

            if ($user->device_key != $request->device_key) {
                $user->device_key = $request->device_key;
                if (!$user->save()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'An error occured. Key has not been saved.',
                    ], 401);
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Device key added.'
                //'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function removeDeviceKey(Request $request)
    {
        try {

            $user = auth()->user();
            $user->device_key = NULL;
            if (!$user->save()) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occured. Key has not been saved.',
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Device key removed.'
                //'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = User::where('name', $request->input('phone'))->first();
        $user->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out.'
        ], 200);
    }

    public function freezeUser(Request $request)
    {
        $user = User::where('name', $request->input('phone'))->first();
        $user->freezed = true;
        $user->save();
        $user->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User freezed.'
        ], 200);
    }
}
