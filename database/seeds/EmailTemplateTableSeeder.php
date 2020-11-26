<?php

use Carbon\Carbon;
use Contus\Cms\Models\EmailTemplates;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('email_templates')->count();
        if($existCount <= 0) {
            DB::table('email_templates')->delete();
            DB::unprepared("ALTER TABLE email_templates AUTO_INCREMENT = 1;");
            EmailTemplates::insert([
                [
                    'name' => 'New User Registration - Customer',
                    'slug' => 'new-customer-account-creation',
                    'subject' => 'Welcome to ##SITE_NAME##',
                    'content'=>'<h4 style="margin: 0 0 15px;">Dear ##GREETING_NAME##,</h4>
                        <p style="margin: 0 0 10px;line-height: 30px;">Thank you for registering with ##SITE_NAME## !!!</p>
                        <p style="margin: 0 0 10px;line-height: 30px;">Enjoy your experience with ##SITE_NAME## by streaming High Quality Content.</p>', 
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Profile Update - Customer & Admin User',
                    'slug' => 'customer-admin-account-update',
                    'subject' => '##SITE_NAME## - Your account has been updated',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##GREETING_NAME##,</h3>
                                    <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Your profile has been updated successfully on ##DATE## by ##MODIFIER_NAME##.</p>',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Forgot Password Content',
                    'slug' => 'forgot_password',
                    'subject' => '##SITE_NAME## - Your Link To Reset your password',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##USERNAME##,</h3>
                                <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Please use the link below to reset your password</p>
                                <h4 style="margin: 30px 0px 20px;text-transform:capitalize;"><a href="##FORGOTPASSWORD##" style="margin:0 auto;text-align:center;padding:10px;color:#fff;background: #26caf0;text-decoration: none;display:inline-block;border-radius: 3px;">reset your password</a></h4>
                                <p style="display:inline-block;text-align:center;font-weight:400;font-size:14px;line-height:20px;color:#7c7c7c;">Please dont share this.</p>',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Change password',
                    'slug' => 'change_password',
                    'subject' => '##SITE_NAME## - Change your password',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##USERNAME##,</h3>
                                    <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">You have successfully changed the password to ##CHANGEPASSWORD##.</p>',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'New User Registration - Admin User',
                    'slug' => 'new-admin-user-account-creation',
                    'subject' => 'Welcome to ##SITE_NAME##',
                    'content'=>'<h4 style="margin: 0 0 15px;">Hi ##GREETING_NAME##,</h4>
                                <p style="margin: 0 0 10px;line-height: 30px;" style="margin: 0 0 10px;line-height: 30px;">Your ##SITE_NAME## admin account has been created.</p>
                                <p style="margin: 0 0 10px;line-height: 30px;"> Please click on the link below to log into your account <a href="##URL##" target="_blank" style="color: #29b6aa; font-weight: 400">##URL##</a></p>
                                <p style="margin: 0 0 10px;line-height: 30px;">User Name: ##EMAIL##</p>
                                <p style="margin: 0 0 10px;line-height: 30px;">Password: ##PASSWORD##</p>', 
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Forgot Password Content',
                    'slug' => 'admin_forgot',
                    'subject' => '##SITE_NAME## - Your Password',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##USERNAME##,</h3>
                                    <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;"> Your password for admin backend is :  ##FORGOTPASSWORD##. Please dont share this.</p>',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Upgrade Notification to Admin',
                    'slug' => 'upgrade_mailto_admin',
                    'subject' => '##SITE_NAME## - User subscription notification',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear admin,</h3>
                                    <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">##NAME## has subscribed to ##PLAN##.</p>',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),

                ],
                [
                    'name' => 'Upgrade Notification to user',
                    'slug' => 'upgrade_mailto_customer',
                    'subject' => '##SITE_NAME## - Subscription successfull',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##USERNAME##,</h3>
                                    <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Your account is succesfully upgraded to ##PLAN##, Thank you for your subscription.</p>
                                    <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Please continue use our services and be benifited.</p>',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Unsubscribe Notification',
                    'slug' => 'unsubscription_mailto_customer',
                    'subject' => '##SITE_NAME## - Unsubscription',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##USERNAME##,</h3>
                            <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Your ##PLAN## plan has successfully unsubscribed, Please do subscribe to continue services.</p>',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Contact Us Content',
                    'slug' => 'contact_us',
                    'subject' => '##SITE_NAME## - Contact Us',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##USERNAME##,</h3>
                                <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Name: ##NAME##</p>
                                <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Email: ##EMAIL##</p>
                                <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Contact: ##CONTACTNUMBER##</p>
                                <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Message: ##MESSAGE##</p>
                                ',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'Subscription Reminder',
                    'slug' => 'subscription_reminder',
                    'subject' => '##SITE_NAME## - Subscription Reminder',
                    'content' => '<h3 style="font-weight:600;font-size:16px;color:#0d0808;margin: 0;">Dear ##USERNAME##,</h3>
                                <p style="font-weight:400;font-size: 16px;color:#393939;margin:3% 0 0 0;">Your subscription plan ##CONTENT##. Please subscribe to use continues unlimited services</p>
                                ',
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'name' => 'New User Registration - By Admin In Customer Management',
                    'slug' => 'new-user-account-creation-by-admin-in-customer-mgmt',
                    'subject' => 'Welcome to ##SITE_NAME##',
                    'content'=>'<h4 style="margin: 0 0 15px;">Hi ##GREETING_NAME##,</h4>
                                <p style="margin: 0 0 10px;line-height: 30px;" style="margin: 0 0 10px;line-height: 30px;">Your ##SITE_NAME## account has been created by the site administrator.</p>
                                <p style="margin: 0 0 10px;line-height: 30px;"> Please click on the link below to log into your account <a href="##URL##" target="_blank" style="color: #29b6aa; font-weight: 400">##URL##</a></p>
                                <p style="margin: 0 0 10px;line-height: 30px;">Enjoy your experience with ##SITE_NAME## by streaming High Quality Content.</p>
                                <p style="margin: 0 0 10px;line-height: 30px;">User Name: ##EMAIL##</p>
                                <p style="margin: 0 0 10px;line-height: 30px;">Password: ##PASSWORD##</p>', 
                    'is_active' => '1',
                    'creator_id' => '1',
                    'updator_id' => '1',
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ],
            ]);
        }
    }
}