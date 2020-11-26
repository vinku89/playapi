<?php

/**
 * @SWG\Info(
 *      version="1.0.0",
 *      title="BestBox",
 *      description="List of API used in BestBox Website, iOS and Android Apps",
 *      @SWG\Contact(
 *          email="ashok.b@contus.in"
 *      ),
 *     @SWG\License(
 *         name="BestBox",
 *         url="https://vplayed.demo.contus.us/"
 *     )
 * )
 */

/**
 * @SWG\Swagger(
 *      host=L5_SWAGGER_CONST_HOST,
 *      schemes={"http", "https"},
 *      @SWG\SecurityScheme(
 *         securityDefinition="bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     )
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/auth/login",
 *     tags={"Login & SignUp"},
 *     operationId="login",
 *     summary="Annotation is login user into application",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="ashok.b@contus.in",
 *    ),
 *     @SWG\Parameter(
 *         name="password",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         description=""
 *    ),
 *    @SWG\Parameter(
 *         name="login_type",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         description="normal - normal email and password login, fb - Facebook login, google+ - google plus login",
 *         default="normal",
 *    ),
 *    @SWG\Parameter(
 *         name="acesstype",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         description="Mobile or website or admin",
 *         default="web",
 *    ),
 *    @SWG\Parameter(
 *         name="social_user_id",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="Socail Media ID",
 *    ),
 *    @SWG\Parameter(
 *         name="token",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="device_token",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="device_type",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */

/**
 * @SWG\Post(
 *     path="/api/v2/auth/register",
 *     tags={"Login & SignUp"},
 *     operationId="register",
 *     summary="Annotation is login user into application",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="ashok.b@contus.in",
 *    ),
 *     @SWG\Parameter(
 *         name="password",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         description=""
 *    ),
 *    @SWG\Parameter(
 *         name="login_type",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         description="normal - normal email and password login, fb - Facebook login, google+ - google plus login",
 *         default="normal",
 *    ),
 *    @SWG\Parameter(
 *         name="acesstype",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         description="Mobile or website or admin",
 *         default="web",
 *    ),
 *    @SWG\Parameter(
 *         name="social_user_id",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="Socail Media ID",
 *    ),
 *    @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="phone",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="profile_picture",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="token",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="device_token",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="device_type",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Parameter(
 *         name="age",
 *         in="formData",
 *         required=false,
 *         type="string",
 *         description="",
 *    ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *    security={
 *      {"bearer": {}}
 *    }
 * )
 */

/**
 * @SWG\Post(
 *     path="/api/v2/auth/forgotpassword",
 *     tags={"Login & SignUp"},
 *     operationId="forgot_password",
 *     summary="Annotation is request for forgot password",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="ashok.b@contus.in",
 *    ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */

/**
 * @SWG\Post(
 *     path="/api/v2/auth/resetpassword",
 *     tags={"Login & SignUp"},
 *     operationId="reset_password",
 *     summary="Annotation is request for forgot password",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="ashok.b@contus.in",
 *    ),
 *    @SWG\Parameter(
 *         name="token",
 *         in="formData",
 *         required=true,
 *         type="string",
 *    ),
 *    @SWG\Parameter(
 *         name="password",
 *         in="formData",
 *         required=true,
 *         type="string",
 *    ),
 *    @SWG\Parameter(
 *         name="password_confirmation",
 *         in="formData",
 *         required=true,
 *         type="string",
 *    ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/auth/iosLogout",
 *     tags={"Login & SignUp"},
 *     operationId="ios_logout",
 *     summary="Annotation is logout from ios",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/auth/device_token",
 *     tags={"Login & SignUp"},
 *     operationId="update_device_token",
 *     summary="Annotation is update device token",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="device_type",
 *          description="device type",
 *          in="formData",
 *          type="string",
 *     ),
 *     @SWG\Parameter(
 *          name="device_token",
 *          description="device token",
 *          in="formData",
 *          type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */

