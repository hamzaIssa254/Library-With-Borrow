<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpadateUserRequest extends FormRequest
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
            'name' => 'nullable|string|min:3|max:30',
            'email' => 'nullable|string|email|max:30|unique:users,email',
            'password' => 'nullable|string|min:8|max:30'
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
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'يرجى التحقق من المدخلات',
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المستخدم',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور'
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'يجب أن يكون اسم المستخدم نصياً.',
            'name.min' => 'يجب أن يكون اسم المستخدم على الأقل 3 أحرف.',
            'name.max' => 'يجب أن لا يتجاوز اسم المستخدم 30 حرفاً.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'email.unique' => 'البريد الإلكتروني المستخدم موجود بالفعل.',
            'password.min' => 'يجب أن تكون كلمة المرور على الأقل 8 أحرف.',
            'password.max' => 'يجب أن لا تتجاوز كلمة المرور 30 حرفاً.'
        ];
    }



}
