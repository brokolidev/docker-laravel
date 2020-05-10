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

    public function getUsers(Request $request){

        $pageLimit = 15;
        if($request->has('pageLimit')){
            $pageLimit = request('pageLimit');
        }

        $where = [];
        if($request->has('name')){
            array_push($where, ['name', '=', request('name')]);
        }
        if($request->has('email')){
            array_push($where, ['email', '=', request('email')]);
        }

        $user = User::with('lastOrder')->where($where)->paginate($pageLimit);

        return $user;
    }
}
