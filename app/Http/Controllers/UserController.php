<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * list view
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function index()
    {
        return view('user-list');
    }

    /**
     * get  all users
     *
     * @return void
     */
    function list()
    {
        $users = User::all();

        return $users;
    }

    /**
     * get user details
     *
     * @param User $user
     * @return void
     */
    function userDetails(User $user)
    {
        return $user;
    }

    /**
     * update user details
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function update(User $user, Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
                'user_type' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            ]);

            $user->update($validated);
            return response()->json(['success' => 'User details updated successfully!'], 200);
        } catch (ValidationException $e) {
            $error = collect($e->errors())->first()[0];
            return response()->json(['error' => $error], 422);
        }
    }

    /**
     * process delete user request
     *
     * @param User $user
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function delete(User $user)
    {
        $deleteUser =  $user->delete();

        if ($deleteUser) {
            return response()->json(['success' => 'User deleted successfully!'], 200);
        } else {
            return response()->json(['error' => 'User not deleted!'], 200);
        }
    }
}
