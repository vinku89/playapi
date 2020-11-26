<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplateTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('email_template_id');
            $table->integer('language_id');
            $table->string('name', 255);
            $table->text('subject');
            $table->longText('content');
            $table->timestamps();
        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_templates_translation');
    }
}
