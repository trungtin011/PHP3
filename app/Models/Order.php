<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'payment_method',
        'total',
        'status',
        'shipping_fee',
        'discount',
        'notes', // Ensure this field is fillable
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function createOrder($userId, $address, $paymentMethod, $cartItems  = 0, $discount = 0, $notes = null)
    {
        $total = $cartItems->sum('total') - $discount;

        // Lưu dữ liệu vào bảng `orders`
        $order = self::create([
            'user_id' => $userId,
            'address' => $address,
            'payment_method' => $paymentMethod,
            'total' => $total,
            'discount' => $discount,
            'status' => 'pending',
            'notes' => $notes,
        ]);

        // Lưu từng mục giỏ hàng vào bảng `order_items`
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'product_image' => $item->product_image,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
            ]);
        }

        return $order;
    }
}
