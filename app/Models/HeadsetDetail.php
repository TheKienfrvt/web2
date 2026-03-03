<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadsetDetail extends Model
{
    use HasFactory;

    protected $table = 'headset_detail';
    protected $primaryKey = 'headset_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'thuong_hieu',
        'micro',
        'trong_luong',
        'pin',
        'ket_noi',
        'description'
    ];

    protected $casts = [
        'micro' => 'string', // Hoặc có thể tạo custom cast nếu cần
    ];

    /**
     * Relationship với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Scope để lấy headset có micro
     */
    public function scopeCoMicro($query)
    {
        return $query->where('micro', 'có');
    }

    /**
     * Scope để lấy headset không micro
     */
    public function scopeKhongMicro($query)
    {
        return $query->where('micro', 'không');
    }

    /**
     * Lấy attributes cho filter
     */
    public static function getFilterAttributes()
    {
        return [
            'thuong_hieu' => 'Thương hiệu',
            'micro' => 'Micro',
            'ket_noi' => 'Kết nối',
            'pin' => 'Pin'
        ];
    }

    public static function getDetailAttributes()
    {
         return [
            'thuong_hieu' => 'Thương hiệu',
            'micro' => 'Micro',
            'ket_noi' => 'Kết nối',
            'pin' => 'Pin'
        ];
    }

    /**
     * Accessor để hiển thị micro đẹp hơn
     */
    public function getMicroDisplayAttribute()
    {
        return $this->micro === 'có' ? 'Có micro' : 'Không micro';
    }
}
