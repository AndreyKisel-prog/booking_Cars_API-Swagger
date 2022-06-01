<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/cars",
     *      operationId="getCarsList",
     *      tags={"Cars"},
     *      summary="Get list of cars",
     *      description="Returns list of cars",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(): array
    {
        $cars = Car::all();
        $total = $cars->count();
        return compact('cars', 'total');
    }

    /**
     * @param CarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *      path="/cars",
     *      operationId="storeCar",
     *      tags={"Cars"},
     *      summary="Store new car",
     *      description="Returns car data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CarRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="new car created",
     *          @OA\JsonContent(ref="#/components/schemas/Car")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Error: new car has not been created"
     *      )
     * )
     */
    public function store(CarRequest $request): \Illuminate\Http\JsonResponse
    {
        $car = Car::create($request->validated());
        if ($car) {
            return response()->json([
                'message' => 'new car created',
                'status'  => 'ok',
            ], 201);
        }
        return response()->json(['status' => 'failed'], 400);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/cars/{id}",
     *      operationId="getCarsById",
     *      tags={"Cars"},
     *      summary="Get car information",
     *      description="Returns car data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Car id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Car")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="404 not found"
     *      ),
     * )
     */
    public function show($id): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $car = Car::findorfail($id);
            return response()->json(['car' => $car]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'ERROR',
                'error'  => '404 not found'
            ], 404);
        }
    }

    /**
     * @param CarRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Put(
     *      path="/cars/{id}",
     *      operationId="updateCars",
     *      tags={"Cars"},
     *      summary="Update existing car",
     *      description="Returns updated car data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Car id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CarRequest")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Car")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
     */
    public function update(CarRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $car = Car::findOrFail($id);

        $car->update($request->validated());
        return response()->json(['message' => 'car updated successfully']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Delete(
     *      path="/cars/{id}",
     *      operationId="deleteCars",
     *      tags={"Cars"},
     *      summary="Delete existing car",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Car id",
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
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $car = Car::findOrFail($id);
        if ($car->delete()) {
            return response()->json(['message' => 'car has been deleted successfully'], 204);
        }
        return response()->json(['status' => 'failed'], 400);
    }
}
