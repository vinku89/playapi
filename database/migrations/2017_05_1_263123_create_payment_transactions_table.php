<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->bigInteger('payment_method_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->string('status', 50);
            $table->text('transaction_message');
            $table->string('transaction_id', 255);
            $table->bigInteger('subscription_plan_id')->nullable();
            $table->bigInteger('subscriber_id')->nullable();
            $table->string('plan_name')->nullable();
            $table->text('response');
            $table->bigInteger('creator_id')->default(0);
            $table->bigInteger('updator_id')->default(0);
            $table->timestamps();

            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_transactions');
    }
}
