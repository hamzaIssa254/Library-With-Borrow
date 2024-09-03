<?php

namespace App\Http\Requests;

use App\Services\ApiResponseService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:30|unique:users,email',
            'password' => 'required|string|min:8|max:30'
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
            'name' => 'اسم المستخدم',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
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
            'name.required' => 'اسم المستخدم مطلوب.',
            'name.string' => 'اسم المستخدم يجب أن يكون نص.',
            'name.min' => 'اسم المستخدم يجب أن يكون على الأقل 3 أحرف.',
            'name.max' => 'اسم المستخدم يمكن أن يكون بحد أقصى 30 حرفًا.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نص.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني عنوان بريد إلكتروني صالح.',
            'email.max' => 'البريد الإلكتروني يجب أن لا يتجاوز 30 حرفًا.',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string' => 'كلمة المرور يجب أن تكون نص.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
            'password.max' => 'كلمة المرور يمكن أن تكون بحد أقصى 30 حرفًا.',
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
