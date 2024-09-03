<?php

namespace App\Http\Requests;

use App\Models\Rate;
use App\Models\Borrow;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'book_id' => 'nullable|exists:books,id',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }
    /**
     * Summary of withValidator
     * @param mixed $validator
     * @return void
     */
    public function withValidator($validator)
    {


                    $validator->after(function ($validator) {
                        $userId = Auth::id();
                        $bookId = $this->input('book_id');

                        $existingRating = Rate::where('user_id', $userId)
                                               ->where('book_id', $bookId)
                                               ->exists();




                        if (!$existingRating) {
                            $validator->errors()->add('book_id', 'لا يمكنك التعديل على تقييم ليس لك.');
                        }


                    });
    }

    /**
     * Customize the attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'معرّف المستخدم',
            'book_id' => 'معرّف الكتاب',
            'rating' => 'التقييم',
        ];
    }

    /**
     * Customize the error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'معرّف المستخدم مطلوب.',
            'user_id.exists' => 'معرّف المستخدم غير موجود.',
            'user_id.integer' => 'معرّف المستخدم يجب أن يكون رقمًا صحيحًا.',
            'book_id.required' => 'معرّف الكتاب مطلوب.',
            'book_id.exists' => 'معرّف الكتاب غير موجود.',
            'book_id.integer' => 'معرّف الكتاب يجب أن يكون رقمًا صحيحًا.',
            'rating.required' => 'التقييم مطلوب.',
            'rating.integer' => 'التقييم يجب أن يكون رقمًا صحيحًا.',
            'rating.min' => 'التقييم يجب أن يكون على الأقل 1.',
            'rating.max' => 'التقييم لا يمكن أن يتجاوز 5.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException(
            ApiResponseService::error('Validation Errors', 422, $errors)
        );
    }

}
