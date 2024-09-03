<?php

namespace App\Http\Requests;

use App\Models\Rate;
use App\Models\Borrow;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class RatingRequest extends FormRequest
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
           'user_id' => 'exists:users,id|integer',
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',


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

                           $borrowed = Borrow::where('user_id', $userId)
                          ->where('book_id', $bookId)
                          ->whereNull('returned_at')
                          ->exists();


                        if ($existingRating) {
                            $validator->errors()->add('book_id', 'لا يمكنك إضافة أكثر من تقييم على نفس الكتاب.');
                        }
                        if (!$borrowed) {
            $validator->errors()->add('book_id', 'لا يحق لك تقييم كتاب لم تقم باستعارته.');
        }

                    });
    }
    /**
     * Summary of prepareValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    protected function prepareValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException(ApiResponseService::error('Validation Errors',422,$errors));
    }

    /**
     * Customize the attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [

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
           
            'user_id.exists' => 'المستخدم غير موجود.',
            'book_id.required' => 'معرّف الكتاب مطلوب.',
            'book_id.exists' => 'الكتاب غير موجود.',
            'rating.required' => 'التقييم مطلوب.',
            'rating.integer' => 'التقييم يجب أن يكون عدد صحيح.',
            'rating.min' => 'التقييم يجب أن يكون على الأقل 1.',
            'rating.max' => 'التقييم يمكن أن يكون بحد أقصى 5.',
        ];
    }
}
