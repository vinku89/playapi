<?php

use Illuminate\Database\Seeder;
use Contus\Payment\Models\PaymentMethod;
use Carbon\Carbon;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('payment_methods')->count();
        if($existCount <= 0) {
            DB::unprepared("ALTER TABLE payment_methods AUTO_INCREMENT = 1;");
            PaymentMethod::insert([
                    [
                        'name' => 'Self',
                        'type' => 'Self',
                        'slug' => 'self',
                        'description' => 'Test payment Method',
                        'is_test' => 1,
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
