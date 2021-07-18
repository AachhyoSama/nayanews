<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::insert([
            [
                "column_name"=>"user",
                "name"=>"View User",
                "slug"=>Str::slug("View User"),
            ],
            [
                "column_name"=>"user",
                "name"=>"Create User",
                "slug"=>Str::slug("Create User"),
            ],
            [
                "column_name"=>"user",
                "name"=>"Edit User",
                "slug"=>Str::slug("Edit User"),
            ],
            [
                "column_name"=>"user",
                "name"=>"Remove User",
                "slug"=>Str::slug("Remove User"),
            ],
            [
                "column_name"=>"role",
                "name"=>"View Role",
                "slug"=>Str::slug("View Role"),
            ],
            [
                "column_name"=>"role",
                "name"=>"Create Role",
                "slug"=>Str::slug("Create Role"),
            ],
            [
                "column_name"=>"role",
                "name"=>"Edit Role",
                "slug"=>Str::slug("Edit Role"),
            ],
            [
                "column_name"=>"role",
                "name"=>"Remove Role",
                "slug"=>Str::slug("Remove Role"),
            ],
            [
                "column_name"=>"permission",
                "name"=>"View Permission",
                "slug"=>Str::slug("View Permission"),
            ],
            [
                "column_name"=>"permission",
                "name"=>"Create Permission",
                "slug"=>Str::slug("Create Permission"),
            ],
            [
                "column_name"=>"permission",
                "name"=>"Edit Permission",
                "slug"=>Str::slug("Edit Permission"),
            ],
            [
                "column_name"=>"permission",
                "name"=>"Remove Permission",
                "slug"=>Str::slug("Remove Permission"),
            ],
            [
                "column_name"=>"journals",
                "name"=>"View journals",
                "slug"=>Str::slug("View journals"),
            ],
            [
                "column_name"=>"journals",
                "name"=>"Create journals",
                "slug"=>Str::slug("Create journals"),
            ],
            [
                "column_name"=>"journals",
                "name"=>"Edit journals",
                "slug"=>Str::slug("Edit journals"),
            ],
            [
                "column_name"=>"journals",
                "name"=>"Cancel journals",
                "slug"=>Str::slug("Cancel journals"),
            ],
            [
                "column_name"=>"journals",
                "name"=>"Approve journals",
                "slug"=>Str::slug("Approve journals"),
            ],
            [
                "column_name"=>"vendor",
                "name"=>"View Vendor",
                "slug"=>Str::slug("View Vendor"),
            ],
            [
                "column_name"=>"vendor",
                "name"=>"Create Vendor",
                "slug"=>Str::slug("Create Vendor"),
            ],
            [
                "column_name"=>"vendor",
                "name"=>"Edit Vendor",
                "slug"=>Str::slug("Edit Vendor"),
            ],
            [
                "column_name"=>"vendor",
                "name"=>"Remove Vendor",
                "slug"=>Str::slug("Remove Vendor"),
            ],
            [
                "column_name"=>"daily_expenses",
                "name"=>"View Daily_expenses",
                "slug"=>Str::slug("View Daily_expenses"),
            ],
            [
                "column_name"=>"daily_expenses",
                "name"=>"Create Daily_expenses",
                "slug"=>Str::slug("Create Daily_expenses"),
            ],
            [
                "column_name"=>"daily_expenses",
                "name"=>"Edit Daily_expenses",
                "slug"=>Str::slug("Edit Daily_expenses"),
            ],
            [
                "column_name"=>"daily_expenses",
                "name"=>"Remove Daily_expenses",
                "slug"=>Str::slug("Remove Daily_expenses"),
            ],
            [
                "column_name"=>"accounts",
                "name"=>"View Accounts",
                "slug"=>Str::slug("View Accounts"),
            ],
            [
                "column_name"=>"accounts",
                "name"=>"Create Accounts",
                "slug"=>Str::slug("Create Accounts"),
            ],
            [
                "column_name"=>"accounts",
                "name"=>"Edit Accounts",
                "slug"=>Str::slug("Edit Accounts"),
            ],
            [
                "column_name"=>"accounts",
                "name"=>"Remove Accounts",
                "slug"=>Str::slug("Remove Accounts"),
            ],
        ]);
    }
}
