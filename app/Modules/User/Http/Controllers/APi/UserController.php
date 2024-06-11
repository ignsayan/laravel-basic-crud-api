<?php

namespace App\Modules\User\Http\Controllers\APi;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
        $searchParams = $request->query('search');
        $users = User::whereHas('roles', function ($query) {
            return $query->where('name', User::ROLE_USER);
        })->when($searchParams != '', function ($query) use ($searchParams) {
            return $query->where(function ($sub_query) use ($searchParams) {
                return $sub_query->where('name', 'REGEXP', $searchParams)
                    ->orWhere('email', 'REGEXP', $searchParams)
                    ->orWhere('phone_no', 'REGEXP', $searchParams);
            });
        })->paginate(10);
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
