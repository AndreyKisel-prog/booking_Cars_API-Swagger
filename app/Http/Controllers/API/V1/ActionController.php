<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionRequest;
use App\Models\Action;

class ActionController extends Controller
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    /**
     * @OA\Get(
     *      path="/actions",
     *      operationId="getActionsList",
     *      tags={"Actions"},
     *      summary="Get list of actions",
     *      description="Returns list of actions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index()
    {
        return Action::all();
    }


    /**
     * @param ActionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *      path="/actions",
     *      operationId="storeAction",
     *      tags={"Actions"},
     *      summary="Store new action",
     *      description="Returns actions data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ActionRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="new action created",
     *          @OA\JsonContent(ref="#/components/schemas/Action")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Error: new action has not been created"
     *      )
     * )
     */
    public function store(ActionRequest $request)
    {
        try {
            $action = Action::create($request->validated());
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response([
                    'status' => 'failed',
                    'error'  => 'car or user is already busy'
                ]);
            }
        }
        if ($action) {
            return response(['status' => 'ok']);
        }
        return response(['status' => 'failed']);
    }

    /**
     * @param Action $action
     * @return mixed
     */
    /**
     * @OA\Get(
     *      path="/actions/{id}",
     *      operationId="getActionsById",
     *      tags={"Actions"},
     *      summary="Get action information",
     *      description="Returns action data",
     *      @OA\Parameter(
     *          name="id",
     *          description="action id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Action")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="404 not found"
     *      ),
     * )
     */
    public function show(Action $action)
    {
        try {
            return Action::findorfail($action);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'ERROR',
                'error'  => '404 not found'
            ], 404);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/actions/{id}",
     *      operationId="deleteActions",
     *      tags={"Actions"},
     *      summary="Delete existing action",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Action id",
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
    public function destroy($id)
    {
        $user = Action::findOrFail($id);

        if ($user->delete()) {
            return response(null, 204);
        }

        return response(['status' => 'failed']);
    }
}
