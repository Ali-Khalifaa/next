<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    /**
     * change Password.
     */

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $user = User::findOrFail($request->user_id);
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);
        if($request->role_id != null)
        {
            $user->syncRoles([$request->role_id]);
        }
        return response()->json('password changed successfully');
    }
}
