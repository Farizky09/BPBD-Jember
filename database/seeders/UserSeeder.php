<?php

namespace Database\Seeders;

use App\Models\User;
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
        $superAdmin = User::create([
            'name'     => 'Super Admin',
            'username' => 'superadmin',
            'email'    => 'superadmin@gmail.com',
            'password' => Hash::make('superadmin'),
            'phone_number' => '081234567875',
            'poin' => 100
        ]);
        $superAdmin2 = User::create([
            'name'     => 'Super Admin 2',
            'username' => 'superadmin 2',
            'email'    => 'superadmin2@gmail.com',
            'password' => Hash::make('superadmin2'),
            'phone_number' => '081234567875',
            'poin' => 100
        ]);



        $admin = User::create([
            'name'     => 'Admin',
            'username' => 'admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'phone_number' => '08123456765',
            'poin' => 100

        ]);

        $farizky = User::create([
            'name'     => 'Ahmad Farizky',
            'username' => 'Farizky',
            'email'    => 'farizky@gmail.com',
            'password' => Hash::make('E41231223'),
            'phone_number' => '08123456761',
            'poin' => 100
        ]);

        $arifin = User::create([
            'name'     => 'Moch. Arifin Hidayatullah',
            'username' => 'Arifin',
            'email'    => 'arifin@gmail.com',
            'password' => Hash::make('E41231218'),
            'phone_number' => '08123456762',
            'poin' => 100
        ]);

        $saka = User::create([
            'name'     => 'Saka Karna Bramasta',
            'username' => 'Saka',
            'email'    => 'saka@gmail.com',
            'password' => Hash::make('E41231073'),
            'phone_number' => '08123456763',
            'poin' => 100
        ]);
        $dymas = User::create([
            'name'     => 'Dymas Ersa Ramadhan',
            'username' => 'Dymas',
            'email'    => 'dymas@gmail.com',
            'password' => Hash::make('E41231177'),
            'phone_number' => '08123456763',
            'poin' => 100
        ]);
        $dara = User::create([
            'name'     => 'Dara Novia Ananta Putri',
            'username' => 'Dara',
            'email'    => 'dara@gmail.com',
            'password' => Hash::make('E41231117'),
            'phone_number' => '08123456763',
            'poin' => 100
        ]);
        $user = User::create([
            'name'     => 'User',
            'username' => 'user123',
            'email'    => 'user123@gmail.com',
            'password' => Hash::make('1'),
            'phone_number' => '08123456763',
            'poin' => 100
        ]);

        $superAdmin->assignRole(User::SUPER_ADMIN);
        $superAdmin2->assignRole(User::SUPER_ADMIN);
        $admin->assignRole(User::ADMIN);
        $farizky->assignRole(User::USER);
        $arifin->assignRole(User::USER);
        $saka->assignRole(User::USER);
        $dymas->assignRole(User::USER);
        $dara->assignRole(User::USER);
        $user->assignRole(User::USER);
    }
}
