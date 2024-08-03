<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;

    protected $table = 'payment_logs';
    // protected $guarded = ['id'];
    protected $fillable = [
        'status', 'payment_type', 'raw_response', 'order_id'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];

    public function order() 
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
