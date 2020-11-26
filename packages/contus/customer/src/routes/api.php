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

Route::group(['prefix' => 'api/v1', 'namespace' => 'Contus\Customer\Api\Controllers\Customer'], function () {
    /**
     * Auth Routes
     */
    Route::post('auth/register', 'CustomerAuthController@apiRegister');
    Route::post('auth/login', 'CustomerAuthController@apiLogin');
    Route::post('auth/logout', 'CustomerAuthController@apiLogout');
    Route::post('auth/reset', 'CustomerAuthController@apiReset');
    Route::put('auth/reset', 'CustomerAuthController@apiResetPass');
    Route::post('auth/social', 'CustomerAuthController@apiSocial');
    Route::post('auth/forgotpassword', 'CustomerAuthController@apiForgotpassword');
    Route::group(['middleware' => ['api.auth']], function () {
        Route::put('auth/change', 'CustomerAuthController@apiPassChange');
        /**
         * Subscription Routes
         */
        Route::get('subscription/{slug}', 'SubscriptionPlanController@fetchOne');
        Route::post('addsubscriber/{slug}', 'SubscriptionPlanController@updatesubscription');
        Route::get('favourite', 'FavouriteVideosController@index');
        Route::post('favourite', 'FavouriteVideosController@store');
        Route::put('favourite', 'FavouriteVideosController@destroy');
        Route::delete('favourite', 'FavouriteVideosController@destroy');
        Route::get('playlists', 'FollowPlaylistsController@index');
        Route::post('playlists', 'FollowPlaylistsController@store');
        Route::put('playlists', 'FollowPlaylistsController@destroy');
        Route::delete('playlists', 'FollowPlaylistsController@destroy');
        Route::post('recentlyViewed', 'RecentlyViewedVideoController@store');
        Route::get('recentlyViewed', 'RecentlyViewedVideoController@index');
        Route::post('customerProfile', 'CustomerAuthController@updateProfile');

        /**
         * my account routes
         */
    });
});

  Route::prefix('api/admin')->namespace('Contus\Customer\Api\Controllers\Customer')->group( function () {
     Route::group ( [ 'middleware' => [ 'api.admin','accesslevel' ] ], function ()  {
       Route::resource('customers', 'CustomerResourceController' );
       Route::post('customer-subscription', 'CustomerResourceController@addSubcription' );
       Route::get('customer/info', 'CustomerAuthController@getInfo');
       Route::post('customer/records', 'CustomerAuthController@postRecords');
       Route::post('customer/action', 'CustomerAuthController@postAction');
       Route::post('customer/update-status/{id}', 'CustomerAuthController@postUpdateStatus');
  
   });
 /**Subscription Routes **/
  Route::get('subscriptions-plans/info', 'SubscriptionPlanController@getInfo');
  Route::post('subscriptions-plans/records', 'SubscriptionPlanController@postRecords');
  Route::post('subscriptions-plans/add', 'SubscriptionPlanController@postAdd');
  Route::post('subscriptions-plans/update-status/{id}', 'SubscriptionPlanController@postUpdateStatus');
  Route::post('subscriptions-plans/edit/{id}', 'SubscriptionPlanController@postEdit');
  Route::post('subscriptions-plans/action', 'SubscriptionPlanController@postAction');
  });

Route::group ( [ 'prefix' => 'api/v1','namespace' => 'Contus\Customer\Api\Controllers\Account' ], function () {
    Route::group ( [ 'middleware' => ['api.auth' ] ], function () {
        Route::post ( 'editProfile/profile-image', 'MyAccountController@postProfileImage' );
        Route::post ( 'notificatinStatus', 'MyAccountController@updateNotificationStatus' );
        Route::get ( 'profile', 'MyAccountController@profileData' );
    } );
} );
/**
 *  Api route url version 2 created for mobile update
 *
 */
Route::group ( [ 'prefix' => 'api/v2','namespace' => 'Contus\Customer\Api\Controllers\Customer', 'middleware' => ['cors'] ], function () {
    /**
     * Auth Routes
     */
    Route::post ( 'auth/register', 'CustomerAuthController@apiRegister' );
    Route::post ( 'auth/login', 'CustomerAuthController@apiLogin' );
    Route::post ( 'auth/reset', 'CustomerAuthController@apiReset' );
    Route::post ( 'auth/resetpassword', 'CustomerAuthController@apiResetPassword' );
    Route::get ( 'auth/verify_resetpassword', 'CustomerAuthController@apiVerifyResetPassword' );

    Route::put ( 'auth/reset', 'CustomerAuthController@apiResetPass' );
    Route::post ( 'auth/social', 'CustomerAuthController@apiSocial' );
    Route::post ( 'auth/forgotpassword', 'CustomerAuthController@apiForgotpassword' );

    Route::group ( [ 'middleware' => [ 'updatedversion','jwt-auth', 'api.auth' ] ], function () {
        Route::post ( 'auth/logout', 'CustomerAuthController@apiLogout' );
        Route::put ( 'auth/change', 'CustomerAuthController@apiPassChange' );
        Route::post ( 'auth/change', 'CustomerAuthController@apiPassChange' );
        Route::post('auth/device_token', 'CustomerAuthController@updateDeviceToken');
        
        /**
         * Subscription Routes
         */
        Route::get ( 'subscription/{slug}', 'SubscriptionPlanController@fetchOne' );
        Route::post ( 'addsubscriber/{slug}', 'SubscriptionPlanController@updatesubscription' );
        Route::get ( 'favourite', 'FavouriteVideosController@index' );
        Route::post ( 'favourite', 'FavouriteVideosController@store' );
        Route::put ( 'favourite', 'FavouriteVideosController@destroy' );
        Route::delete ( 'favourite', 'FavouriteVideosController@destroy' );
        Route::get ( 'playlists', 'FollowPlaylistsController@index' );
        Route::post ( 'playlists', 'FollowPlaylistsController@store' );
        Route::put ( 'playlists', 'FollowPlaylistsController@destroy' );
        Route::delete ( 'playlists', 'FollowPlaylistsController@destroy' );
        Route::post ( 'recentlyViewed', 'RecentlyViewedVideoController@store' );
        Route::get ( 'recentlyViewed', 'RecentlyViewedVideoController@index' );
        Route::post ( 'customerProfile', 'CustomerAuthController@updateProfile' );
    } );
} );
Route::group ( [ 'prefix' => 'api/v2','namespace' => 'Contus\Customer\Api\Controllers\Account' ], function () {
    Route::group ( [ 'middleware' => ['cors', 'updatedversion', 'jwt-auth', 'api.auth'] ], function () {
        Route::post ( 'editProfile/profile-image', 'MyAccountController@postProfileImage' );
        Route::post ( 'notificatinStatus', 'MyAccountController@updateNotificationStatus' );
        Route::get ( 'profile', 'MyAccountController@profileData' );
    } );
} );

