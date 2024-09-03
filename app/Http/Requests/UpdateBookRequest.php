<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\ApiResponseService;

class UpdateBookRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'author' => 'nullable|string|min:4|max:255',
            'description' => 'nullable|string|max:255',
            'published_at' => 'nullable|date|before_or_equal:today',
        ];
    }

    /**
     * Customize the attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'عنوان الكتاب',
            'category_id' => 'معرّف التصنيف',
            'author' => 'اسم المؤلف',
            'description' => 'وصف الكتاب',
            'published_at' => 'تاريخ النشر',
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
            'title.string' => 'عنوان الكتاب يجب أن يكون نصًا.',
            'title.max' => 'عنوان الكتاب لا يمكن أن يتجاوز 255 حرفًا.',
            'category_id.integer' => 'معرّف التصنيف يجب أن يكون رقمًا صحيحًا.',
            'category_id.exists' => 'معرّف التصنيف غير موجود.',
            'author.string' => 'اسم المؤلف يجب أن يكون نصًا.',
            'author.min' => 'اسم المؤلف يجب أن يحتوي على 4 أحرف على الأقل.',
            'author.max' => 'اسم المؤلف لا يمكن أن يتجاوز 255 حرفًا.',
            'description.string' => 'وصف الكتاب يجب أن يكون نصًا.',
            'description.max' => 'وصف الكتاب لا يمكن أن يتجاوز 255 حرفًا.',
            'published_at.date' => 'تاريخ النشر يجب أن يكون تاريخًا صحيحًا.',
            'published_at.before_or_equal' => 'تاريخ النشر يجب أن يكون اليوم أو قبل ذلك.',
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
