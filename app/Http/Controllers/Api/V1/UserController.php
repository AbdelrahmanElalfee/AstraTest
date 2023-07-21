<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Symfony\Component\HttpFoundation\Response;
use Rap2hpoutre\FastExcel\FastExcel;

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
        $user = User::create($request->all());

        return response()->json([
            'data' => new UserResource($user)
        ], Response::HTTP_CREATED);
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function import(ImportRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $filePath = $file->path();

        $users = (new FastExcel)->withoutHeaders()->import($filePath)->skip(1)->map(function ($row) {
            return User::create([
                'full_name' => $row[0],
                'email' => $row[1],
                'phone_number' => $row[2]
            ]);
        });

        return response()->json([
            'message' => 'User Added Successfully'
        ], Response::HTTP_CREATED);
    }
}