/**
 * @SWG\Get(
 *     path="/api/v2/home_page",
 *     tags={"Home Page"},
 *     operationId="home_page",
 *     summary="Annotation is used to load home page contents",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/home_more",
 *     tags={"Home Page"},
 *     operationId="home_page_more",
 *     summary="Annotation is used to load more home page contents",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="mobile",
 *     ),
 *     @SWG\Parameter(
 *         name="type",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="new",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/searchvideos",
 *     tags={"Search"},
 *     operationId="search_videos",
 *     summary="Annotation is used to search the content in home page",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *         name="search",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *     @SWG\Parameter(
 *         name="age",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/videos",
 *     tags={"Home Page"},
 *     operationId="post_new_videos",
 *     summary="Annotation is used for upload new videos",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *  @SWG\Parameter(
 *          name="slug",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     ),
 *     @SWG\Parameter(
 *          name="device_type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="IOS",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/home_page_banner",
 *     tags={"Home Page"},
 *     operationId="home_page_banner",
 *     summary="Annotation is used to search the content in home page",
 *     consumes={"multipart/form-data"},
  *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="x-language-code",
 *          description="Language Code",
 *          in="header",
 *          type="string",
 *          default="en",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/home_category_videos",
 *     tags={"Home Page"},
 *     operationId="home_category_videos",
 *     summary="Annotation is used to load  home page with default categories",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="x-language-code",
 *          description="Language Code",
 *          in="header",
 *          type="string",
 *          default="en",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Request Response",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *         name="section",
 *         in="query",
 *         required=false,
 *         type="string",
 *         default="2",
 *     ),
 *     @SWG\Parameter(
 *         name="is_web_series",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="0",
 *     ),
 *     @SWG\Parameter(
 *         name="category",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="movies",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/more_category_videos",
 *     tags={"Home Page"},
 *     operationId="more_category_videos",
 *     summary="Annotation is used to load  home page with more categories",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Request Response",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *      @SWG\Parameter(
 *         name="type",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="new",
 *     ),
 *     @SWG\Parameter(
 *         name="page",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="1",
 *     ),
 *     @SWG\Parameter(
 *         name="is_web_series",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="0",
 *     ),
 *     @SWG\Parameter(
 *         name="category",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="movies",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/category_videos",
 *     tags={"Home Page"},
 *     operationId="category_videos",
 *     summary="Annotation is used to load  home page with more categories",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Request Response",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *      @SWG\Parameter(
 *         name="section",
 *         in="query",
 *         required=false,
 *         type="string",
 *         default="",
 *     ),
 *     @SWG\Parameter(
 *         name="page",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="1",
 *     ),
 *     @SWG\Parameter(
 *         name="is_web_series",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="0",
 *     ),
 *     @SWG\Parameter(
 *         name="category",
 *         in="query",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/search/videos",
 *     tags={"Search"},
 *     operationId="search_videos_with_more_categories",
 *     summary="Annotation is used to load  home page with more categories",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Request Response",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *      @SWG\Parameter(
 *         name="q",
 *         in="query",
 *         required=true,
 *         type="string",
 *         default="",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */

/**
 * @SWG\Post(
 *     path="/api/v2/videos/",
 *     tags={"Video Detail"},
 *     operationId="post_new_videos",
 *     summary="Annotation is used for view new videos",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-language-code",
 *          description="Language Code",
 *          in="header",
 *          type="string",
 *          default="en",
 *     ),
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="slug",
 *          description="Video Details",
 *          in="path",
 *          type="string",
 *     ),
 *     @SWG\Parameter(
 *          name="device_type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="IOS",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/videos",
 *     tags={"Video Detail"},
 *     operationId="videos_with_playlist",
 *     summary="Annotation is used to load  video detail page with playlist",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 * 
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/recentlyViewed",
 *     tags={"Video Detail"},
 *     operationId="track_video_watch",
 *     summary="Annotation is used for track recently viewed videos",
 *     consumes={"multipart/form-data"},
 *    @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\SecurityScheme(
 *         securityDefinition="bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *    ),
 *     @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="1",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/videosRelatedTrending",
 *     tags={"Video Detail"},
 *     operationId="related_trending_videos",
 *     summary="Annotation is used for view related trending videos",
 *     consumes={"multipart/form-data"},
 *    @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="1",
 *     ),
 *     @SWG\Parameter(
 *         name="id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="",
 *     ),
 *     @SWG\Parameter(
 *         name="type",
 *         in="formData",
 *         required=true,
 *         type="string",
 *         default="related",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */

