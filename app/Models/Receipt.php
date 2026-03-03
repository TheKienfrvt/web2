<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'receipt';
    protected $primaryKey = 'receipt_id';
    public $timestamps = false;
    protected $fillable = [
        'supplier_id',
        'order_date',
        'status'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'status' => 'string'
    ];

    protected $attributes = [
        'status' => 'đang chờ'
    ];

    protected $appends = ['total_amount', 'quantity_product'];

    /**
     * Relationship với Supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relationship với ReceiptDetail
     */
    public function receiptDetails(): HasMany
    {
        return $this->hasMany(ReceiptDetail::class, 'receipt_id');
    }

    /**
     * Tính tổng giá trị receipt
     */
    public function getTotalAmountAttribute(): int
    {
        return $this->receiptDetails->sum(function ($detail) {
            return $detail->quantity * $detail->price;
        });
    }

    /**
     * Tính tổng số lượng sản phẩm
     */
    public function getQuantityProductAttribute(): int
    {
        return $this->receiptDetails->sum('quantity');
    }
}
