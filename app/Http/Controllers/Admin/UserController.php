<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        // Views now fetch user lists directly from the API via axios in the client.
        return view('admin.user');
    }

    public function create()
    {
        return view('admin.user_create');
    }

    public function store(Request $request)
    {
        abort(404, 'Use API endpoints directly from the frontend.');
    }

    public function edit(User $user)
    {
        // The edit view will fetch the user data via axios on the client-side.
        return view('admin.user_edit', ['user' => (object)['id' => $user->id]]);
    }

    public function update(Request $request, User $user)
    {
        abort(404, 'Use API endpoints directly from the frontend.');
    }

    public function destroy(User $user)
    {
        abort(404, 'Use API endpoints directly from the frontend.');
    }
}
