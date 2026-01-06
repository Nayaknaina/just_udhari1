<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebInformation;

class WebInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        WebInformation::create([

            'name' => 'Just Udhari',
            'logo' => 'assets/images/logo.png',
            'mobile_no' => '9876543211',
            'whatsapp_no' => '9876543210',
            'email' => 'info@justudhari.com',
            'address' => '123 Street, City, Country', 

        ]);

    }
}
