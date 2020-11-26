var appRoute=angular.module("app.routes");(function(){'use strict';appRoute.config(['$stateProvider','$urlRouterProvider','$ocLazyLoadProvider',function($stateProvider,$urlRouterProvider,$ocLazyLoadProvider){$ocLazyLoadProvider.config({debug:false,modules:[{name:'dashboard',files:['contus/customer/js/auth/controller/DashboardController.js?v='+version,],serie:true},{name:'profile',files:['contus/customer/js/myaccount/controller/myAccountController.js?v='+version,],},{name:'videos',files:['contus/video/js/customer/video/controller/VideoController.js?v='+version,],serie:true},{name:'category',files:['contus/video/js/customer/video/controller/CategoryListController.js?v='+version,],serie:true},{name:'profile',files:['contus/customer/js/myaccount/controller/myAccountController.js?v='+version,],serie:true},{name:'password',files:['contus/customer/js/myaccount/controller/changePasswordController.js?v='+version,],serie:true},{name:'forgotpasswordreset',files:['contus/customer/js/auth/controller/ForgotController.js?v='+version,],serie:true},{name:'notifications',files:['contus/customer/js/myaccount/controller/changePasswordController.js?v='+version,],serie:true},{name:'favourite',files:['contus/customer/js/myaccount/controller/favouriteController.js?v='+version,],serie:true},{name:'following',files:['contus/customer/js/myaccount/controller/followController.js?v='+version,],serie:true},{name:'subscription',files:['contus/customer/js/myaccount/controller/subscriptionController.js?v='+version,],serie:true}]});$urlRouterProvider.otherwise('/');$stateProvider.state("dashboard",{url:"/",controller:'dashboardController',controllerAs:'dashCtrl',templateUrl:'dashboard',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('homeWeb'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('dashboard').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}}).state("resetpassword",{url:"/forgotpassword/:slug",controller:'forgotController',templateUrl:function(urlattr){return'forgotPassword/'+urlattr.slug;},resolve:{loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('forgotpasswordreset').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("profile",{url:"/profile",controller:'myAccountController',controllerAs:'accountCtrl',templateUrl:'myprofile',access:'login',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('profile'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('profile').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("subscriptions",{url:"/subscriptions",controller:'subscriptionController',controllerAs:'subCtrl',templateUrl:'subscriptions',access:'login',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('profile'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};['contus/base/js/gridView.js?v='+version,'contus/customer/js/myaccount/controller/subscriptionController.js?v='+version,].forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("subscribeinfo",{url:"/subscribeinfo",controller:'myAccountController',controllerAs:'accountCtrl',templateUrl:'subscribeinfo',access:'login',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('profile'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('profile').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("password",{url:"/password",controller:'changePasswordController',controllerAs:'passwordCtrl',templateUrl:'password',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('profile'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('password').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("editProfile",{url:"/editProfile",controller:'myAccountController',controllerAs:'profileCtrl',templateUrl:'editProfile',access:'login',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('profile'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('profile').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("favourites",{url:"/favourites",controller:'favouritesController',controllerAs:'favouritesCtrl',templateUrl:'favourites',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('profile'),{headers:requestFactory.getHeaders()});},favourites:function(requestFactory,$http){return $http.get(requestFactory.getUrl('favourite'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('favourite').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("following",{url:"/following",controller:'followController',templateUrl:'following',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('profile'),{headers:requestFactory.getHeaders()});},playlists:function(requestFactory,$http){return $http.get(requestFactory.getUrl('playlists'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('following').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});$stateProvider.state("subscription",{url:"/",controller:'subscriptionController',controllerAs:'subCtrl',templateUrl:'subscrptionForm',resolve:{data:function(requestFactory,$http){return $http.get(requestFactory.getUrl('subscriptions'),{headers:requestFactory.getHeaders()});},loadCtrl:['$ocLazyLoad',function($ocLazyLoad){var load={serie:true,files:[],cache:true};$ocLazyLoad.getModuleConfig('subscription').files.forEach(function(files){load.files.push(files);});if(load.files.length){return $ocLazyLoad.load(load);}}],}});}])})();
var version  = document.querySelector('meta[name="assertversion"]').content;
( function () {
    'use strict';
    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $ocLazyLoadProvider.config( {
                    debug : false,
                    modules : [
                            // ----------- wysihtml5 ELEMENTS -----------
                            {
                                name : 'videos',
                                files : [
                                        'contus/video/js/customer/video/controller/VideoController.js?v='+version,
                                ],
                                serie : true
                            }, {
                                name : 'category',
                                files : [
                                        'contus/video/js/customer/video/controller/CategoryListController.js?v='+version,
                                ],
                                serie : true
                            }, {
                                name : 'videodetailall',
                                files : [
                                    'contus/video/js/customer/videodetail/controller/VideoControl.js?v='+version,'contus/video/js/customer/videodetail/controller/VideoController.js?v='+version
                                ],
                                serie : true
                            }, {
                                name : 'videodetail',
                                files : ['contus/video/js/customer/videodetail/controller/VideoController.js?v='+version],
                                serie : true
                            }, {
                                name : 'playlist',
                                files : [
                                        'contus/video/js/customer/playlists/controller/PlaylistController.js?v='+version,
                                ],
                                serie : true
                            },{
                                name : 'livevideos',
                                files : [
                                        'contus/video/js/customer/livevideos/controller/LivevideoController.js?v='+version,
                                ],
                                serie : true
                            }, {
                                name : 'examlistingpage',
                                files : [
                                        'contus/video/js/customer/exams/controller/ExamListContoller.js?v='+version,
                                ],
                                serie : true
                            }, {
                                name : 'playlistdetail',
                                files : [
                                        'contus/video/js/customer/playlists/controller/PlaylistDetailController.js?v='+version,
                                ],
                                serie : true
                            }, {
                                name : 'forgotpassword',
                                files : [
                                        'contus/video/js/customer/forgotpassword/controller/ForgotpasswordController.js?v='+version,
                                ],
                                serie : true
                            }
                    ]
                } );
                $stateProvider.state( "category", {
                    url : "/category",
                    controller : 'CategoryListController',
                    templateUrl : 'listCategories',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'getCategoriesNavList' ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'category' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "categorysection", {
                    url : "/section/:category/:slug",
                    controller : function ( $stateParams, $state ) {
                        sessionStorage.category = $stateParams.slug;
                        $state.go( 'categoryvideos', {
                            'slug' : $stateParams.category
                        }, {
                            reload : true
                        } )
                    }
                } ).state( "categoryvideos", {
                    url : "/videos/:slug",
                    controller : 'VideoController',
                    controllerAs : 'videoCtrl',
                    templateUrl : 'allvideos',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'videos' ), {
                                'main_category' : $stateParams.slug,
                                'tag' : sessionStorage.tag,
                                'search' : $rootScope.fields.search,
                                'category' : sessionStorage.category
                            }, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videos' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "videoDetail", {
                    url : "/video-detail/:slug",
                    views: {
                        '':{templateUrl : 'videodetail',
                            controller : 'VideoDetailController'},
                        'examdetail@videoDetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        relateddata : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/related/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetailall' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }} ).state( "liveDetail", {
                    url : "/live/:slug",
                    views: {
                        '':{templateUrl : 'videodetail',
                            controller : 'VideoDetailController'},
                        'examdetail@liveDetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        relateddata : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'getAllLiveVideos' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetailall' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "playlist", {
                    url : "/playlist",
                    controller : 'PlaylistController',
                    controllerAs : 'playCtrl',
                    templateUrl : 'allPlaylists',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'playlist' ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'playlist' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "playlistList", {
                    url : "/playlist/:slug",
                    controller : 'ExamListContoller',
                    templateUrl : 'playlistlistdetail',
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/playlist/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                    '$ocLazyLoad', function ( $ocLazyLoad ) {
                        var load = {
                            serie : true,
                            files : [],
                            cache : true
                        };
                        /**
                         * init the dependencies array
                         * 
                         * @type {Array}
                         */
                        $ocLazyLoad.getModuleConfig( 'examlistingpage' ).files.forEach( function ( files ) {
                            load.files.push( files );
                        } );
                        /**
                         * check if the lazy load data exists
                         */
                        if ( load.files.length ) {
                            return $ocLazyLoad.load( load );
                        }
                    }]
                    }
                } ).state( "playlistdetail", {
                    parent:'playlistList', 
                    url : "/:video",
                    views: {
                        'examdetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.video ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetailall' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "examList", {
                    url : "/examgroup/:slug",
                    controller : 'ExamListContoller',
                    templateUrl : 'grouplistdetail',
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'group/' + $stateParams.slug ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                    loadCtrl : [
                        '$ocLazyLoad', function ( $ocLazyLoad ) {
                            var load = {
                                serie : true,
                                files : [],
                                cache : true
                            };
                            /**
                             * init the dependencies array
                             * 
                             * @type {Array}
                             */
                            $ocLazyLoad.getModuleConfig( 'examlistingpage' ).files.forEach( function ( files ) {
                                load.files.push( files );
                            } );
                            /**
                             * check if the lazy load data exists
                             */
                            if ( load.files.length ) {
                                return $ocLazyLoad.load( load );
                            }
                        }
                ]
                    }
                } ).state( "examdetail", {
                    parent:'examList', 
                    url : "/:video",
                    views: {
                        'examdetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.video ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetailall' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "livevideos", {
                    url : "/livevideos",
                    controller : 'LivevideoController',
                    controllerAs : 'liveCtrl',
                    templateUrl : 'livevideos',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'livevideos' ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'livevideos' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } );
            }
    ] )

} )();


( function () {
    'use strict';
    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $stateProvider.state( "notifications", {
                    url : "/notifications",
                    controller : 'notificationController',
                    controllerAs : 'notificationCtrl',
                    templateUrl : 'notifications',
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        notification : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'notifications' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    [
                                            'contus/notification/js/myaccount/controller/notificationController.js?v='+version,
                                    ].forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } );
            }
    ] )

} )();

( function () {
    'use strict';
    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $ocLazyLoadProvider.config( {
                    debug : false,
                    modules : [
                            // ----------- wysihtml5 ELEMENTS -----------
                            {
                                name : 'content',
                                files : [
                                        'contus/cms/js/staticcontent/controller/StaticContentController.js?v='+version,
                                ],
                                serie : true
                            },
							{
                                name : 'blog',
                                files : [
                                        'contus/cms/js/latestnews/controller/blogController.js?v='+version,
                                ],
                                serie : true
                            },
                            {
                                name : 'mobileblog',
                                files : [
                                        'contus/base/css/mobile.css?v='+version,
                                ],
                                serie : true
                            },
							{
                                name : 'blogdetail',
                                files : [
                                        'contus/cms/js/latestnews/controller/blogDetailController.js?v='+version,
                                ],
                                serie : true
                            },
                    ]
                } );
              
                $stateProvider.state( "staticContent", {
                    url : "/content/:slug",
                    controller : 'StaticContentController',
                    controllerAs : 'contentCtrl',
                    templateUrl : 'staticContentTemplate',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'staticcontent/' + $stateParams.slug), {
                                headers : requestFactory.getHeaders()
                            });
                        },
                        loadCtrl : [
                                    '$ocLazyLoad', function ( $ocLazyLoad ) {
                                        var load = {
                                            serie : true,
                                            files : [],
                                            cache : true
                                        };
                                        /**
                                         * init the dependencies array
                                         * 
                                         * @type {Array}
                                         */
                                        $ocLazyLoad.getModuleConfig( 'content' ).files.forEach( function ( files ) {
                                            load.files.push( files );
                                        } );
                                        /**
                                         * check if the lazy load data exists
                                         */
                                        if ( load.files.length ) {
                                            return $ocLazyLoad.load( load );
                                        }
                                    }
                            ],
                    }
                } );
                $stateProvider.state( "staticContent.mobile", {
                    url : "/mobile",
                    resolve : {
                        loadCtrl : [
                                    '$ocLazyLoad', function ( $ocLazyLoad ) {
                                        var load = {
                                            serie : true,
                                            files : [],
                                            cache : true
                                        };
                                        /**
                                         * init the dependencies array
                                         * 
                                         * @type {Array}
                                         */
                                        $ocLazyLoad.getModuleConfig( 'mobileblog' ).files.forEach( function ( files ) {
                                            load.files.push( files );
                                        } );
                                        /**
                                         * check if the lazy load data exists
                                         */
                                        if ( load.files.length ) {
                                            return $ocLazyLoad.load( load );
                                        }
                                    }
                            ],
                    }
                } );
               $stateProvider.state( "blog", {
                    url : "/blog",
                    controller : 'blogController',
                    controllerAs : 'blogCtrl',
                    templateUrl : 'blog',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'blog'), {
                                headers : requestFactory.getHeaders()
                            });
                        },
                        loadCtrl : [
                                    '$ocLazyLoad', function ( $ocLazyLoad ) {
                                        var load = {
                                            serie : true,
                                            files : [],
                                            cache : true
                                        };
                                        /**
                                         * init the dependencies array
                                         * 
                                         * @type {Array}
                                         */
                                        $ocLazyLoad.getModuleConfig( 'blog' ).files.forEach( function ( files ) {
                                          load.files.push( files );
                                        } );
                                        /**
                                         * check if the lazy load data exists
                                         */
                                        if ( load.files.length ) {
                                            return $ocLazyLoad.load( load );
                                        }
                                    }
                            ],
                    }
                } );
               $stateProvider.state( "blog.mobile", {
                   url : "/mobile",
                   resolve : {
                       loadCtrl : [
                                   '$ocLazyLoad', function ( $ocLazyLoad ) {
                                       var load = {
                                           serie : true,
                                           files : [],
                                           cache : true
                                       };
                                       /**
                                        * init the dependencies array
                                        * 
                                        * @type {Array}
                                        */
                                       $ocLazyLoad.getModuleConfig( 'mobileblog' ).files.forEach( function ( files ) {
                                         load.files.push( files );
                                       } );
                                       /**
                                        * check if the lazy load data exists
                                        */
                                       if ( load.files.length ) {
                                           return $ocLazyLoad.load( load );
                                       }
                                   }
                           ],
                   }
               } );
$stateProvider.state( "blogdetail", {
                    url : "/blogdetails/:slug",
                    controller : 'blogDetailController',
                    controllerAs : 'blogdetCtrl',
                    templateUrl : 'blogdetail',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'blogdetail/' +$stateParams.slug), {
                                headers : requestFactory.getHeaders()
                            });
                        },
                        loadCtrl : [
                                    '$ocLazyLoad', function ( $ocLazyLoad ) {
                                        var load = {
                                            serie : true,
                                            files : [],
                                            cache : true
                                        };
                                        /**
                                         * init the dependencies array
                                         * 
                                         * @type {Array}
                                         */
                                        $ocLazyLoad.getModuleConfig( 'blogdetail' ).files.forEach( function ( files ) {
                                          load.files.push( files );
                                        } );
                                        /**
                                         * check if the lazy load data exists
                                         */
                                        if ( load.files.length ) {
                                            return $ocLazyLoad.load( load );
                                        }
                                    }
                            ],
                    }
                } );
            }
    ] )

} )();

( function () {
    'use strict';

    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $stateProvider.state( "transactions", {
                    url : "/transactions",
                    controller : 'transactionController',
                    controllerAs : 'transactionCtrl',
                    templateUrl : 'transactions',
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    [
                                            'contus/base/js/gridView.js?v='+version, 'contus/payment/js/myaccount/controller/transactionController.js?v='+version,

                                    ].forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } );

                $stateProvider.state( "subscribeinfos", {
                    url : "/subscribeinfos",
                    controller : 'transactionController',
                    controllerAs : 'transactionCtrl',
                    templateUrl : 'subscribeinfos',
                    access : 'login',
                    resolve : {
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    [
                                            'contus/payment/js/myaccount/controller/transactionController.js?v='+version,
                                    ].forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ); 
                $stateProvider.state( "paymentsuccess", {
                    url : "/paymentsuccess/:slug",
                    templateUrl: function(urlattr){
                        return 'paymentsuccess/' + urlattr.slug;
                    },
                } ); $stateProvider.state( "paymentfailure", {
                    url : "/paymentfailure/:slug",
                    templateUrl: function(urlattr){
                        return 'paymentfailure/' + urlattr.slug;
                    },
                } ); $stateProvider.state( "paymentcancel", {
                    url : "/paymentcancel/:slug",
                    controller : 'transactionController',
                    controllerAs : 'transactionCtrl',
                    templateUrl: function(urlattr){
                        return 'paymentcancel/' + urlattr.slug;
                    },
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    [
                                            'contus/base/js/gridView.js?v='+version, 'contus/payment/js/myaccount/controller/transactionController.js?v='+version,

                                    ].forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } );
            }
    ] )

} )();