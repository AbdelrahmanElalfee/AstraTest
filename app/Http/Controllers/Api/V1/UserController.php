<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => UserResource::collection(User::all())
        ], Response::HTTP_OK);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $user = User::create($request);

        return response()->json([
            'data' => new UserResource($user)
        ], Response::HTTP_CREATED);
    }

    public function import(ImportRequest $request): JsonResponse
    {
        Excel::import(new UsersImport, $request->file('file'));

        return response()->json([
            'message' => 'User Added Successfully'
        ], Response::HTTP_CREATED);
    }
}
