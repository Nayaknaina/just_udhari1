<?php

// database/seeders/SuperAdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;

class SuperAdminSeeder extends Seeder {

    public function run() {

        SuperAdmin::create([

            'name' => 'Super Admin',
            'mobile_no' => '9876543210',
            'password' => Hash::make('Super@Admin123'),

        ]) ;

    }

}
