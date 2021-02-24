<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'supri@gmail.com',
            'name' => 'Supri',
            'phone' => '086512312312',
            'password' => \Hash::make('123258'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);
    }
}
