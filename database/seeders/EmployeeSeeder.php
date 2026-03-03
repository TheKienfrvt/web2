<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu tồn tại
        DB::table('employee')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE employee AUTO_INCREMENT = 1');

        $employees = [
            [
                'full_name' => 'Huỳnh Trần Nhật Nguyên',
                'email' => 'nhatnguyen@gmail.com',
                'password' => Hash::make('nhatnguyen'),
                'phone_number' => '0963944370',
                'position' => 'Quản lý',
                'department' => 'Ban Quản lý',
                'hire_date' => '2024-01-01',
                'manager_id' => null,
                'status' => 'Active',
                'created_at' => "2024-01-01 11:11:11",
                'updated_at' => "2024-01-01 11:11:11",
            ],
            [
                'full_name' => 'Lê Văn Nhân viên',
                'email' => 'nv1@gmail.com',
                'password' => Hash::make('nv1'),
                'phone_number' => '0903333333',
                'position' => 'Nhân viên Kinh doanh',
                'department' => 'Phòng Kinh doanh',
                'hire_date' => '2024-02-01',
                'manager_id' => 1, // Quản lý bởi Huỳnh Trần Nhật Nguyên
                'status' => 'Active',
                'created_at' => "2024-02-01 12:12:12",
                'updated_at' => "2024-02-01 12:12:12",
            ],
            [
                'full_name' => 'Phạm Gia Bảo',
                'email' => 'nv2@gmail.com',
                'password' => Hash::make('nv2'),
                'phone_number' => '0902343247',
                'position' => 'Nhân viên Kinh doanh',
                'department' => 'Phòng Kinh doanh',
                'hire_date' => '2024-02-01',
                'manager_id' => 1, // Quản lý bởi Huỳnh Trần Nhật Nguyên
                'status' => 'Active',
                'created_at' => "2024-02-01 12:12:12",
                'updated_at' => "2024-02-01 12:12:12",
            ],
            [
                'full_name' => 'Khải Hòa',
                'email' => 'khaihoa@gmail.com',
                'password' => Hash::make('KhaiHoaDepTrai'),
                'phone_number' => '0902343999',
                'position' => 'Nhân viên Kinh doanh',
                'department' => 'Phòng Kinh doanh',
                'hire_date' => '2024-02-01',
                'manager_id' => 1, // Quản lý bởi Huỳnh Trần Nhật Nguyên
                'status' => 'Active',
                'created_at' => "2024-02-01 12:12:12",
                'updated_at' => "2024-02-01 12:12:12",
            ],
            [
                'full_name' => 'Thùy Vân',
                'email' => 'thuyvan2000@gmail.com',
                'password' => Hash::make('thuyvan'),
                'phone_number' => '0902876534',
                'position' => 'Nhân viên Kinh doanh',
                'department' => 'Phòng Kinh doanh',
                'hire_date' => '2024-02-01',
                'manager_id' => 1, // Quản lý bởi Huỳnh Trần Nhật Nguyên
                'status' => 'Active',
                'created_at' => "2024-02-01 12:12:12",
                'updated_at' => "2024-02-01 12:12:12",
            ],
            [
                'full_name' => 'Lê Thành Hoa',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'phone_number' => '0908767723',
                'position' => 'Quản lý',
                'department' => 'Ban Quản lý',
                'hire_date' => '2024-02-01',
                'manager_id' => null, // Quản lý bởi Huỳnh Trần Nhật Nguyên
                'status' => 'Active',
                'created_at' => "2024-02-01 12:12:12",
                'updated_at' => "2024-02-01 12:12:12",
            ]
        ];

        DB::table('employee')->insert($employees);

        $this->command->info('✓ Đã thêm ' . count($employees) . ' nhân viên mẫu.');
    }
}
