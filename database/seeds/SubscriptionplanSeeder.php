<?php

use Illuminate\Database\Seeder;
use Contus\Customer\Models\SubscriptionPlan;
use Carbon\Carbon;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('subscription_plans')->count();
        if($existCount <= 0) {
            DB::unprepared("ALTER TABLE subscription_plans AUTO_INCREMENT = 1;");
            SubscriptionPlan::insert([
                    [
                        'name' => 'Gold',
                        'type' => 'month',
                        'slug' => 'gold',
                        'amount' => '20.00',
                        'description' => 'Test',
                        'duration' => '30',
                        'is_active' => 1,
                        'creator_id' => 1,
                        'updator_id' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'name' => 'Platinum',
                        'type' => 'year',
                        'slug' => 'platinum',
                        'amount' => '50.00',
                        'description' => 'Test',
                        'duration' => '365',
                        'is_active' => 1,
                        'creator_id' => 1,
                        'updator_id' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'name' => 'Silver',
                        'type' => 'week',
                        'slug' => 'silver',
                        'amount' => '10.00',
                        'description' => 'Test',
                        'duration' => '7',
                        'is_active' => 1,
                        'creator_id' => 1,
                        'updator_id' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]
                ]
            );
        }
    }
}
