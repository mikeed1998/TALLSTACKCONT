<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'total_amount',
        'status',
        'shipping_address'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total_amount, 2);
    }
}