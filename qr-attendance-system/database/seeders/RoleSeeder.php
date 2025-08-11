<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full system administrator with all permissions'
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Course administrator with limited permissions'
            ],
            [
                'name' => 'user',
                'display_name' => 'Student',
                'description' => 'Regular student user'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
