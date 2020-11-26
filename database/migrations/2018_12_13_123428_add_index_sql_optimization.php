<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexSqlOptimization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function(Blueprint $table){
            $table->index('is_active');
        });

        Schema::table('categories', function(Blueprint $table){
            $table->index('slug');
            $table->index('is_active');    
        });

        Schema::table('collections', function(Blueprint $table){
            $table->index('slug');
            $table->index('is_active');        
        });

        Schema::table('collections_videos', function(Blueprint $table){
            $table->index('video_id');
            $table->index('group_id');
            $table->index('parent_cateogry_id');
            $table->index('is_active');        
        });

        Schema::table('videos', function(Blueprint $table){
            $table->index('slug');
            $table->index('is_active');
            $table->index('job_status');
            $table->index('is_archived');
            $table->index('is_live');        
        });

        Schema::table('video_categories', function(Blueprint $table){
            $table->index('video_id');
            $table->index('category_id');  
        });

        Schema::table('email_templates', function(Blueprint $table){
           $table->index('slug');
           $table->index('is_active');       
        });

        Schema::table('site_languages', function(Blueprint $table){
            $table->index('code');
            $table->index('is_active');   
        });

        Schema::table('subscription_plans', function(Blueprint $table){
            $table->index('is_active');  
        });

        Schema::table('subscribers', function(Blueprint $table){
            $table->index('subscription_plan_id');
            $table->index('customer_id');
            $table->index('is_active');        
        });

        Schema::table('recently_viewed_videos', function(Blueprint $table){
            $table->index('video_id');
            $table->index('customer_id'); 
        });

        Schema::table('favourite_videos', function(Blueprint $table){
            $table->index('customer_id');
            $table->index('video_id');   
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function(Blueprint $table){
            $table->dropIndex('is_active');
        });

        Schema::table('email_templates', function(Blueprint $table){
           $table->dropIndex('slug');
           $table->dropIndex('is_active');       
        });

        Schema::table('site_languages', function(Blueprint $table){
            $table->dropIndex('code');
            $table->dropIndex('is_active');   
        });

        Schema::table('subscription_plans', function(Blueprint $table){
            $table->dropIndex('is_active');  
        });

        Schema::table('subscribers', function(Blueprint $table){
            $table->dropIndex('subscription_plan_id');
            $table->dropIndex('customer_id');
            $table->dropIndex('is_active');        
        });

        Schema::table('recently_viewed_videos', function(Blueprint $table){
            $table->dropIndex('video_id');
            $table->dropIndex('customer_id'); 
        });

        Schema::table('favourite_videos', function(Blueprint $table){
            $table->dropIndex('customer_id');
            $table->dropIndex('video_id');   
        });

        Schema::table('categories', function(Blueprint $table){
            $table->dropIndex('slug');
            $table->dropIndex('is_active');    
        });

        Schema::table('collections', function(Blueprint $table){
            $table->dropIndex('slug');
            $table->dropIndex('is_active');        
        });

        Schema::table('collections_videos', function(Blueprint $table){
            $table->dropIndex('video_id');
            $table->dropIndex('group_id');
            $table->dropIndex('parent_cateogry_id');
            $table->dropIndex('is_active');        
        });

        Schema::table('videos', function(Blueprint $table){
            $table->dropIndex('slug');
            $table->dropIndex('is_active');
            $table->dropIndex('job_status');
            $table->dropIndex('is_archived');
            $table->dropIndex('is_live');        
        });

        Schema::table('video_categories', function(Blueprint $table){
            $table->dropIndex('video_id');
            $table->dropIndex('category_id');  
        });
       
    }
}
