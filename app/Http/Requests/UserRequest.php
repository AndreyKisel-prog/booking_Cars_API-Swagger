<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *      title="Store User request",
 *      description="Store User request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UserRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new user",
     *      example="Alex"
     * )
     *
     * @var string
     */
    public $name;


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
            'name'=> 'required|string|unique:users|max:255',
        ];
    }
}
