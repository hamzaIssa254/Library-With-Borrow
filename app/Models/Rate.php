<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = ['book_id', 'user_id', 'rating'];
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
        static::creating(function ($rate) {
            $rate->user_id = Auth::user()->id;
        });
        static::updating(function ($rate) {
            $rate->user_id = Auth::user()->id;
        });
        static::deleting(function ($rate) {
            $rate->user_id = Auth::user()->id;
        });
    }
}
