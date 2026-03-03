<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaptopGamingDetail extends Model
{
    use HasFactory;

    protected $table = 'laptop_gaming_detail';
    protected $primaryKey = 'laptop_gaming_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'thuong_hieu',
        'cpu',
        'gpu',
        'ram',
        'dung_luong',
        'kich_thuoc_man_hinh',
        'do_phan_giai',
        'description'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function getFilterAttributes()
    {
        return [
            'thuong_hieu' => 'Thương hiệu',
            'cpu' => 'CPU',
            'gpu' => 'GPU',
            'ram' => 'RAM',
            'dung_luong' => 'Ổ cứng',
            'kich_thuoc_man_hinh' => 'Kích thước màn hình',
            'do_phan_giai' => 'Độ phân giải'
        ];
    }

    public static function getDetailAttributes()
    {
        return [
            'thuong_hieu' => 'Thương hiệu',
            'cpu' => 'CPU',
            'gpu' => 'GPU',
            'ram' => 'RAM',
            'dung_luong' => 'Ổ cứng',
            'kich_thuoc_man_hinh' => 'Kích thước màn hình',
            'do_phan_giai' => 'Độ phân giải'
        ];
    }
    public static function getFilterValues($attribute)
    {
        return self::select($attribute)->distinct()->pluck($attribute);
    }
}
