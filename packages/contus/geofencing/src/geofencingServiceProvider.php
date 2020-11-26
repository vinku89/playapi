<?php

/**
 * Service Provider for Geofencing
 *
 * @name GeofencingServiceProvider
 * @vendor Contus
 * @package Geofencing
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Geofencing;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class GeofencingServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     * @pakage geofencing
     *
     * @return void
     */
    public function boot() {
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', 'geofencing' );
        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', 'geofencing' );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/geofencing' ) ], 'geofencing_assets' );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/geofencing' ) ], 'geofencing_config' );
        $this->shareDataToView ();
    }
    
    /**
     * Register the application services.
     *
     * @vendor Contus
     * @pakage geofencing
     * 
     * @return void
     */
    public function register() {
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';
    }
    
    /**
     * Method used to share the datas to balde file.
     *
     * Can access getUrl, auth, siteSettings in view files.
     *
     * @return void
     */
    public function shareDataToView() {
        view ()->share ( 'getgeofencingAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.geofencing.geofencing.vendor' ) . '/' . config ( 'contus.geofencing.geofencing.package' ) . '/' . $url );
        } );
    }
}
