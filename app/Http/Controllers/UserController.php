<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Simplified add: Ang validation exception i-handle na sa Handler.php
    public function add(Request $request ){
        $rules = [
            'username' => 'required|string|unique:users,username|max:20',
            'password' => 'required|string|min:5|max:20',
        ]; 

        $this->validate($request, $rules);

        $user = User::create($request->all());
        return response()->json(['message' => 'User created successfully', 'data' => $user], 201);
    }

    public function show($id){
        // FindOrFail automatically throws an exception if not found
        $user = User::findOrFail($id);
        return response()->json(['message' => 'User retrieved successfully', 'data' => $user]);
    }

    public function update(Request $request, $id) {
        // I-validate ang update inputs
        $this->validate($request, [
            'username' => 'required|string|max:20',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());
        
        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
    }

    public function delete($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}