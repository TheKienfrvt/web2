<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyboardDetail extends Model
{
    use HasFactory;

    protected $table = 'keyboard_detail';
    protected $primaryKey = 'keyboard_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'thuong_hieu',
        'ket_noi',
        'description'
    ];

    /**
     * Relationship với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Lấy attributes cho filter
     */
    public static function getFilterAttributes()
    {
        return [
            'thuong_hieu' => 'Thương hiệu',
            'ket_noi' => 'Kết nối'
        ];
    }

    public static function getDetailAttributes()
    {
        return [
            'thuong_hieu' => 'Thương hiệu',
            'ket_noi' => 'Kết nối'
        ];
    }
    /**
     * Scope để lấy keyboard theo loại kết nối
     */
    public function scopeTheoKetNoi($query, $ketNoi)
    {
        return $query->where('ket_noi', $ketNoi);
    }

    /**
     * Scope để lấy keyboard có dây/không dây
     */
    public function scopeCoDay($query)
    {
        return $query->where('ket_noi', 'like', '%có dây%');
    }

    public function scopeKhongDay($query)
    {
        return $query->where('ket_noi', 'like', '%không dây%');
    }
}
