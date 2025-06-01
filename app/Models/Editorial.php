<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'website',
    ];

    public function books(){
        return $this->hasMany(Book::class);
    }
}
