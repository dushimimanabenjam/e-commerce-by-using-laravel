<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(customer::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(order_detail::class);
    }

    public function deductStockForShipping(): void
    {
        $this->orderDetails()
            ->where('stock_deducted', false)
            ->each(function (order_detail $detail) {
                product::where('id', $detail->product_id)
                    ->where('stock', '>=', $detail->quantity)
                    ->decrement('stock', $detail->quantity);

                $detail->update(['stock_deducted' => true]);
            });
    }

    public function restoreStockIfNeeded(): void
    {
        $this->orderDetails()
            ->where('stock_deducted', true)
            ->each(function (order_detail $detail) {
                product::where('id', $detail->product_id)
                    ->increment('stock', $detail->quantity);

                $detail->update(['stock_deducted' => false]);
            });
    }
}
