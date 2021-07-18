<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Role::insert([
            [
                "name"=>"Admin",
                "slug"=>Str::slug("Admin"),
            ],
            [
                "name"=>"Editor",
                "slug"=>Str::slug("Editor"),
            ],
            [
                "name"=>"User",
                "slug"=>Str::slug("User"),
            ],
        ]);
    }
}
