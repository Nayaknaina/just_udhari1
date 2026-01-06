<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebPage;
use Illuminate\Support\Str;

class WebPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $pages = [

            [
            'title' => 'Terms and Conditions',
            'url' => 'terms-and-conditions',
            ] ,
            [
            'title' => 'Privacy Policy',
            'url' => 'privacy-policy',
            ] ,
            [
            'title' => 'Cancellation/ Refund Policy',
            'url' => 'terms-and-conditions#cancellation-refund-policy',
            ] ,

        ] ;

        foreach($pages as $page) {

            WebPage::create([

                'title' => $page['title'] ,
                'url' => $page['url'] ,

            ]) ;

        }
    }
}
