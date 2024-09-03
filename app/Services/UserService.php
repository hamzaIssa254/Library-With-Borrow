<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function listAllUsers(int $perPage)
    {
        $cacheKey = 'users_' . md5( $perPage . request('page', 1));

        // Check if the cached result exists
        $users = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage)
         {
            $userQuery = User::query();
            $userQuery->select('name','email');
            return $userQuery->paginate($perPage);

         });
         return $users;
    }

    public function createUser(array $data)
    {
        DB::beginTransaction();
        try
        {
           $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
           ]);
            DB::commit();
            return $user;
        }catch(Exception $e)
        {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUser(User $user)
    {
        return $user;
    }

    public function updateUser(array $data,User $user)
    {
        $user->update(array_filter($data));
        return $user;
    }

    public function deleteUser(User $user)
    {
        $user->delete();

    }
}
