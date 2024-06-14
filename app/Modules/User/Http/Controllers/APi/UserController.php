<?php

namespace App\Modules\User\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', User::ROLE_USER);
        })
            ->when($search != '', function ($query) use ($search) {
            return $query->where(function ($sub_query) use ($search) {
                return $sub_query->where('name', 'REGEXP', $search)
                    ->orWhere('email', 'REGEXP', $search)
                    ->orWhere('phone_no', 'REGEXP', $search);
            });
        })->paginate(10);

        $users->each(function ($user) {
            $user->avatar = $user->getFirstMediaUrl('avatar');
            $user->banner = $user->getFirstMediaUrl('banner');
            return $user;
        });

        return (new JsonResourceResponse(new UserResource($users), 200, ''))->response();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
