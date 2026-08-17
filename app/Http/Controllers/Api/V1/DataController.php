<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Data;
use App\Models\User;

class DataController extends Controller
{
    public function store(Request $req): JsonResponse
    {
        $data = Data::create([
            'id'=>$req->id,
            'name'=>$req->name,
            'address'=>$req->address,
            'dob'=>$req->dob,
            'gender'=>$req->gender,
        ]);

        return response()->json([
            'status' => 201,
            'message'=> "data inserted successfully",
            "data"=> $data,
        ], 201);
    }

    public function index()
    {
        // $users = User::limit(10)->get();
        // $users = User::all();
        $users = Data::take(10)->get();

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $users,
        ],200);
    }

    public function updatePut(Request $request, $id)
    {
        // Validate all fields
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'dob'     => 'required|date',
            'gender'  => 'required|in:Male,Female,Other',
        ]);

        // Find the record
        $data = Data::find($id);

        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Record not found',
            ], 404);
        }

        // Replace all fields
        $data->name = $request->name;
        $data->address = $request->address;
        $data->dob = $request->dob;
        $data->gender = $request->gender;

        // Save changes
        $data->save();

        return response()->json([
            'status' => 200,
            'message' => 'Record updated successfully',
            'data' => $data,
        ], 200);
    }

   public function updatePatch(Request $request, $id)
    {
        // Validate only provided fields
        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'dob'     => 'sometimes|date',
            'gender'  => 'sometimes|in:Male,Female,Other',
        ]);

        // Find the record
        $data = Data::find($id);

        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Record not found',
            ], 404);
        }

        // Update only provided fields
        if ($request->has('name')) {
            $data->name = $request->name;
        }

        if ($request->has('address')) {
            $data->address = $request->address;
        }

        if ($request->has('dob')) {
            $data->dob = $request->dob;
        }

        if ($request->has('gender')) {
            $data->gender = $request->gender;
        }

        // Save changes
        $data->save();

        return response()->json([
            'status' => 200,
            'message' => 'Record updated successfully',
            'data' => $data,
        ], 200);
    }

    public function destroy($id){
        $data = Data::find($id);

        if(!$data){
            return response()->json([
                'status' => 404,
                'message' => 'Record not found',
            ],404);
        }

        $data->delete();

        return response()->json([
            'status' => 200,
            'message'=>'Data Delete Successfully'
        ],200);
        
    }
}
