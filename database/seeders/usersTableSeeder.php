<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class usersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        DB::table("users")->insert([
            [
                "username" => "teste@gmail.com",
                //password_hash("Teste10*", PASSWORD_DEFAULT)
                "password" => bcrypt("Teste10*"),

            ],

            [
                "username" => "teste10",
                //password_hash("Teste20*", PASSWORD_DEFAULT)
                "password" => bcrypt("Teste20*"),

            ],
        ]);
    }
}
