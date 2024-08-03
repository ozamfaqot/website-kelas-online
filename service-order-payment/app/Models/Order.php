<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    // protected $guarded = ['id'];
    protected $fillable = [
        'status', 'user_id', 'course_id', 'snap_url', 'metadata'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',        
        'metadata' => 'array'
    ];

    public function paymentLogs()
    {
        return $this->hasOne(PaymentLog::class);
    }
}