/**
 * @SWG\Get(
 *     path="/api/v2/watchvideo/",
 *     tags={"Video Detail"},
 *     operationId="watch_video",
 *     summary="Annotation is used to watch videos",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="referer",
 *          description="",
 *          in="header",
 *          type="string",
 *          default="adf",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/favourite",
 *     tags={"Favourite"},
 *     operationId="make_favourite",
 *     summary="Annotation is used to mark videos as favorite",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/favourite",
 *     tags={"Favourite"},
 *     operationId="make_favourite",
 *     summary="Annotation is used to mark videos as favorite",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Delete(
 *     path="/api/v2/favourite",
 *     tags={"Favourite"},
 *     operationId="make_favourite",
 *     summary="Annotation is used to mark videos as unfavorite",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/videoComments",
 *     tags={"Comments"},
 *     operationId="add_comments",
 *     summary="Annotation is used to add comments for video",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *         name="video_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="parent_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="comment",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/comments/delete/{comment}",
 *     tags={"Comments"},
 *     operationId="delete_video_comments",
 *     summary="Annotation is used to delete comments for video",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *         name="comment_id",
 *         in="path",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *    security={
 *           {"bearer": {}}
 *    }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/getCategoryForNav",
 *     tags={"Category"},
 *     operationId="category_for_nav",
 *     summary="Annotation is used to fetch video category",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/admin/categories/records",
 *     tags={"Category"},
 *     operationId="category_for_nav",
 *     summary="Annotation is used to fetch category records",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="X-CSRF-TOKEN",
 *          description="CSRF Token",
 *          in="header",
 *          type="string",
 *     ),
 *     @SWG\Parameter(
 *          name="X-PUBLIC-ACCESS-TOKEN",
 *          description="Public access token",
 *          in="header",
 *          type="string",
 *     ),
 *    @SWG\Parameter(
 *          name="X-USER-ID",
 *          description="user id",
 *          in="header",
 *          type="string",
 *     ),
 *    @SWG\Parameter(
 *          name="X-WEB-SERVICE",
 *          description="Web Service",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="X-XSRF-TOKEN",
 *          description="XSRF Token",
 *          in="header",
 *          type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/category_list",
 *     tags={"Category"},
 *     operationId="category_list",
 *     summary="Annotation is used to list video category",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/childWebseries/web-series",
 *     tags={"Category"},
 *     operationId="webseries_list",
 *     summary="Annotation is used to Child Webseries video",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *  *    @SWG\Parameter(
 *          name="page",
 *         
 *          in="header",
 *          type="string",
 *        
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/webseason_videos/{slug}/{season}",
 *     tags={"Category"},
 *     operationId="webseries_list_slug",
 *     summary="Annotation is used to  Webseries season Filter",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="slug",
 *          description="Web Series Slug",
 *          in="path",
 *          type="string",
 *          required=true
 *     ),
 *     @SWG\Parameter(
 *          name="season",
 *          description="Web Series season",
 *          in="path",
 *          type="string",
 *          required=true
 *     ),
 *     @SWG\Parameter(
 *          name="page",
 *         
 *          in="header",
 *          type="string",
 *        
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/webseries/{slug}",
 *     tags={"Category"},
 *     operationId="category_lists",
 *     summary="Annotation is used to  Webseries detail page",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="slug",
 *          description="Web Series Slug",
 *          in="path",
 *          type="string",
 *          required=true
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/staticContent/contactus",
 *     tags={"CMS"},
 *     operationId="add_contact_us",
 *     summary="Annotation is used to get contact details",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/staticcontent/contact-us",
 *     tags={"CMS"},
 *     operationId="get_contact_us",
 *     summary="Annotation is used to get contact details in list",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/getsiteaddress",
 *     tags={"CMS"},
 *     operationId="contact_us_setting",
 *     summary="Annotation is used to get Site Address",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/footer",
 *     tags={"CMS"},
 *     operationId="contact_us_footer",
 *     summary="Annotation is used to get list",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *          name="x-language-code",
 *          description="Language",
 *          in="header",
 *          type="string",
 *          default="en",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/subscriptions",
 *     tags={"Subscriptions"},
 *     operationId="subscriptions_list",
 *     summary="Annotation is used to get list of subscribers",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *          name="x-language-code",
 *          description="Language",
 *          in="header",
 *          type="string",
 *          default="en",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/subscription/add",
 *     tags={"Subscriptions"},
 *     operationId="Add_new_subscription",
 *     summary="Annotation is used to add new subscribers",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Delete(
 *     path="/api/v2/unsubscription",
 *     tags={"Subscriptions"},
 *     operationId="unsubscribe",
 *     summary="Annotation is used to unsubscriber",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/notifications",
 *     tags={"Notification"},
 *     operationId="notification_list",
 *     summary="Annotation is used to get list of Notification",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/notify",
 *     tags={"Notification"},
 *     operationId="post_new_notification",
 *     summary="Annotation is used to get post new Notification",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/notification/settings",
 *     tags={"Notification"},
 *     operationId="post_new_notification",
 *     summary="Annotation is used set or modify notifcation setting",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/notification/read_all",
 *     tags={"Notification"},
 *     operationId="read_all_notification",
 *     summary="Annotation is used read all notifications",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/notification/read",
 *     tags={"Notification"},
 *     operationId="read_new_notification",
 *     summary="Annotation is used read notifications",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *          name="id",
 *          description="Notification ID",
 *          in="path",
 *          type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/notification/remove",
 *     tags={"Notification"},
 *     operationId="remove_new_notification",
 *     summary="Annotation is used remove  notifications",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *          name="id",
 *          description="Notification ID",
 *          in="path",
 *          type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/notification/remove_all",
 *     tags={"Notification"},
 *     operationId="remove_all_notification",
 *     summary="Annotation is used remove all notifications",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/notification/clear",
 *     tags={"Notification"},
 *     operationId="clear_all_notification",
 *     summary="Annotation is used clear all notifications",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/create_playlist",
 *     tags={"User Playlist"},
 *     operationId="user_play_list",
 *     summary="Annotation is used create playlist for users",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/create_playlist",
 *     tags={"User Playlist"},
 *     operationId="user_play_list_listing",
 *     summary="Annotation is used list all playlist",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *         name="video_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/create_playlist_videos",
 *     tags={"User Playlist"},
 *     operationId="playlist_videos",
 *     summary="Annotation is used list videos playlist",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *         name="playlist_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Delete(
 *     path="/api/v2/create_playlist_videos",
 *     tags={"User Playlist"},
 *     operationId="delete_playlist_videos",
 *     summary="Annotation is used delete playlist",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *         name="playlist_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="video",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Delete(
 *     path="/api/v2/cards",
 *     tags={"Cards"},
 *     operationId="delete_cards",
 *     summary="Annotation is used to delete cards",
 *     consumes={"multipart/form-data"},
 *    @SWG\Parameter(
 *         name="playlist_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="video",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/cards",
 *     tags={"Cards"},
 *     operationId="get_cards",
 *     summary="Annotation is used to list cards",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *         name="playlist_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="video",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/cards",
 *     tags={"Cards"},
 *     operationId="create_cards",
 *     summary="Annotation is used to create new cards",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *         name="playlist_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="video",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/key",
 *     tags={"Security"},
 *     operationId="create_cards",
 *     summary="Annotation is used to create new cards",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="Title",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Referer",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="X-DEVICE-TYPE",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *         name="key",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/playlists",
 *     tags={"Playlist"},
 *     operationId="favourite_user_play_list",
 *     summary="Annotation is used create favourite playlist for users",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Delete(
 *     path="/api/v2/playlists",
 *     tags={"Playlist"},
 *     operationId="delete_favourite_user_play_list",
 *     summary="Annotation is used to delete playlist from favourite",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/playlists",
 *     tags={"Playlist"},
 *     operationId="create_favourite_user_play_list",
 *     summary="Annotation is used to create  favourite playlist",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/playlist",
 *     tags={"Playlist"},
 *     operationId="list_all_playlist",
 *     summary="Annotation is used list all playlist for users",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *   @SWG\Parameter(
 *         name="type",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/videos/playlist",
 *     tags={"Playlist"},
 *     operationId="video_inside_playlist",
 *     summary="Annotation is used to create playlist inside videos",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *     @SWG\Parameter(
 *         name="device_type",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *     @SWG\Parameter(
 *          name="slug",
 *          description="Video Details",
 *          in="path",
 *          type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/live_more_videos",
 *     tags={"Live Videos"},
 *     operationId="live_more_videos",
 *     summary="Annotation is used load more live videos",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/livevideos",
 *     tags={"Live Videos"},
 *     operationId="live_more_videos",
 *     summary="Annotation is used load more live videos",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *   @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=false,
 *         type="string",
 *     ),
 *  *   @SWG\Parameter(
 *         name="country_id",
 *         in="formData",
 *         required=false,
 *         type="string",
 *     ),
 *  *   @SWG\Parameter(
 *         name="category",
 *         in="formData",
 *         required=false,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/customerProfile",
 *     tags={"Profile"},
 *     operationId="update_customer_profile",
 *     summary="Annotation is used to update customer profile",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *   @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *   @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *   @SWG\Parameter(
 *         name="phone",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *   @SWG\Parameter(
 *         name="acesstype",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *   @SWG\Parameter(
 *         name="profile_picture",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *   @SWG\Parameter(
 *         name="age",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/profile",
 *     tags={"Profile"},
 *     operationId="list_customer_profile",
 *     summary="Annotation is used to list customer profile",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Put(
 *     path="/api/v2/auth/change",
 *     tags={"Profile"},
 *     operationId="change_customer_password",
 *     summary="Annotation is used to password of customer",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/x-www-form-urlencoded",
 *     ),
 *    @SWG\Parameter(
 *         name="old_password",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="password",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Parameter(
 *         name="password_confirmation",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/videoQuestions",
 *     tags={"Video Questions"},
 *     operationId="video_questions",
 *     summary="Annotation is used questions for videos",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *         name="video_id",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/clear_recent_view",
 *     tags={"Clear Recent View"},
 *     operationId="clear_recent_view",
 *     summary="Annotation is used to clear recent view",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *     @SWG\Parameter(
 *          name="Content-Type",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *    @SWG\Parameter(
 *         name="page",
 *         in="formData",
 *         required=true,
 *         type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/transactions/records",
 *     tags={"Transaction Records"},
 *     operationId="transaction_records",
 *     summary="Annotation is used list transaction records",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/countries_list",
 *     tags={"Countries List"},
 *     operationId="list_countries_list",
 *     summary="Annotation is used to Countries list ",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *     @SWG\Parameter(
 *          name="Accept",
 *          description="Media Type",
 *          in="header",
 *          type="string",
 *          default="application/json",
 *     ),
 *  *     @SWG\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         type="string",
 *         default="",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Post(
 *     path="/api/v2/open",
 *     tags={"Security Offline Downloads"},
 *     operationId="security_offline_downloads",
 *     summary="Annotation is used for offline downloads security",
 *     consumes={"multipart/form-data"},
 *     @SWG\Parameter(
 *          name="x-request-type",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="web",
 *     ),
 *    @SWG\Parameter(
 *          name="X-DEVICE-TYPE",
 *          description="Platform",
 *          in="header",
 *          type="string",
 *          default="ios",
 *     ),
 *    @SWG\Parameter(
 *         name="key",
 *         in="path",
 *         required=true,
 *         type="string",
 *     ),
 *     @SWG\Parameter(
 *          name="id",
 *          description="Video Details",
 *          in="path",
 *          type="string",
 *     ),
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
/**
 * @SWG\Get(
 *     path="/api/v2/cache_clear",
 *     tags={"Clear Cache"},
 *     operationId="redis_cache_clear",
 *     summary="Annotation is used for clear cache",
 *      consumes={"multipart/form-data"},
 *    @SWG\Response(
 *         response=200,
 *         description="success",
 *    ),
 *    @SWG\Response(
 *         response="default",
 *         description="error",
 *    ),
 *       security={
 *           {"bearer": {}}
 *       }
 * )
 */
?>
