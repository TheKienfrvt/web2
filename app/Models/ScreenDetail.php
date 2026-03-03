<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenDetail extends Model
{
    use HasFactory;

    protected $table = 'screen_detail';
    protected $primaryKey = 'screen_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'thuong_hieu',
        'kich_thuoc_man_hinh',
        'tang_so_quet',
        'ti_le',
        'tam_nen',
        'do_phan_giai',
        'khoi_luong',
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
            'kich_thuoc_man_hinh' => 'Kích thước màn hình',
            'tang_so_quet' => 'Tần số quét',
            'do_phan_giai' => 'Độ phân giải',
            'tam_nen' => 'Tấm nền'
        ];
    }

    public static function getDetailAttributes()
    {
        return [
            'thuong_hieu' => 'Thương hiệu',
            'kich_thuoc_man_hinh' => 'Kích thước màn hình',
            'tang_so_quet' => 'Tần số quét',
            'do_phan_giai' => 'Độ phân giải',
            'tam_nen' => 'Tấm nền'
        ];
    }

    public static function getFilterValues($attribute)
    {
        return self::select($attribute)->distinct()->pluck($attribute);
    }
}
