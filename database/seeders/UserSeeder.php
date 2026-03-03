<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'user_id' => 1,
                'username' => 'nhat nguyen',
                'password' => Hash::make('password'),
                'email' => 'password@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0963944370',
                'dob' => '2005-04-27',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 2,
                'username' => 'khach hang 2',
                'password' => Hash::make('khachhang2'),
                'email' => 'khachhang2@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0123456789',
                'dob' => '2005-05-15',
                'status' => 'mở',
                'created_at' => '2024-09-5 12:34:21',
                'updated_at' => '2024-09-5 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 3,
                'username' => 'thế kiên',
                'password' => Hash::make('password'),
                'email' => 'thekien@gmail.com',
                'sex' => 'nữ',
                'phone_number' => '0998877665',
                'dob' => '2005-10-30',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 4,
                'username' => 'user1',
                'password' => Hash::make('password'),
                'email' => 'user1@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0998877001',
                'dob' => '2005-01-01',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 5,
                'username' => 'user2',
                'password' => Hash::make('password'),
                'email' => 'user2@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0998877002',
                'dob' => '2005-02-02',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 6,
                'username' => 'user3',
                'password' => Hash::make('password'),
                'email' => 'user3@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0998877003',
                'dob' => '2005-03-03',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 7,
                'username' => 'user4',
                'password' => Hash::make('password'),
                'email' => 'user4@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0333877003',
                'dob' => '1994-09-30',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 8,
                'username' => 'user5',
                'password' => Hash::make('password'),
                'email' => 'user5@gmail.com',
                'sex' => 'nữ',
                'phone_number' => '0987567003',
                'dob' => '2005-03-03',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''

            ],
            [
                'user_id' => 9,
                'username' => 'user6',
                'password' => Hash::make('password'),
                'email' => 'user6@gmail.com',
                'sex' => 'nữ',
                'phone_number' => '0998877003',
                'dob' => '1999-06-22',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 10,
                'username' => 'user7',
                'password' => Hash::make('password'),
                'email' => 'user7@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0998877003',
                'dob' => '2000-05-19',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''

            ],
            [
                'user_id' => 11,
                'username' => 'user8',
                'password' => Hash::make('password'),
                'email' => 'user8@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0998865403',
                'dob' => '2005-03-03',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 12,
                'username' => 'user9',
                'password' => Hash::make('password'),
                'email' => 'user9@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0992225403',
                'dob' => '2005-01-11',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 13,
                'username' => 'user10',
                'password' => Hash::make('password'),
                'email' => 'user10@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0998095403',
                'dob' => '2005-11-18',
                'status' => 'mở',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 14,
                'username' => 'user11',
                'password' => Hash::make('password'),
                'email' => 'user11@gmail.com',
                'sex' => 'nữ',
                'phone_number' => '0998865403',
                'dob' => '2002-05-10',
                'status' => 'khóa',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ],
            [
                'user_id' => 15,
                'username' => 'user12',
                'password' => Hash::make('password'),
                'email' => 'user12@gmail.com',
                'sex' => 'nam',
                'phone_number' => '0845865403',
                'dob' => '2001-10-10',
                'status' => 'đã xóa',
                'created_at' => '2024-09-10 12:34:21',
                'updated_at' => '2024-09-10 12:34:21',
                'avatar_url' => ''
            ]
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }

        $this->command->info('✓ Đã thêm ' . count($users) . ' khách hàng mẫu.');
    }
}
