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
Route::group([ 'prefix' => 'api/admin','namespace' => 'Contus\Video\Api\Controllers\Admin' ], function () {
    /*Live stream routes start*/
    Route::post('createlivestream', 'LiveStreamController@createlivestream');
    Route::post('startlivestream', 'LiveStreamController@startLiveStream');
    Route::post('stoplivestream', 'LiveStreamController@stopLiveStream');
    Route::post('satuslivestream', 'LiveStreamController@statusLivestream');
    /*Live stream routes end*/

    Route::group([ 'middleware' => 'api.admin' ], function () {
        Route::get('dashboard/info', 'DashboardController@getInfo');
        Route::post('image', 'VideoController@uploadImage');
        /* Videos Routes starts */
        Route::get('videos/info', 'VideoController@getInfo');
        Route::post('videos/records', 'VideoController@postRecords');
        Route::get('videos/video-to-edit/{id}', 'VideoController@getVideoToEdit');
        Route::get('videos/video-categories/{id}', 'VideoController@getEdit');
        Route::post('videos/update-status/{id}', 'VideoController@postUpdateStatus');
        Route::post('videos/edit/{id}', 'VideoController@postEdit');
        Route::post('videos/delete-action', 'VideoController@postDeleteAction');
        Route::post('videos/bulk-update-status', 'VideoController@postBulkUpdateStatus');
        Route::post('videos/thumbnail', 'VideoController@postThumbnail');
        Route::get('videos/complete-video-details/{id}', 'VideoController@getCompleteVideoDetails');
        Route::post('videos/handle-fine-uploader', 'VideoController@postHandleFineUploader');
        Route::post('videos/add', 'VideoController@postAdd');
        Route::post('videos/delete-thumbnail/{id}', 'VideoController@postDeleteThumbnail');
        Route::post('videos/subtitle', 'VideoController@postSubtitle');
        Route::post('videos/uplaod-banner-video', 'VideoController@postUplaodBannerVideo');
        /* Videos Routes ends */

        /*Live videos Routes start*/
        Route::get('livevideos/info', 'VideoController@getInfo');
        Route::post('livevideos/records', 'LivevideoController@postRecords');
        /*Live videos Routes end*/

        /* Category Routes starting*/
        Route::get('categories/info', 'CategoryController@getInfo');
        Route::post('categories/records', 'CategoryController@postRecords');
        Route::get('categories/videos/{id}', 'CategoryController@getVideoToEdit');
        Route::post('categories/parent-category/{id}', 'CategoryController@postParentCategory');
        Route::get('categories/video-categories/{id?}', 'CategoryController@getVideoCategories');
        Route::get('categories/updated-details', 'CategoryController@getUpdatedDetails');
        Route::post('categories/update-status/{id}', 'CategoryController@postUpdateStatus');
        Route::post('categories/edit/{id}', 'CategoryController@postEdit');
        Route::post('categories/action', 'CategoryController@postAction');
        Route::post('categories/bulk-update-status', 'CategoryController@postBulkUpdateStatus');
        Route::post('categories/add', 'CategoryController@postAdd');
        Route::post('categories/category-image', 'CategoryController@postCategoryImage');
        Route::post('categories/delete-category-image/{id}', 'CategoryController@postDeleteCategoryImage');
        /* Category Routes ending*/

        /*Playlists Routes Starting*/
        Route::post('playlists', 'PlaylistController@postAdd');
        Route::get('playlists/info', 'PlaylistController@getInfo');
        Route::post('playlists/records', 'PlaylistController@postRecords');
        Route::post('playlists/update-status/{id}', 'PlaylistController@postUpdateStatus');
        Route::post('playlists/edit/{id}', 'PlaylistController@postEdit');
        Route::post('playlists/action', 'PlaylistController@postDelete');
        Route::post('playlists/bulk-update-status', 'PlaylistController@postBulkUpdateStatus');
        Route::post('playlists/action', 'PlaylistController@postAction');
        Route::get('playlists/playlists-all', 'PlaylistController@getPlaylistList');
        Route::resource('playlists', 'PlaylistResourceController');
        Route::post('playlist/add', 'PlaylistController@postAdd');
        /*Playlists Routes End*/

        /*Genre Routes Starts*/
        Route::get('collections/info', 'CollectionController@getInfo');
        Route::post('collections/records', 'CollectionController@postRecords');
        Route::get('collections/video-to-edit/{id}', 'CollectionController@getVideoToEdit');
        Route::post('collections/update-status/{id}', 'CollectionController@postUpdateStatus');
        Route::post('collections/edit/{id}', 'CollectionController@postEdit');
        Route::post('collections/action', 'CollectionController@postAction');
        Route::post('collections/bulk-update-status', 'CollectionController@postBulkUpdateStatus');
        Route::post('collections/create-collection', 'CollectionController@postCreateCollection');
        /*Genre Routes End*/

        /*Sub genre Routes Start*/
        Route::get('examgroups/info', 'GroupController@getInfo');
        Route::post('examgroups/add', 'GroupController@postAdd');
        Route::post('examgroups/records', 'GroupController@postRecords');
        Route::get('examgroups/video-to-edit/{id}', 'GroupController@getVideoToEdit');
        Route::post('examgroups/update-status/{id}', 'GroupController@postUpdateStatus');
        Route::post('examgroups/edit/{id}', 'GroupController@postEdit');
        Route::post('examgroups/action', 'GroupController@postAction');
        Route::post('examgroups/bulk-update-status', 'GroupController@postBulkUpdateStatus');
        Route::get('examgroups/videos/{id}', 'GroupController@getVideoCollections');
        /*Sub genre Routes End*/

        /*Presets Routes Start*/
        Route::get('presets/info', 'PresetController@getInfo');
        Route::post('presets/records', 'PresetController@postRecords');
        Route::get('presets/video-to-edit/{id}', 'PresetController@getVideoToEdit');
        Route::post('presets/update-status/{id}', 'PresetController@postUpdateStatus');
        Route::post('presets/edit/{id}', 'PresetController@postEdit');
        Route::post('presets/delete-action', 'PresetController@postDeleteAction');
        Route::post('presets/bulk-update-status', 'PresetController@postBulkUpdateStatus');
        /*Presets Routes End*/

        /*Comments Routes Start*/
        Route::get('comments/info', 'CommentsController@getInfo');
        Route::post('comments/records', 'CommentsController@postRecords');
        Route::get('comments/video-to-edit/{id}', 'CommentsController@getVideoToEdit');
        Route::post('comments/updatestatus/{id}', 'CommentsController@postUpdateStatus');
        Route::post('comments/update-status/{id}', 'CommentsController@postUpdateStatus');
        Route::post('comments/edit/{id}', 'CommentsController@postEdit');
        Route::post('comments/delete-action', 'CommentsController@postDeleteAction');
        Route::post('comments/bulk-update-status', 'CommentsController@postBulkUpdateStatus');
        Route::get('comments/delete/{comment}', 'CommentsController@deleteComments');
        /*Comments Routes End*/

        /*Queries Routes Start*/
        Route::get('qa/info', 'QaController@getInfo');
        Route::post('qa/records', 'QaController@postRecords');
        Route::get('qa/video-to-edit/{id}', 'QaController@getVideoToEdit');
        Route::post('qa/updatestatus/{id}', 'QaController@postUpdateStatus');
        Route::post('qa/update-status/{id}', 'QaController@postUpdateStatus');
        Route::post('qa/edit/{id}', 'QaController@postEdit');
        Route::post('qa/delete-action', 'QaController@postDeleteAction');
        Route::post('qa/bulk-update-status', 'QaController@postBulkUpdateStatus');
        /*Queries Routes End*/

        /*Reports Routes Start*/
        Route::get('/reports', 'ReportsController@getIndex');
        Route::prefix('reports/info')->group(function () {
            Route::get('/{time1?}/{time2?}/{time3?}', 'ReportsController@getInfo');
        });
        /*Reports Routes End*/
    });
});

