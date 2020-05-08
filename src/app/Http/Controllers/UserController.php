<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function getUser($id)
    {
        return User::findOrFail($id);
    }

    public function getOrders($id)
    {
        $user = User::findOrFail($id);
        return $user->orders()->get();
    }
}
