<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('posts')->insert([

            ['title' => 'post 1' , 'user_id' => 1 , 'content' => 'post 1 content'],
            ['title' => 'post 2' , 'user_id' => 1 , 'content' => 'post 2 content'],
            ['title' => 'post 3' , 'user_id' => 1 , 'content' => 'post 3 content'],
        ]);
    }
}