Route::group([ 'prefix' => 'api/admin','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function () {
    Route::post('comments/{slug}', 'VideoController@browseVideoComments');
});

Route::group([ 'prefix' => 'api/v1','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function () {
    Route::group([ 'middleware' => [ 'api' ] ], function () {
        Route::get('downloadfile', 'VideoController@getDocumentDownload');
        Route::post('livevideos', 'VideoController@browseAllLiveVideos');
        Route::get('getCategoryForNav', 'CategoryController@getCategoriesNav');
        Route::post('getCategoriesNavList', 'CategoryController@getCategoriesNavList');
        Route::post('playlist', 'PlaylistController@browseCategoryPlaylist');
        Route::get('homeWeb', 'VideoController@browseAllCategoryVideos');
        Route::post('homeWeb', 'VideoController@fetchPageAll');
        Route::get('allexams', 'CollectionController@getAllExams');
        Route::get('exam/{examIdorSlug}', 'CollectionController@getAllGroups');
        Route::get('group/{groupIdorSlug}', 'CollectionController@getAllVideos');
        Route::post('videos', 'VideoController@browseVideos');
        Route::get('clearallcache', 'CategoryController@clearAllCache');
    });
    Route::post('livevideonotification', 'VideoController@liveVideoNotification');
});
Route::group([ 'prefix' => 'api/v1','namespace' => 'Contus\Video\Api\Controllers\Frontend','middleware' => [ 'api.auth' ] ], function () {
    Route::post('videos/{slug}', 'VideoController@browseVideo');
    Route::get('videos/{slug}/{playlist_id}', 'VideoController@browseVideo');
    Route::post('videos/related/{slug}', 'VideoController@browseVideoRelated');
    Route::post('getLivevideos', 'VideoController@getLiveVideos');
    Route::get('getAllLiveVideos', 'VideoController@AllLiveVideos');
    Route::post('getAllLiveVideos', 'VideoController@AllLiveVideos');
    Route::get('playlists/video-playlists/{id}', 'PlaylistController@getVideoPlaylists');
    Route::post('videos/comments/{slug}', 'VideoController@browseVideoComments');
    Route::post('videoComments', 'VideoController@getandpostVideocomments');
    Route::post('videos/playlist/{slug}', 'PlaylistController@browsePlaylistList');
    Route::get('myPreferenceList', 'PlaylistController@preferenceListPlaylist');
    Route::get('myPreferenceList/all', 'CategoryController@getCategoriesExams');
    Route::put('savemyPreferenceList', 'PlaylistController@savepreferenceListPlaylist');
    Route::post('savemyPreferenceList', 'PlaylistController@savepreferenceListPlaylist');
    Route::get('myPreferenceCategoryList', 'PlaylistController@mypreferenceCategoryList');
});

