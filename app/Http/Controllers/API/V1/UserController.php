<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      tags={"Users"},
     *      summary="Get list of users",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index()
    {
        return User::all();
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *      path="/users",
     *      operationId="storeUser",
     *      tags={"Users"},
     *      summary="Store new user",
     *      description="Returns user data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="new user created",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Error: new user has not been created"
     *      )
     * )
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        if ($user) {
            return response()->json([
                'status'  => 'ok',
                'message' => 'new user created'
            ], 201);
        }

        return response(
            [
                'status'  => 'failed',
                'message' => 'Error: new user has not been created'
            ],
            400
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="getUsersById",
     *      tags={"Users"},
     *      summary="Get user information",
     *      description="Returns user data",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="404 not found"
     *      ),
     * )
     */
    public function show($id)
    {
        try {
            return User::findorfail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'ERROR',
                'error' => '404 not found'
            ], 404);
        }
    }

    /**
     * @param UserRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUsers",
     *      tags={"Users"},
     *      summary="Update existing user",
     *      description="Returns updated user data",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->validated());
        $user->save();
        return response()->json(['message' => 'user has been updated']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUsers",
     *      tags={"Users"},
     *      summary="Delete existing user",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id) {
        $user = User::findOrFail($id);
        if ($user->delete()) {
            return response()->json(['message' => 'user has been deleted'], 204);
        }
        return response()->json(['status' => 'failed'], 400);
    }
}
