<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * Tên bảng trong database
     */
    protected $table = 'user';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'user_id';

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
        'username',
        'password',
        'email',
        'sex',
        'phone_number',
        'dob',
        'status',
        'avatar_url'
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
            'dob' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship với Address
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    /**
     * Relationship với Cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    /**
     * Relationship với Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Scope để lấy user active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'mở');
    }
}
