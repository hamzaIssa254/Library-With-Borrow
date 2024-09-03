<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrow extends Model
{
    use HasFactory;

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = ['book_id','user_id','borrowed_at','due_date','returned_at'];
    /**
     * Summary of casts
     * @var array
     */
    protected $casts = [
        'book_id' => 'integer',
        'user_id' => 'integer',
        'borrowed_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date'

    ];
    /**
     * Summary of book
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    /**
     * Summary of user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Summary of boot
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($borrow) {
            $borrow->user_id = Auth::user()->id;
        });
       
    }
}
