<?php
if (!function_exists('switchDatabase')) {
    /**
     * Function to switch database to tenant database from main database
     *
     * @return void
     */
    function switchDatabase($subscriber = array())
    {
        // Database Name generation logic (eg format. alecia_subscriber_id)
        $subscriber = (!empty($subscriber)) ? $subscriber : auth()->user()->subscriber;
        // genereate db name
        $dbName = generateDbName($subscriber);
        // genereate db user
        $dbUser = generateDbUser($subscriber);
        // genereate db password
        $dbPassword = dbPassword($subscriber);
        // DB Configuration
        config(['database.connections.mysql_tenant' => array('driver' => 'mysql',
            'host' => 'localhost',
            'database' => $dbName,
            'username' => $dbUser,
            'password' => $dbPassword,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false)]);
        $res = DB::select("show databases like '{$dbName}'");
        if (count($res) == 0) {
            return redirect('logout');
        }
        // Default connection changed to tenant DB
        DB::setDefaultConnection('mysql_tenant');
    }
}

if (!function_exists('isJson')) {
    /**
     * To check if the given value is a valid json string
     *
     * @param string $value
     * @return boolean
     */
    function isJson($value)
    {
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('generateDbName')) {
    /**
     * generate dbname
     * using hashkey value from the subscriber table
     */
    function generateDbName($subscriber)
    {
        // if $user->randomString exists logic will use business name else randomString
        $businessName = is_null($subscriber->hash_key) ? $subscriber->business_name : $subscriber->hash_key;
        $appendString = is_null($subscriber->hash_key) ? "ALECIA" : "CTLG";
        // Get first 3 characters frm bsuiness name
        $businessKey = substr(str_replace(' ', '', $businessName), 0, 3);
        // Reverse the 3 characters
        $businessReverse = strrev($businessKey);
        // DB Name Format
        // Reverse Business Name + Subscriber Id + reverse base64 encoded reveresed business name + ALECIA
        $dbName = $businessReverse . $subscriber->id . base64_encode(strrev($businessReverse)) . $appendString;
        // Make lowercase
        return env('DB_PREFIX', '') . strtolower($dbName);
    }
}

if (!function_exists('generateDbUser')) {
    /**
     * generate db user logic
     * using hashkey value from the subscriber table
     */
    function generateDbUser($subscriber)
    {
        // if $user->randomString exists logic will use business name else randomString
        return is_null($subscriber->hash_key) ? str_slug(str_limit($subscriber->business_name, 15, ''), '') : str_slug(str_limit(base64_encode(substr(str_replace(' ', '', $subscriber->hash_key), 0, 3)) . $subscriber->id, 15, ''), '');
    }
}

if (!function_exists('dbPassword')) {
    /**
     * Database Password generation logic (eg format .
     * company_name|ALECIA|subscriber_id|%^&s!@$%)
     * using hashkey value from the subscriber table
     */
    function dbPassword($subscriber)
    {
        return sha1(generateDbUser($subscriber) . '|ALECIA|' . $subscriber->id . '|%^&s!@$%');
    }
}

if (!function_exists('getCurrency')) {
    /**
     * to get currency symbol
     *
     * @return boolean
     */
    function getCurrency()
    {
        if (config('settings.general_settings.default_currency_symbol')) {
            return config('settings.general_settings.default_currency_symbol');
        }
        return '$';
    }
}

if (!function_exists('getCurrencyType')) {
    /**
     * to get currency symbol
     *
     * @return boolean
     */
    function getCurrencyType()
    {
        if (config('settings.general_settings.default_currency')) {
            return config('settings.general_settings.default_currency');
        }
        return 'USD';
    }
}

if (!function_exists('getValidButton')) {
    function getValidButton($routeName, $value, $className = 'btn btn-primary', $routeParam = '')
    {
        if (Gate::allows('accessPolicy', $routeName)) {
            return '<a class="' . $className . '" href="' . route($routeName, $routeParam) . '">' . trans($value) . '</a>';
        }
    }
}

if (!function_exists('getAssets')) {
    /**
     * to check if the user current subscription is active or not
     *
     * @return boolean
     */
    function getAssets($module, $url = false)
    {
        switch ($module) {
            case PRODUCT_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . PRODUCT_PATH . '/';
                break;
            case CATEGORY_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . CATEGORY_PATH . '/';
                break;
            case BRAND_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . BRAND_PATH . '/';
                break;
            case MANUFACTURER_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . MANUFACTURER_PATH . '/';
                break;
            case USER_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . USER_PATH . '/';
                break;
            case CUSTOMER_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . CUSTOMER_PATH . '/';
                break;
            case SUPPLIER_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . SUPPLIER_PATH . '/';
                break;
            case LOGO_PATH:
                $path = UPLOADS . auth()->user()->subscriber->id . '/' . LOGO_PATH . '/';
                break;
            default:
                $path = UPLOADS . auth()->user()->subscriber->id . '/';
                break;
        }
        return ($url) ? $path : public_path() . $path;
    }
}

if (!function_exists('getMonth')) {
    /**
     * To get the list of months
     *
     * @return multitype:string
     */
    function getMonth()
    {
        $months = array();
        foreach (range(1, 12) as $month) {
            $months[$month] = date('F', mktime(0, 0, 0, $month));
        }
        return $months;
    }
}

if (!function_exists('getYear')) {
    /**
     * To get the expiry years
     *
     * @param int $startYear
     * @param int $endYear
     * @return number
     */
    function getYear($startYear, $endYear)
    {
        $years = range($startYear, $endYear);
        // [2013 => 2013]
        return array_combine($years, $years);
    }
}
if (!function_exists('array_filter_recursive')) {
    /**
     * To remove the value that are NULL in array
     *
     * @param array $input
     * @return array
     */
    function array_filter_recursive($input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = array_filter_recursive($value);
            }
        }
        return array_filter($input);
    }
}
if (!function_exists('progressUpdate')) {
    /**
     * sending progress to view
     *
     * @param id integer current record
     *
     * @param message string current message
     *
     * @param float progress current progress
     *
     * @param total records
     */
    function progressUpdate($id, $message, $progress, $countToExport)
    {
        $d = array('message' => $message, 'progress' => $progress, 'totalCount' => $countToExport);
        echo "id: $id" . PHP_EOL;
        echo "data: " . json_encode($d) . PHP_EOL;
        echo PHP_EOL;
        ob_flush();
        flush();
    }
}

