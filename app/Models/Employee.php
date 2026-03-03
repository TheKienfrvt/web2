<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * Tên bảng trong database
     */
    protected $table = 'employee';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'employee_id';

    /**
     * Khóa chính tự tăng
     */
    public $incrementing = true;

    /**
     * Kiểu khóa chính
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'position',
        'department',
        'hire_date',
        'status',
        'manager_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Scope để lấy user active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Mối quan hệ với quản lý
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'employee_id');
    }
    
    /**
     * Mối quan hệ với nhân viên dưới quyền
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id', 'employee_id');
    }
}
