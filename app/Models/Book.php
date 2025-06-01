<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'isbn',
        'editorial_id',
        'category_id',
        'price',
        'stock',
        'release_date',
        'language',
        'image',
        'description',
    ];

    public function author(){
        return $this->belongsTo(Author::class);
    }

    public function editorial(){
        return $this->belongsTo(Editorial::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function saleDetail(){
        return $this->hasMany(SaleDetail::class);
    }
}
