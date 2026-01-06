<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str ;

class CategorySeeder extends Seeder {

    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        
        $categories_2 = [
            
            [ 'name'=>'Bridal Collection',
               'category_level' => '2'
            ] , [ 'name'=>'Everyday Wear ',
               'category_level' => '2'
            ] ,[ 'name'=>'Festive Collection',
               'category_level' => '2'
            ] ,[ 'name'=>'Office Wear',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Gifts & Anniversary',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Party & Evening Wear',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Traditional',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Contemporary',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Minimalist',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Vintage',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Bohemian',
               'category_level' => '2'
            ] ,
            [ 'name'=>'Luxury',
               'category_level' => '2'
            ] ,

        ] ; 

        foreach($categories_2 as $cat2) {

            $slug = Str::slug($cat2['name'], '-');
            Category::create(['name'=>$cat2['name'] , 'category_level' =>2 , 'slug' =>$slug ]);

        }

        $categories_3 = [

            ["name" => "Chains"],
            ["name" => "Necklaces"],
            ["name" => "Rings"],
            ["name" => "Bracelets"],
            ["name" => "Earrings"],
            ["name" => "Pendants"],
            ["name" => "Anklets"],
            ["name" => "Necklace and Earring Sets"] ,
            ["name" => "Bracelet and Ring Sets"] , 
            ["name" => "Mangalsutra"] , 
            ["name" => "Bangle"] , 
            ["name" => "Watches"] ,
            ["name" => "Nosepins"] ,

        ];

        foreach($categories_3 as $cat3) {

            $slug = Str::slug($cat3['name'], '-');
            Category::create(['name'=>$cat3['name'] , 'category_level' =>3 , 'slug' =>$slug ]);

        }


    }

}
