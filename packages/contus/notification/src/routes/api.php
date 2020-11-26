<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([ 'prefix' => 'api/admin','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function () {
    Route::group([ 'middleware' => [ 'api.admin','accesslevel' ] ], function () {
        //Route::controller ( 'notifications', 'NotificationController' );
    });
    Route::post('notify', 'NotificationController@setNotification');
});

Route::group([ 'prefix' => 'api/v1','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function () {
    Route::group([ 'middleware' => [ 'api.auth'] ], function () {
        Route::get('notifications', 'NotificationController@getNotifications');
        Route::post('notify', 'NotificationController@setNotification');
    });
});
Route::group([ 'prefix' => 'api/v2','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function () {
    Route::group([ 'middleware' => [ 'cors', 'updatedversion','jwt-auth', 'api.auth'] ], function () {
        Route::get('notifications', 'NotificationController@getNotifications');
        Route::post('notify', 'NotificationController@setNotification');
        Route::post('notification/settings', 'NotificationController@updateSettings');
        Route::get('notification/read_all', 'NotificationController@markAsReadAll');
        Route::get('notification/read/{id}', 'NotificationController@markAsRead');
        Route::get('notification/remove_all', 'NotificationController@removeAll');
        Route::get('notification/remove/{id}', 'NotificationController@remove');
        Route::get('notification/clear', 'NotificationController@clearCount');
    });
});
