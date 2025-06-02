<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected$table = 'sale_detail';

    protected $fillable = [
        'id_sale',
        'id_book',
        'quantity',
        'unit_price',
    ];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function book(){
        return $this->belongsTo(Book::class);
    }
}
