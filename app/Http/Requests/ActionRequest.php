<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @OA\Schema(
 *      title="Store Action request",
 *      description="Store Action request body data",
 *      type="object",
 *      required={"userId", "carId"}
 * )
 */
class ActionRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="userId",
     *      description="id of the user",
     *      example="1"
     * )
     *
     * @var string
     */
    public string $userId;

    /**
     * @OA\Property(
     *      title="carId",
     *      description="id of the car",
     *      example="1"
     * )
     *
     * @var string
     */
    public string $carId;



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['userId' => "string", 'carId' => "string"])] public function rules(): array
    {
        return [
            'userId' => 'required|int',
            'carId' => 'required|int',
        ];
    }
}
