<?php

namespace App\Models;

use PhpParser\Node\Stmt\Static_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'description', 'published_at', 'category_id'];

    protected $casts = ['category_id' => 'integer'];
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }



    public function ratings()
    {
        return $this->hasMany(Rate::class);
    }



    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    


}
