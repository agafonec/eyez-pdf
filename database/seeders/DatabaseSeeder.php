<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $role = Role::create(['name' => 'admin']);
        $createUsers = Permission::create(['name' => 'create_users']);
        $updateOpretail = Permission::create(['name' => 'update_opretail']);

        $role->givePermissionTo($createUsers);
        $role->givePermissionTo($updateOpretail);

        $user = User::whereEmail('dev@astraverdes.com')->first() ?? User::factory([
            'email' => 'dev@astraverdes.com',
            'name' => 'Super Admin',
            'password' => bcrypt('admin112233'),
        ])->create();

        $user->assignRole('admin');
    }
}
