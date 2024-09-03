<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JsonException;

class CreateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Summary of prepareForValidation
     * @return void
     */
    public function prepareForValidation()
    {

         $this->merge([
            // 'title' => ,
            'title' => preg_replace('/\b/','',ucwords($this->input('title')))
         ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|string',
            'category_id' =>'required|integer',
            'author'=>'required|string|min:4',
            'description'=>'required|string|max:255',
            'published_at'=>'required|date'
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // throw new  JsonException($validator->errors(),404);
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'يرجى التحقق من المدخلات',
            'error' => $validator->errors()
        ]));


    }
   

    public function attributes()
    {
        return [
            'title' => 'اسم الكتاب',
            'author' => 'مؤلف الكتاب',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'اسم الكتاب مطلوب.',
            'author.required' => 'مؤلف الكتاب مطلوب ويجب أن يحتوي على 3 حروف على الأقل.',
        ];
    }
}
