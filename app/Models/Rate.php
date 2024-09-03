<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'user_id', 'rating'];

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
