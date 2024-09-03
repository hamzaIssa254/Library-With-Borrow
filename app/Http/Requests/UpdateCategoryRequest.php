<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|unique:categories,name'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {

        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->name),
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        // Customize the response on validation failure
        $response = response()->json([
            'status' => 'error',
            'message' => 'Validation failed for the update category request.',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }

     /**
     * Get custom error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'The category name must be a valid string.',
            'name.unique' => 'This category name is already taken, please choose another one.',
        ];
    }

}
