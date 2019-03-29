<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTypeSeeder::class,
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            LanguageTableSeeder::class,
            TypeOfOrganizationTableSeeder::class,
            ServicePackageTypeSeeder::class,
            SeedVatCountries::class,
        ]);
    }
}