if (!function_exists('uploadImageFromUrl')) {
    /**
     * To upload image from the url and upload in server
     *
     * @param unknown $url
     * @param unknown $path
     * @param string $prefix
     * @throws ValidateException
     * @return string
     */
    function uploadImageFromUrl($url, $path, $prefix = null)
    {
        if (@getimagesize($url)) {
            //add time to the current filename
            $name = basename($url);
            list($txt, $ext) = explode(".", $name);
            $ext = pathinfo(basename($url), PATHINFO_EXTENSION);
            $name = $txt . time();
            $name = (!empty($prefix)) ? $prefix . "_" . $name . "." . $ext : $name . "." . $ext;
            //check if the files are only image / document
            $allowed = array("jpg", "png", "gif", "jpeg", "bmp");
            //here is the actual code to get the file from the url and save it to the uploads folder
            //get the file from the url using file_get_contents and put it into the folder using file_put_contents
            if (in_array($ext, $allowed)) {
                //here is the actual code to get the file from the url and save it to the uploads folder
                //get the file from the url using file_get_contents and put it into the folder using file_put_contents
                //check success
                if (file_put_contents($path . $name, file_get_contents($url))) {
                    return urlencode($name);
                }
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('checkRouteAccess')) {
    function checkRouteAccess($routeName)
    {
        if (isset($routeName['child'])) {
            $route = $routeName['child'];
        } else {
            $route = $routeName;
        }
        foreach ($route as $eachRouteName) {
            if (Gate::allows('accessPolicy', $eachRouteName) || ($eachRouteName == 'dashboard') || ($eachRouteName == 'user.myprofile') || ($eachRouteName == 'user.change-password')) {
                return 1;
            }
        }
    }
}
if (!function_exists('checkRouteAllowAccess')) {
    function checkRouteAllowAccess($routeName)
    {
        if (Gate::allows('accessPolicy', $routeName) || ($routeName == 'dashboard') || ($routeName == 'user.myprofile') || ($routeName == 'user.change-password')) {
            return 1;
        }
        return 0;
    }
}
if (!function_exists('uniqueUserName')) {
    function uniqueUserName($data, $model, $fieldName, $id)
    {
        $username = substr($data, 0, strpos($data, '@'));
        return userNameExist($username, $model, $fieldName, $id);
    }
}

if (!function_exists('userNameExist')) {
    function userNameExist($username, $model, $fieldName, $id)
    {
        $value = $username;
        if ($model->where($fieldName, $username)->where('id', '!=', $id)->count() > 0) {
            $value = $value . str_random(4);
            userNameExist($value, $model, $fieldName, $id);
        }
        return $value;
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        $url = app('url')->asset($path, $secure);

        if (env('ADMIN_PREFIX')) {
            $url = str_replace('/' . env('ADMIN_PREFIX'), '', $url);
        }
        return $url;
    }
}

if (!function_exists('isMobile')) {
    /**
     * To detect if the request is from mobile or from web
     *
     * @return boolean
     */
    function isMobile()
    {
        $isMobileApp = false;
        if (!is_null(app()->make('request')->header('X-REQUEST-TYPE')) && app()->make('request')->header('X-REQUEST-TYPE') == 'mobile') {
            $isMobileApp = true;
        }
        return $isMobileApp;
    }
}

if (!function_exists('isAdmin')) {
    /**
     * To detect if the request is from mobile or from web
     *
     * @return boolean
     */
    function isAdmin()
    {
        $isAdmin = false;
      
        if (!is_null(app()->make('request')->header('X-REQUEST-TYPE')) && app()->make('request')->header('X-REQUEST-TYPE') == 'admin') {
            $isAdmin = true;
        }
        return $isAdmin;
    }
}

if (!function_exists('isWebsite')) {
    /**
     * To detect if the request is from mobile or from web
     *
     * @return boolean
     */
    function isWebsite()
    {
        $isCustomer = false;
        if (!is_null(app()->make('request')->header('X-REQUEST-TYPE')) && app()->make('request')->header('X-REQUEST-TYPE') == 'web') {
            $isCustomer = true;
        }
        return $isCustomer;
    }
}

if (!function_exists('getPlatform')) {
    /**
     * To detect if the request is from mobile or from web
     *
     * @return boolean
     */
    function getPlatform(){
        $platform = 'web';
        if (!is_null(app()->make('request')->header('X-DEVICE-TYPE'))) {
            $platform = app()->make('request')->header('X-DEVICE-TYPE');
            switch (strtolower($platform)) {
                case 'ios':
                    $platform = 'ios';
                    break;
                case 'android':
                    $platform = 'android';
                    break;
                default:
                    $platform = 'web';
                    break;
            }
        }
        return $platform;
    }
}

if (!function_exists('formatMobileNumer')) {
    /**
     * To format given input number
     *
     * @return string
     */
    function formatMobileNumer($number, $default = null)
    {
        if ($default == 1) {
            $number = '1234567890';
        }

        $number = preg_replace("/[^0-9]/", "", $number);
        if (preg_match('/^(\d{0,3})(\d{0,3})(\d{0,10})$/', $number, $matches)) {
            $number = '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
        }
        return $number;
    }
}

if (!function_exists('formatCardMonth')) {
    /**
     * To format given input number
     *
     * @return string
     */
    function formatCardMonth($month)
    {
        return (strlen($month) == 1) ? '0' . $month : $month;
    }
}

if (!function_exists('getAdminLogoUrl')) {
    /**
     * To get admin logo url
     *
     * @return string
     */
    function getAdminLogoUrl()
    {
        return env('AWS_S3_URL') . 'settings/logo.png';
    }
}

if (!function_exists('getAdminFavUrl')) {
    /**
     * To get admin fav icon url
     *
     * @return string
     */
    function getAdminFavUrl()
    {
        return env('AWS_S3_URL') . 'settings/fav.png';
    }
}

if (!function_exists('filterCreatedDate')) {
    /**
     * To add date filter validation
     *
     * @return string
     */
    function filterCreatedDate($value, $model)
    {
        if (trim($value) != '') {
            $value = explode('-', trim($value));
            $fromDate = \Carbon\Carbon::parse($value[0]);
            $toDate = \Carbon\Carbon::parse($value[1]);
            if ($fromDate == $toDate) {
                $model->whereDate('created_at', '=', $fromDate);
            } else {
                $model->whereDate('created_at', '>=', $fromDate);
                $model->whereDate('created_at', '<=', $toDate);
            }
        }
    }
}

if (!function_exists('setRequestOriginalImage')) {
    /**
     * To set the original param fields
     *
     * @return string
     */
    function setRequestOriginalImage($paramArray)
    {
        if (app()->request->has('select_original_image')) {
            $existArray = app()->request->select_original_image;
            if (is_array($existArray) && !empty($existArray)) {
                $paramArray = array_merge($existArray, $paramArray);
            }
        }
        return app()->request->request->add(['select_original_image' => $paramArray]);
    }
}

if (!function_exists('getRequestOriginalImage')) {
    /**
     * To get the original param fields
     *
     * @return string
     */
    function getRequestOriginalImage()
    {
        if (app()->request->has('select_original_image')) {
            return app()->request->select_original_image;
        }
        return [];
    }
}

if (!function_exists('getTagPrefix')) {
    /**
     * To get the tag prefix for cache tags
     *
     * @return string
     */
    function getTagPrefix()
    {
        $tempTag = url('/');
        return env('CACHE_TAG', $tempTag);
    }
}

if (!function_exists('getCacheTime')) {
    /**
     * To get the cache time for all cache
     *
     * @return string
     */
    function getCacheTime()
    {
        return env('CACHE_TIME', 120);
    }
}

if (!function_exists('getUserPrefix')) {
    /**
     * To get the tag prefix for cache tags
     *
     * @return string
     */
    function getUserPrefix()
    {
        if (!empty((array)(authUser()))) {
            return authUser()->id;
        }
        return 0;
    }
}

if (!function_exists('generateOrderNumber')) {
    /**
     * To get the tag prefix for cache tags
     *
     * @return string
     */
    function generateOrderNumber($orderId)
    {
        $prefix = config('common.pattern.order');
        $diff = strlen($prefix) - strlen($orderId);
        if ($diff > 0) {
            return substr($prefix, 0, strlen($prefix) - strlen($orderId)) . $orderId;
        }
        return $orderId;
    }
}

if (!function_exists('slugOrId')) {
    /**
     * To detect if the request is from mobile or from web
     *
     * @return boolean
     */
    function slugOrId($model = '')
    {
        $slug = ($model == 'order') ? 'incremental_id' : 'slug';
        if (!is_null(app()->make('request')->header('X-REQUEST-TYPE')) && app()->make('request')->header('X-REQUEST-TYPE') == 'mobile') {
            $slug = 'id';
        }
        return $slug;
    }
}

if (!function_exists(('cryptoJsAesDecrypt'))) {
    /**
     * Decrypt data from a CryptoJS json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $jsonString
     * @return mixed
     */
    function cryptoJsAesDecrypt($value)
    {
        if(getPlatform() == 'ios') {
            $encrypted_data = openssl_decrypt(hex2bin($value), 'aes-256-cbc', hex2bin(config('contus.video.video.crypto_ios_key')),0, hex2bin(config('contus.video.video.crypto_ios_iv')));
        }
        else {
            $encrypted_data = openssl_decrypt(hex2bin($value), 'aes-256-cbc', hex2bin(config('contus.video.video.crypto_key')), true, hex2bin(config('contus.video.video.crypto_iv')));
        }

        return hex2bin($encrypted_data);
    }
}

if (!function_exists(('cryptoJsAesEncrypt'))) {
    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function cryptoJsAesEncrypt($value)
    {
        if(getPlatform() == 'ios') {
            $encrypted_data = openssl_encrypt(bin2hex($value), 'aes-256-cbc', hex2bin(config('contus.video.video.crypto_ios_key')),0, hex2bin(config('contus.video.video.crypto_ios_iv')));
        }
        else {
            $encrypted_data = openssl_encrypt(bin2hex($value), 'aes-256-cbc', hex2bin(config('contus.video.video.crypto_key')), true, hex2bin(config('contus.video.video.crypto_iv')));
        }
        return bin2hex($encrypted_data);
    }
}

if (!function_exists(('dynamicTrans'))) {
    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function dynamicTrans($module, $originalMessage, $exceptions = null)
    {
        if (app()->make('request')->header('debug') == 1 && !empty($exceptions)) {
            return $exceptions->getMessage();
        } else {
            return str_replace('[MODULE_NAME]', $module, $originalMessage);
        }
    }
}

if (!function_exists(('authUser'))) {
    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function authUser()
    {
        if(app()->make('request')->headers->has('Authorization')) {
            $token = app()->make('request')->header('Authorization');
            return \JWTAuth::toUser($token);
        }
        else {
            return new stdclass();
        }
    }
}

if (!function_exists(('getIPAddress'))) {
    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function getIPAddress()
    {
        $original_ip = (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
        if(empty($original_ip))   {
            $original_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';
        }

        $remoteAdds =   explode(",",$original_ip);
        return (!empty($remoteAdds[0])) ? $remoteAdds[0] : '';
    }
}



/**
 * Get all classes in given namespace
 * @param string $namespace
 * @return array
 */
function getClassesInNamespace($namespace)
{
    $files = scandir(getNamespaceDirectory($namespace));

    $classes = array_map(function ($file) use ($namespace) {
        return $namespace . '\\' . str_replace('.php', '', $file);
    }, $files);

    return array_filter($classes, function ($possibleClass) {
        return class_exists($possibleClass);
    });
}

/**
 * Get namespace directory
 * @param string $namespace
 */
function getNamespaceDirectory($namespace)
{
    $composerNamespaces = getDefinedNamespaces();
    $namespaceFragments = explode('\\', $namespace);
    $undefinedNamespaceFragments = [];

    while ($namespaceFragments) {
        $possibleNamespace = implode('\\', $namespaceFragments) . '\\';
        if (array_key_exists($possibleNamespace, $composerNamespaces)) {
            return realpath(base_path() . '/' . $composerNamespaces[$possibleNamespace] . '/' . implode('/', array_reverse($undefinedNamespaceFragments)));
        }

        $undefinedNamespaceFragments[] = array_pop($namespaceFragments);
    }

    return false;
}

/**
 * Get defined namespace
 * @param string $namespace
 */
function getDefinedNamespaces()
{
    $composerJsonPath = base_path() . '/composer.json';
    $composerConfig = json_decode(file_get_contents($composerJsonPath));

    //Apparently PHP doesn't like hyphens, so we use variable variables instead.
    $psr4 = "psr-4";
    return (array) $composerConfig->autoload->$psr4;
}


if (!function_exists(('getCacheKey'))) {
    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function getCacheKey($skipUser = 0)
    {
        $key    = '';
        if(!empty(auth()->id) && !$skipUser) {
            $key .= 'auth'.authUser()->id;
        }
        else {
            $key .= 'auth0';
        }
        $key .= (!empty(app()->request->has('page')) && app()->request->page) ? '_page'.app()->request->page : '_page0';
        $key .= (isMobile()) ? '_mobile1' : '_mobile0';
        if (!is_null(app()->make('request')->header('X-LANGUAGE-CODE'))) {
            $key .= '_'.app()->make('request')->header('X-LANGUAGE-CODE');
        }
        else {
            $key .= '_en';
        }
        return $key;
    }
}

if (!function_exists(('getCacheTag'))) {
    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function getCacheTag()
    {
        return env('GLOBAL_CACHE_TAG', '');
    }
}

if (!function_exists(('getCacheTime'))) {
    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function getCacheTime()
    {
        return env('GLOBAL_CACHE_TIME', 120);
    }
}