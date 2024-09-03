<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = ['book_id','user_id','borrowed_at','due_date','returned_at'];

    protected $casts = [
        'book_id' => 'integer',
        'user_id' => 'integer',
        'borrowed_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date'

    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function ($borrow) {
            $borrow->user_id = Auth::user()->id;
        });
         static::updating(function ($borrow) {
            // $borrow->user_id = Auth::user()->id;
            // $borrow->book_id = $borrow->book->id;
        });
    }
}
