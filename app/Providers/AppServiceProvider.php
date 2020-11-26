<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Contus\User\Models\SettingCategory;
use Contus\User\Models\Setting;
use Contus\User\Repositories\SettingsRepository;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {

        if(env('ENABLE_SSL')) {
            \URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
        $this->setSettingToConfig();
        Validator::extend('card_validate', function ($attribute, $value, $parameters, $validator) {
            $request = app()->request->all();
            $val = false;
            if (isset($request['card_id']) && $request['card_id'] > 0) {
                $val = true;
            } elseif (!isset($request['card_id'])) {
                foreach ($parameters as $key => $value) {
                    if (array_key_exists($value, $request)) {
                        $val = true;
                    } else {
                        $val = false;
                        break;
                    }
                }
            }
            return $val;
        });
    }

    /**
     * Method used to set the config values from cache file.
     *
     * While updating the setting datas from admin side the cache file will be generated.
     *
     * All the setting data stored in JSON format under the storage path
     *
     * @return void
     */
    public function setSettingToConfig()
    {
        if(isMobile()) {
            config()->set('access.perpage', 20);
        }else {
            config()->set('access.perpage', 20);
        }

        if (Cache::has('setting_table_exist')) {
            $settingExist = Cache::get('setting_table_exist');
        }
        else {
            $settingExist = Schema::hasTable('settings');
            Cache::forever('setting_table_exist', $settingExist);
        }

        if (!empty(env('APP_DEBUG')) && $settingExist == 1) {
            if (Cache::has('settings_caches')) {
                config()->set('settings', json_decode(Cache::get('settings_caches'), true));
                $this->setSessionLifeTimeToConfig();
            } else {
                $repo = new SettingsRepository(new Setting(), new SettingCategory());
                $repo->generateSettingsCache();
                config()->set('settings', json_decode(app('cache')->get('settings_caches'), true));
            }
        }
    }

    /**
     * Method used to set the session lifetime value.
     *
     * Overwrite the session.php lifetime value based on the admin settings
     *
     * @return boolean
     */
    public function setSessionLifeTimeToConfig()
    {
        $sessionLifetime = config('settings.security-settings.session-settings.session_lifetime');
        config()->set('session.lifetime', ($sessionLifetime) ? $sessionLifetime : 120);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(env('ENABLE_SSL')) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }
}
