<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::insert([
            [
                "name"=>"Mr.Admin",
                "email"=>"admin@admin.com",
                "password"=>Hash::make("password"),
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Mr.Editor",
                "email"=>"editor@editor.com",
                "password"=>Hash::make("password"),
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Mr.User",
                "email"=>"user@user.com",
                "password"=>Hash::make("password"),
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
