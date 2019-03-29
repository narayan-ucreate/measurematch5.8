<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // adds admin user
        // test
      DB::table('users')->insert([
            'name'=>'Admin','user_type_id'=>'3','id'=>'ffe8bd10-b0aa-11e6-9ca2-150b96e5df85','email'=> getenv('ADMIN_EMAIL'),'password'=> \Hash::make(getenv('ADMIN_PASSWORD')),'remember_token'=>'JjU00oprI7HvTMbFfeJPcgkPVtq5l1tSenaCqAUC0MSR7hHdWuua0rRjNqLa','mm_unique_num'=>'MMB0010000','status'=>1,'created_at'=>date('Y-m-d H:i:s'),'admin_approval_status'=>1,'verified_status'=>1,'updated_at'=>date('Y-m-d H:i:s')
        ]);
        
        DB::table('skills')->insert([
          ['name'=>'A/B','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Multivariate','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Data Governance/Integrity','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Reporting Tool Administration','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Tag Management','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Channel Optimization','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'R','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Python','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'SAS','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Tableau','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Google Analytics','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Unica','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Adobe','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Dashboard Creation','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Training','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'C','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'C++','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'JavaScript','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'HTML','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Database Administration','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Neteeza','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'SQL','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'APIs','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Business Requirements','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Process Improvement','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Strategy Definition','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Project Management','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Presentation','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Communications','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Team Building','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        ]);
        DB::table('remote_works')->insert([
          ['name'=>'Only work remotely','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Only work on site','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'Can work remotely and on site','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        ]);
    }
}
