<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Action request",
 *      description="Store Action request body data",
 *      type="object",
 *      required={"user_id", "car_id"}
 * )
 */
class ActionRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="user_id",
     *      description="id of the user",
     *      example="1"
     * )
     *
     * @var string
     */
    public $user_id;

    /**
     * @OA\Property(
     *      title="car_id",
     *      description="id of the car",
     *      example="1"
     * )
     *
     * @var string
     */
    public $car_id;



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|int',
            'car_id' => 'required|int',
        ];
    }
}
