<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpuDetail extends Model
{
    use HasFactory;

    protected $table = 'gpu_detail';
    protected $primaryKey = 'gpu_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'thuong_hieu',
        'gpu',
        'cuda',
        'toc_do_bo_nho',
        'bo_nho',
        'nguon',
        'description'
    ];

    protected $casts = [
        // Có thể thêm casts nếu cần
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
            'gpu' => 'GPU',
            'cuda' => 'Số nhân CUDA',
            'bo_nho' => 'Bộ nhớ',
            'nguon' => 'Nguồn yêu cầu'
        ];
    }

    public static function getDetailAttributes()
    {
        return [
            'thuong_hieu' => 'Thương hiệu',
            'gpu' => 'GPU',
            'cuda' => 'Số nhân CUDA',
            'bo_nho' => 'Bộ nhớ',
            'nguon' => 'Nguồn yêu cầu'
        ];
    }
}
