<?php 

namespace App\Services;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{
    public function allUsers()
    {
        // return User::all();
        return UserResource::collection(User::all());
    }
}