Route::group([ 'prefix' => 'api/v1','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function () {
    Route::group([ 'middleware' => [ 'api.auth' ] ], function () {
        Route::post('exams', 'CollectionController@browseCategoryExams');
        Route::post('forgotpassword', 'PlaylistController@forgotpassword');
        Route::post('searchRelatedVideos', 'VideoController@searchRelatedVideos');
        Route::get('videos/section/{slug}', 'VideoController@getCategorySection');
        Route::get('recommended', 'CollectionController@getRecommendedVideos');
        Route::get('recommended/{skip}', 'CollectionController@getRecommendedVideosSkip');
        Route::post('requestpayment', 'CollectionController@sendPaymentlink');
        
    });
});
/**
 *  Api route url version 2 created for mobile update
 *
 */
Route::group([ 'prefix' => 'api/v2','namespace' => 'Contus\Video\Api\Controllers\Frontend','middleware' => [ 'cors', 'updatedversion', 'jwt-auth:1', 'api.auth'] ], function () {
    Route::post('videos/{slug}', 'VideoController@browseVideo');
    Route::get('videos/{slug}/{playlist_id}', 'VideoController@browseVideo');
    Route::get('watchvideo/{slug}/{user_id?}/{is_newversion?}', 'VideoController@browseWatchVideo');
    Route::get('getVideoId/{slug}', 'VideoController@getVideoId');
    Route::post('tvod_view_count', 'VideoController@saveTvodViewCount');
    Route::get('getvideoComments', 'VideoController@getVideocomments');
    Route::get('replyComments/{id}', 'VideoController@replyVideocomments');
    Route::post('videosRelatedTrending', 'VideoController@browseRelatedTrendingVideos');
    Route::get('season_videos/{slug}/{season}', 'VideoController@browseSeasonVideo');
    Route::get('webseason_videos/{slug}/{season}', 'VideoController@browseWebseriesSeasonVideo'); // Calling from different API
    Route::get('key', 'videoDRMController@getKey');
    Route::post('recommended_videos', 'VideoController@recommendedVideos');
    Route::get('video/cast/{slug}', 'VideoController@castList');
    Route::get('parentWebseriesList', 'CategoryController@parentWebseriesList'); // Get parent web series
    Route::get('allWebseries', 'CategoryController@getAllWebseries');// Get all web series
    Route::get('webseries/{slug}/{season?}/{user_id?}', 'CategoryController@browseWebseries');// Get detail of the web series
    Route::get('childWebseries/{slug}/{perpage?}/{search?}', 'CategoryController@browseChildWebseries');// Get all child web series based on parent(is_webseries = true)
    Route::get('childWebseriesMobile/{slug}/{perpage?}/{search?}', 'CategoryController@browseChildWebseriesMobile');// Get all child web series based on parent(is_webseries = true)
});

