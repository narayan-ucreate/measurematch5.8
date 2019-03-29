<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $langname = 'English';

        $checklanguage = DB::table('languages')->where('name', 'iLIKE', trim($langname))->get();

        if (empty($checklanguage)) {
            DB::table('languages')->insert([
                ['name' => $langname, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ]);
        }
    }

}
