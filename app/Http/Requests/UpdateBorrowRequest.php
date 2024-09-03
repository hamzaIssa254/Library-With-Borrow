<?php

namespace App\Http\Requests;

use App\Models\Borrow;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBorrowRequest extends FormRequest
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
            'borrowed_at' => 'nullable|date|after_or_equal:today',
            'due_date' => 'nullable|date|after_or_equal:borrowed_at',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at',
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

                        $existingBorrow = Borrow::where('user_id', $userId)
                                               ->where('book_id', $bookId)
                                               ->exists();




                        if (!$existingBorrow) {
                            $validator->errors()->add('book_id', 'لا يمكنك التعديل على استعارة ليست لك.');
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
            'book_id' => 'معرّف الكتاب',
            'borrowed_at' => 'تاريخ الاستعارة',
            'due_date' => 'تاريخ الإرجاع المتوقع',
            'returned_at' => 'تاريخ الإرجاع الفعلي',
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
            'book_id.required' => 'معرّف الكتاب مطلوب.',
            'book_id.exists' => 'معرّف الكتاب غير موجود.',
            'borrowed_at.date' => 'تاريخ الاستعارة يجب أن يكون تاريخًا صحيحًا.',
            'borrowed_at.after_or_equal' => 'تاريخ الاستعارة يجب أن يكون اليوم أو بعده.',
            'due_date.date' => 'تاريخ الإرجاع المتوقع يجب أن يكون تاريخًا صحيحًا.',
            'due_date.after_or_equal' => 'تاريخ الإرجاع المتوقع يجب أن يكون بعد تاريخ الاستعارة.',
            'returned_at.date' => 'تاريخ الإرجاع الفعلي يجب أن يكون تاريخًا صحيحًا.',
            'returned_at.after_or_equal' => 'تاريخ الإرجاع الفعلي يجب أن يكون بعد تاريخ الاستعارة.',
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
