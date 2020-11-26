<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Contus\User\Models\User;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $existCount = DB::table('users')->count();
        if($existCount <= 0) {
            DB::unprepared("ALTER TABLE users AUTO_INCREMENT = 1;");
            User::insert([
                        [
                            'name' => 'Admin',
                            'email' => 'vplay@contus.in',
                            'password' => bcrypt('admin123'),
                            'parent_id' => 0,
                            'user_group_id' => 1,
                            'is_active' => 1,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ],
                    ]
                );
        }
    }
}