Route::group([ 'prefix' => 'api/v2','namespace' => 'Contus\Video\Api\Controllers\Frontend','middleware' => [ 'cors', 'updatedversion', 'jwt-auth', 'api.auth'] ], function () {
    Route::post('videos/related/{slug}', 'VideoController@browseVideoRelated');
    Route::post('getLivevideos', 'VideoController@getLiveVideos');
    Route::get('getAllLiveVideos', 'VideoController@AllLiveVideos'); 
    Route::post('getAllLiveVideos', 'VideoController@AllLiveVideos');
    Route::get('playlists/video-playlists/{id}', 'PlaylistController@getVideoPlaylists');
    Route::post('videos/comments/{slug}', 'VideoController@browseVideoComments');
    Route::post('videoComments', 'VideoController@getandpostVideocomments');
    Route::delete('videoComments/{comment}', 'VideoController@deleteComments');
  
    Route::post('videos/qa/{slug}', 'VideoController@browseVideoQA');
    Route::post('videoQuestions', 'VideoController@getandpostVideoQuestions');
    Route::post('videos/playlist/{slug}', 'PlaylistController@browsePlaylistList');
    Route::get('myPreferenceList', 'PlaylistController@preferenceListPlaylist');
    Route::get('myPreferenceList/all', 'CategoryController@getCategoriesExams');
    Route::put('savemyPreferenceList', 'PlaylistController@savepreferenceListPlaylist');
    Route::post('savemyPreferenceList', 'PlaylistController@savepreferenceListPlaylist');
    Route::get('myPreferenceCategoryList', 'PlaylistController@mypreferenceCategoryList');
});
Route::group([ 'prefix' => 'api/v2','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function () {
    Route::group([ 'middleware' => [ 'cors', 'updatedversion', 'jwt-auth', 'api.auth'] ], function () {
        Route::post('exams', 'CollectionController@browseCategoryExams');
        Route::post('forgotpassword', 'PlaylistController@forgotpassword');

        Route::post('searchRelatedVideos', 'VideoController@searchRelatedVideos');
        Route::get('videos/section/{slug}', 'VideoController@getCategorySection');
        Route::get('recommended', 'CollectionController@getRecommendedVideos');
        Route::get('recommended/{skip}', 'CollectionController@getRecommendedVideosSkip');
        Route::post('requestpayment', 'CollectionController@sendPaymentlink');
    });
});
Route::group([ 'prefix' => 'api/v2','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function () {
    Route::group([ 'middleware' => [ 'cors', 'updatedversion','api',  'jwt-auth', 'api.auth' ] ], function () {
        Route::get('downloadfile', 'VideoController@getDocumentDownload');
        Route::get('getCategoryForNav', 'CategoryController@getCategoriesNav');
        Route::post('getCategoriesNavList', 'CategoryController@getCategoriesNavList');
        Route::get('homeWeb', 'VideoController@browseAllCategoryVideos');
        Route::post('homeWeb', 'VideoController@fetchPageAll');
        Route::get('allexams', 'CollectionController@getAllExams');
        Route::get('exam/{examIdorSlug}', 'CollectionController@getAllGroups');
        Route::get('group/{groupIdorSlug}', 'CollectionController@getAllVideos');
        Route::get('clearallcache', 'CategoryController@clearAllCache');
        Route::post('clear_recent_view', 'VideoController@clearView');
        /*
        * Likes routes
        */
        Route::post('like', 'LikeController@postLike');
        Route::post('dislike', 'LikeController@postDisLike');

        //---------Playlist------------
        Route::post('create_playlist', 'UserPlaylistController@createPlaylist');
        Route::get('create_playlist', 'UserPlaylistController@fetchPlaylist');
        Route::delete('create_playlist', 'UserPlaylistController@deletePlaylist');

        Route::get('create_playlist_videos', 'UserPlaylistController@fetchPlaylistVideos');
        Route::delete('create_playlist_videos', 'UserPlaylistController@deletePlaylistVideos');

        Route::post('open/{id}', 'VideoController@downloadUrl');
    });

    Route::group([ 'middleware' => [ 'cors', 'updatedversion','api', 'jwt-auth:1'] ], function () {
        Route::post('livevideonotification', 'VideoController@liveVideoNotification');
        Route::get('category_list', 'CategoryController@categoryList');
        Route::get('countries_list', 'CountriesController@countriesList');
    });
});

