<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public static $categoryMapping = [
        'Laptop' => [
            'model' => LaptopDetail::class,
            'type' => 'Laptop'
        ],
        'Screen' => [
            'model' => ScreenDetail::class,
            'type' => 'Screen'
        ],
        'LaptopGaming' => [
            'model' => LaptopGamingDetail::class,
            'type' => 'LaptopGaming'
        ],
        'GPU' => [
            'model' => GpuDetail::class,
            'type' => 'GPU'
        ],
        'Headset' => [
            'model' => HeadsetDetail::class,
            'type' => 'Headset'
        ],
        'Mouse' => [
            'model' => MouseDetail::class,
            'type' => 'Mouse'
        ],
        'Keyboard' => [
            'model' => KeyboardDetail::class,
            'type' => 'Keyboard'
        ]
        // Thêm các category khác tại đây
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name',
        'category_id',
        'stock',
        'price',
        'status',
        'image_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'stock' => 'integer',
        'price' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // Add any fields you want to hide from JSON responses
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'stock' => 0,
        'status' => 'hiện',
    ];

    /**
     * Get the category that owns the product.
     */
    // Lấy thông tin category của sản phẩm
    // $product = Product::find(1);
    // $category = $product->category;
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function laptopDetail()
    {
        return $this->hasOne(LaptopDetail::class, 'product_id');
    }

    public function laptopGamingDetail()
    {
        return $this->hasOne(LaptopGamingDetail::class, 'product_id');
    }

    public function screenDetail()
    {
        return $this->hasOne(ScreenDetail::class, 'product_id');
    }

    public function gpuDetail()
    {
        return $this->hasOne(GpuDetail::class, 'product_id');
    }

    public function headsetDetail()
    {
        return $this->hasOne(HeadsetDetail::class, 'product_id');
    }

    public function mouseDetail()
    {
        return $this->hasOne(MouseDetail::class, 'product_id');
    }

    public function keyboardDetail()
    {
        return $this->hasOne(KeyboardDetail::class, 'product_id');
    }

    /**
     * Scope a query to only include active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public static function getRelationName($categoryId)
    {
        $relations = [
            'Laptop' => 'laptopDetail',
            'Screen' => 'screenDetail',
            'LaptopGaming' => 'laptopGamingDetail',
            'GPU' => 'gpuDetail',
            'Headset' => 'headsetDetail',
            'Mouse' => 'mouseDetail',
            'Keyboard' => 'keyboardDetail'
        ];

        return $relations[$categoryId] ?? null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'hiện');
    }

    // Thêm các methods hỗ trợ trong Product model
    public function getFilterAttribute()
    {
        $categoryMapping = [
            'Laptop' => LaptopDetail::getFilterAttributes(),
            'LaptopGaming' => LaptopGamingDetail::getFilterAttributes(),
            'Screen' => ScreenDetail::getFilterAttributes(),
            'GPU' => GpuDetail::getFilterAttributes(),
            'Headset' => HeadsetDetail::getFilterAttributes(),
            'Mouse' => MouseDetail::getFilterAttributes(),
            'Keyboard' => KeyboardDetail::getFilterAttributes(),
        ];

        return $categoryMapping[$this->category_id] ?? [];
    }


    public function getDetailAttribute()
    {
        $mapping = [
            'Laptop' => 'laptopDetail',
            'LaptopGaming' => 'laptopGamingDetail',
            'Screen' => 'screenDetail',
            'GPU' => 'gpuDetail',
            'Headset' => 'headsetDetail',
            'Mouse' => 'mouseDetail',
            'Keyboard' => 'keyboardDetail'
        ];

        // mapping quan hệ
        // $relation = laptopDetail/laptopGamingDetail/...
        $relation = $mapping[$this->category_id] ?? null;

        // trả về quan hệ
        // $this->$relation = $this->laptopDetail/laptopGamingDetail/...
        return $relation ? $this->$relation : null;
    }
}
