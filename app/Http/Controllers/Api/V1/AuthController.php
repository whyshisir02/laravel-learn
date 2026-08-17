<?php

// namespace App\Http\Controllers\Api\V1;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Illuminate\Http\JsonResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;


// class AuthController extends Controller
// {
//     public function register(Request $request): JsonResponse
//     {
//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         return response()->json([
//             'message' => 'User registered successfully',
//         ], 201);
//     }

//     public function login(Request $request): JsonResponse
//     {

//         $user = User::where('email', $request->email)->first();

//         if (!$user) {
//             return response()->json([
//                 'message' => 'Invalid credentials'
//             ], 401);
//         }

//         if (!Hash::check($request->password, $user->password)) {
//             return response()->json([
//                 'message' => 'Invalid credentials'
//             ], 401);
//         }

//         $token = $user->createToken('api-token')->plainTextToken;

//         return response()->json([
//             'message' => 'Login successful',
//             'token' => $token,
//         ], 200);
//     }
// }


// app/Http/Controllers/Api/V1/AuthController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password;
class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([            
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()   // At least one uppercase & one lowercase
                    ->numbers()     // At least one number
                    ->symbols(),    // At least one special character
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        
        return response()->json([
            'status' => 201,
            'message' => 'User registered successfully',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status_code' => 401,
                'message'     => 'Invalid credentials',
            ], 401);
        }

        // Create a Personal Access Token via Passport
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;

        return response()->json([
            'status_code'  => 200,
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_at'   => $tokenResult->token->expires_at,
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke the token currently being used
        $request->user()->token()->revoke();

        return response()->json([
            'status_code' => 200,
            'message'     => 'Logged out successfully',
        ]);
    }

    public function updatePut(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::find($id);

        if(!$user){
            return response()->json([
                'message' => "User not found",
            ],404);
        }

        
        $user->name = $request->name;

        $user->email = $request->email;

        $user->save();

        return response()->json([
            'message' => "User update suuccessfully",
            'User'    => $user,
        ],200);
    }

    // public function index()
    // {
    //     // $response = Http::get('https://jsonplaceholder.typicode.com/posts');
    //     $response = Http::withoutVerifying()
    // ->get('https://jsonplaceholder.typicode.com/posts');

    //     return $response->json();
    // }

    public function list()
    {
        // $users = User::limit(10)->get();
        // $users = User::all();
        // $users = User::take(10)->get();

            // $users = User::paginate(50);
        $users = User::cursorPaginate();


        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $users,
        ],200);
    }

    public function index()
{
    try {

        // $response = Http::timeout(5)->get('http://127.0.0.1:9999/posts');
        //     $response = Http::withoutVerifying()
        // ->get('https://jsonplaceholder.typicode.com/posts');
        $response = Http::withoutVerifying()->get('https://jsonplaceholder.typide.com/posts');

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => $response->json()
        ],200);

    } catch (ConnectionException $e) {

        return response()->json([
            'status'   => 503,
            'success' => false,
            'message' => 'No internet connection. Please check your network and try again.'
        ], 503);

    }
}

        public function destroy($id){
        $data = User::find($id);

        if(!$data){
            return response()->json([
                'status' => 404,
                'message' => 'Record not found',
            ],404);
        }

        if($data->role === "admin"){
            return response()->json([
                'status' => 403,
                'message' => 'Cannot delete admin role',
            ],403);
        }

        $data->delete();

        return response()->json([
            'status' => 200,
            'message'=>'Data Delete Successfully'
        ],200);
        
    }
}

