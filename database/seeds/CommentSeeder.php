<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('comments')->insert([

            ['user_id' => 2 , 'post_id' => 3 , 'content' => 'comment 1 on post 3 from user 2'],
            ['user_id' => 2 , 'post_id' => 2 , 'content' => 'comment 2 on post 2 from user 2'],
            ['user_id' => 2 , 'post_id' => 2 , 'content' => 'comment 3 on post 2 from user 2'],

        ]);

    }
}
