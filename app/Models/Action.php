<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Action",
 *     description="Action model",
 *     @OA\Xml(
 *         name="Action"
 *     )
 * )
 */

class Action extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private int $id;

    /**
     * @OA\Property(
     *      title="user_id",
     *      description="id of the user",
     *      example="1"
     * )
     *
     * @var string
     */
    public string $userId;

    /**
     * @OA\Property(
     *      title="car_id",
     *      description="id of the car",
     *      example="1"
     * )
     *
     * @var string
     */
    public string $carId;


    /**
     * @var string[]
     */
    protected $fillable = [
        'userId',
        'carId',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
