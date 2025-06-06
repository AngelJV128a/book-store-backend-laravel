<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_client',
        'date',
        'total',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function saleDetail(){
        return $this->hasMany(SaleDetail::class, 'id_sale');
    }
}
