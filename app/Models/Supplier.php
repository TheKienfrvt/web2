<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'supplier_name',
        'supplier_phone',
        'supplier_address'
    ];

    /**
     * Relationship với Receipt
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class, 'supplier_id');
    }
}