Route::group([ 'prefix' => 'api/v2','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function () {
    Route::group([ 'middleware' => [ 'cors', 'updatedversion','api',  'jwt-auth:1', 'api.auth' ] ], function () {
        Route::post('livevideos', 'VideoController@browseAllLiveVideos');
        Route::get('live_more_videos', 'VideoController@browseMoreLiveVideos');
        Route::get('home_page/{search?}', 'VideoController@getHome');
        Route::get('home_page_banner', 'VideoController@fetchHomePageBanner');
        Route::get('getCountryWisefilterTV', 'VideoController@getCountryWisefilterTV');

        Route::get('getCategoriesCountryWise', 'VideoController@getCategoriesCountryWise');
        Route::get('liveVideoSlug/{slug}', 'VideoController@browseLiveVideoSlug'); //vinod
        
        Route::get('home_more', 'VideoController@getMoreVideos');
        Route::get('searchvideos', 'VideoController@searchVideos');

        Route::post('playlist', 'PlaylistController@browseCategoryPlaylist');
        Route::get('playlist', 'PlaylistController@browseCategoryPlaylist');
        Route::post('videos', 'VideoController@browseVideos');

        Route::get('search/videos', 'SearchController@searchVideos');
        Route::get('search/audios', 'SearchController@searchAudios');

        //Route::get('searchVod', 'SearchController@searchVod'); //vinod

        Route::get('category_videos', 'VideoController@fetchCategoryVideos');
        Route::get('home_category_videos', 'VideoController@fetchCategoryVideos');
        Route::get('series_videos', 'VideoController@fetchSeriesVideos');
        Route::get('more_category_videos', 'VideoController@fetchMoreCategoryVideos');
        Route::get('more_category_videos_mobile', 'VideoController@fetchMoreCategoryVideosMobile');

        Route::get('bestbox_dashboard', 'VideoController@fetchBestBoxDashboard'); //vinod
        Route::get('gettopList', 'VideoController@gettopList'); //naveen
        //Route::get('showmore_bestbox_dashboard', 'VideoController@showMoreFetchBestBoxRecords'); //vinod
        
        // CLEAR CACHE
        Route::get('cache_clear', 'VideoController@clearData');
        Route::get('static_response', 'VideoController@staticResponse');

        //vinod
        Route::get('livetv_country_list', 'VideoController@getLivetvCountryList');

        //Continue Watching list API's
        Route::get('getContinuewatchList', 'VideoController@getContinuewatchList');
        Route::post('update_video_duration', 'VideoController@updateVideoDuration');

    });
});
