<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSystem extends Model
{
    use HasFactory;
    protected $table = 'payment';
    protected $fillable = [
        'entered_amount',
        'email',
        'amount',
        'from_currency',
        'to_currency',
        'status',
        'gateway_id',
        'gateway_url',
    ];
}
