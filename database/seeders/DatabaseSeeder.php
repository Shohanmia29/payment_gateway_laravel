<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HigherOrderTapProxy;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $user = tap(
            User::create([
                'name' => 'User',
                'phone' => '01700000000',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user@gmail.com')
            ])
        )->markEmailAsVerified();

        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@gmail.com')
        ]);

        $this->call(LaratrustSeeder::class);

        $user->attachRole(Role::whereName('user')->first());
        $admin->attachRole(Role::whereName('admin')->first());
    }
}
