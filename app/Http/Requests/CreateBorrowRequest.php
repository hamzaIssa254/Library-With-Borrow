<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class CreateBorrowRequest extends FormRequest
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
            'book_id' => 'required|integer|exists:books,id',

            'due_date' => 'nullable|date|after_or_equal:borrowed_at',
            'returned_at' => 'date|after:borrowed_at'
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
            $book = Book::find($this->book_id);
        $currentDate = Carbon::now();


        if ($this->returned_at && $this->borrowed_at < $currentDate) {
            $validator->errors()->add('returned_at', 'تاريخ الارجاع لا يمكن أن يكون أقدم من التاريخ الحالي.');
        }

        if ($this->due_date && $this->due_date < $currentDate) {
            $validator->errors()->add('due_date', 'تاريخ الإعادة لا يمكن أن يكون أقدم من التاريخ الحالي.');
        }


                $startDate = Carbon::now();
                $endDate = $startDate->copy()->addDays(14);

                if ($book && !$this->isDateRangeAvailable($book->id, $startDate, $endDate)) {
                    $validator->errors()->add('book', 'لا يمكن استعارة الكتاب في هذه الفترة، هناك تداخل مع استعارة أخرى.');
                }

        });
    }
    /**
     * Summary of isDateRangeAvailable
     * @param mixed $bookId
     * @param mixed $startDate
     * @param mixed $endDate
     * @return bool
     */
    private function isDateRangeAvailable($bookId, $startDate, $endDate)
{
    return !Borrow::where('book_id', $bookId)
                  ->where(function ($query) use ($startDate, $endDate) {
                      $query->whereBetween('returned_at', [$startDate, $endDate])
                            ->orWhereBetween('due_date', [$startDate, $endDate])
                            ->orWhere(function ($query) use ($startDate, $endDate) {
                                $query->where('returned_at', '<=', $endDate)
                                      ->where('due_date', '>=', $startDate);
                            });
                  })
                  ->exists();
}
    /**
     * Summary of failedValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {

        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'يرجى التحقق من المدخلات',
            'error' => $validator->errors()
        ]));


    }

    /**
     * Summary of attributes
     * @return string[]
     */
    public function attributes()
    {
        return [
            'book_id' => 'الكتاب',
            'borrowed_at' => 'تاريخ الاستعارة',
            'due_date' => 'تاريخ الإرجاع',
        ];
    }
    /**
     * Summary of messages
     * @return string[]
     */
    public function messages()
    {
        return [
            'due_date.after_or_equal' => 'تاريخ الاعادة يجب أن يكون بعد  تاريخ الاستعارة.',
            'due_date.before' => 'تاريخ الاعادة يجب أن يكون قبل  تاريخ الارجاع.',
            'book_id.exists' => 'هذا الكتاب غير موجود',
        ];
    }
}
