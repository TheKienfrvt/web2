<?php

namespace App\Services;

use App\Models\GpuDetail;
use App\Models\HeadsetDetail;
use App\Models\KeyboardDetail;
use App\Models\LaptopDetail;
use App\Models\LaptopGamingDetail;
use App\Models\MouseDetail;
use App\Models\ScreenDetail;
use App\Models\Product;

class ProductFilterService
{
  /**
   * Mapping giữa category_id và loại sản phẩm
   * truyên vào categoryId để trả về model và type
   * model là model detail tương ứng của category
   * type là loại sản phẩm
   */
  private static $categoryMapping = [
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
   * Lấy cấu hình filters cho một category cụ thể
   */
  public static function getFiltersForCategory($categoryId)
  {
    $config = self::$categoryMapping[$categoryId] ?? null;

    if (!$config) {
      return ['']; // Trả về mảng rỗng nếu không có config
    }

    return self::generateFilters($config['model'], $config['type']);
  }

  /**
   * Tạo filters dựa trên model và loại sản phẩm
   */
  private static function generateFilters($modelClass, $productType)
  {
    $filters = [];
    // $filterAttributes = self::getFilterAttributes($productType);
    $filterAttributes = $modelClass::getFilterAttributes();


    foreach ($filterAttributes as $attribute => $label) {
      $values = self::getDistinctValues($modelClass, $attribute);

      if ($values->isNotEmpty()) {
        $filters[$attribute] = [
          'label' => $label,
          'values' => $values,
          'type' => self::getFilterType($attribute) // select, range, etc.
        ];
      }
    }

    return $filters;
  }

  /**
   * Lấy danh sách attributes cần filter cho từng loại sản phẩm
   */

  private static function getFilterAttributes($productType)
  {
    $attributes = [
      'Laptop' => [
        'thuong_hieu' => 'Thương hiệu',
        'cpu' => 'CPU',
        'gpu' => 'GPU',
        'ram' => 'RAM',
        'dung_luong' => 'Ổ cứng',
        'kich_thuoc_man_hinh' => 'Kích thước màn hình',
        'do_phan_giai' => 'Độ phân giải'
      ],
      'Screen' => [
        'thuong_hieu' => 'Thương hiệu',
        'kich_thuoc_man_hinh' => 'Kích thước màn hình',
        'tang_so_quet' => 'Tần số quét',
        'do_phan_giai' => 'Độ phân giải',
        'tam_nen' => 'Tấm nền'
      ],
      'LaptopGaming' => [
        'thuong_hieu' => 'Thương hiệu',
        'cpu' => 'CPU',
        'gpu' => 'GPU',
        'ram' => 'RAM',
        'dung_luong' => 'Ổ cứng',
        'kich_thuoc_man_hinh' => 'Kích thước màn hình',
        'do_phan_giai' => 'Độ phân giải'
      ]
    ];

    return $attributes[$productType] ?? [];
  }

  /**
   * Lấy giá trị distinct từ database
   */
  private static function getDistinctValues($modelClass, $attribute)
  {
    return $modelClass::select($attribute)
      ->distinct()
      ->orderBy($attribute)
      ->pluck($attribute)
      ->filter(); // Lọc bỏ giá trị null/rỗng
  }

  /**
   * Xác định loại filter (select, range, checkbox)
   */
  private static function getFilterType($attribute)
  {
    $rangeAttributes = ['price', 'khoi_luong', 'tang_so_quet'];

    if (in_array($attribute, $rangeAttributes)) {
      return 'range';
    }

    return 'select'; // Mặc định là dropdown select
  }

  /**
   * Áp dụng filters vào query
   */
  public static function applyFilters($query, $request, $categoryId)
  {
    $query->where('category_id', $categoryId);

    $filters = self::getFiltersForCategory($categoryId);

    $config = self::$categoryMapping[$categoryId] ?? null;

    if (!$config) {
      return $query;
    }

    foreach ($filters as $attribute => $filter) {
      if ($request->filled($attribute)) {
        $value = $request->input($attribute);

        // Áp dụng filter vào query
        $query->whereHas(
          self::getRelationName($config['type']),
          function ($q) use ($attribute, $value) {
            $q->where($attribute, $value);
          }
        );
      }
    }

    return $query;
  }

  /**
   * Lấy tên relationship dựa trên loại sản phẩm
   */
  private static function getRelationName($productType)
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

    return $relations[$productType] ?? null;
  }

  /**
   * Lấy giá trị min/max cho range filters
   */
  public static function getRangeValues($categoryId, $attribute)
  {
    $config = self::$categoryMapping[$categoryId] ?? null;

    if (!$config) {
      return ['min' => 0, 'max' => 100];
    }

    $modelClass = $config['model'];

    return [
      'min' => $modelClass::min($attribute) ?? 0,
      'max' => $modelClass::max($attribute) ?? 100
    ];
  }
}
