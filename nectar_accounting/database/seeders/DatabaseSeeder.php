<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use App\Models\Setting;
use App\Models\SubAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UserPermissionTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
        $this->call(RolesPermissionsTableSeeder::class);
        $this->call(ProvinceTableSeeder::class);
        $this->call(DistrictTableSeeder::class);

        // Main Account Seeder
        Account::insert([
            [
                "title"=>"Assets",
                "slug"=>Str::slug("Assets"),
            ],
            [
                "title"=>"Liabilities",
                "slug"=>Str::slug("Liabilities"),
            ]
        ]);

        Setting::insert([
            [
                "company_name"=>"Nectar Accounting",
                "company_email"=>"nectar@gmail.com ",
                "company_phone"=>"01-42657548",
                "province_id"=>"3",
                "district_id"=>"23",
                "address"=>"Chamati, Banasthali",
                "logo"=>"noimage.jpg",
            ]
        ]);


         // Sub Account Seeder
         SubAccount::insert([
            [
                "account_id" => "1",
                "title"=>"Current Assets",
                "slug"=>Str::slug("Current Assets"),
            ],
            [
                "account_id" => "2",
                "title"=>"Account Payable",
                "slug"=>Str::slug("Account Payable"),
            ]
        ]);

        // Child Account Seeder
        ChildAccount::insert([
            [
                "sub_account_id" => "1",
                "title"=>"Cash in hand",
                "slug"=>Str::slug("Cash in hand"),
            ],
            [
                "sub_account_id" => "2",
                "title"=>"Payable to Ram",
                "slug"=>Str::slug("Payable to Ram"),
            ]
        ]);

        FiscalYear::insert([
            [
                "fiscal_year" => "2077/2078"
            ],
        ]);

    }
}
