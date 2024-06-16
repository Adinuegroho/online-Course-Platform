<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ownerRole = Role::create([
            'name' => 'owner'
        ]);
        $studentRole = Role::create([
            'name' => 'student'
        ]);
        $teacherRole = Role::create([
            'name' => 'teacher'
        ]);

        // akun super admin untuk mengelola data awal
        // data kategori, kelas, dst
        $userOwner = User::create([
            'name' => 'bagus adi',
            'occupation' => 'Educator',
            'avatar' => 'images/default-avatar.png',
            'email' => 'adi@owner.com',
            'password' => bcrypt('12345678'),
        ]);

        $userOwner->assignRole($ownerRole);
    }
}
