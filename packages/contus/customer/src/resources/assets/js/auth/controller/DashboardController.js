( function () {
    'use strict';
    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'initializeOwlCarousel', intializeOwlCarouselDirective );
    controller.controller( 'dashboardController', ['$sce','$scope','requestFactory','$state','$rootScope','data','$filter',function ( $sce, $scope, requestFactory, $state, $rootScope, data, $filter ) {
        $rootScope.httpLoaderLocalElement = 0;
        $scope.category = {};
        $scope.testimonial = {};
        var successResponseData;
        var dataBinder = function () {
            $scope.data = successResponseData.response;
            $scope.category = $scope.data.categories;
            $rootScope.bannerImage = $scope.data.video_image;
            $rootScope.bannerImage = $scope.data.banner_image;
            if ( $rootScope.bannerImage.type == 'video' ) {
                $rootScope.bannerImage.video_image = $sce.trustAsResourceUrl( $rootScope.bannerImage.video_image );
            } if ( $rootScope.bannerImage.type == 'image' ) {
                $rootScope.bannerImage.banner_image = $sce.trustAsResourceUrl( $rootScope.bannerImage.banner_image );
            }
            $rootScope.profileInfo = $scope.data.profileInfo;
            $scope.trending = $scope.data.trending;
            $scope.trendings = $scope.data.trending;
            $scope.live = $scope.data.live;
            $scope.exams = $scope.data.exams;
            $scope.latestnews = $scope.data.latestnews;
            $scope.testimonial = $scope.data.testimonials;
            $scope.totalstudent = $scope.data.total_number_of_active_customer;
            $scope.totalvideos = $scope.data.total_number_of_active_videos;
            $scope.totaldocs = $scope.data.total_number_of_active_pdfdocs;
            $scope.totalaudio = $scope.data.total_number_of_active_audio;
            requestFactory.toggleLoader();
        };
        successResponseData = data.data;
        dataBinder();
        $scope.$on( "triggerNextOwlcarosel", function ( evt, data ) {
            var key = "";
            $scope.loadmoreVideos( $scope.trending, data ['parent-slug'], key )
        } );
        $scope.loadmoreVideos = function ( category, slug ) {
            if ( category.next_page_url !== null ) {
                requestFactory.get( category.next_page_url, ( function ( response ) {
                    var temp = {};
                    temp = $scope.trending.data;
                    $scope.trending = response.response.trending;
                    $scope.trending.data = ( temp ) ? temp.concat( $scope.trending.data ) : $scope.trending.data;
                } ), fail );
            }
        }
        $scope.loadcategoryfromslug = function ( slug ) {
            var key = ( $filter( 'getByKey' )( $scope.trending, slug, 'slug', 'key' ) );
            if ( !angular.isObject( $scope.trending [key].child_category ) ) {
                $scope.trending [key].child_category = {data : []};
                requestFactory.post( requestFactory.getUrl( 'homeWeb' ), {'category' : slug,'type' : 'trending'}, ( function ( response ) {
                    $scope.trending [key].child_category = response.response.trending.category;
                } ), fail );
            }
        }
        $scope.loadexamfromslug = function ( slug ) {
            var key = ( $filter( 'getByKey' )( $scope.exams, slug, 'slug', 'key' ) );
            if ( !angular.isObject( $scope.exams [key].exams ) ) {
                $scope.exams [key].exams = [];
                requestFactory.post( requestFactory.getUrl( 'homeWeb' ), {'exam_id' : slug,'type' : 'exam'}, ( function ( response ) {
                    $scope.exams [key].exams = response.response.exams;
                } ), fail );
            }
        }
        setTimeout( function () {
            if ( angular.element( 'div.modal-backdrop.fade.in[uib-modal-animation-class="fade"][modal-in-class="in"]' ).length && $state.current.name === 'dashboard' ) {
                location.reload();
            } else {
                $rootScope.httpLoaderLocalElement = 0;
            }
        }, 500 );
        var success = function ( success ) {
            successResponseData = success;
            dataBinder();
        };
        var fail = function ( fail ) {
            return fail;
        };
        $scope.getCount = function ( subcategory ) {
            $scope.videoCount = 0;
            angular.forEach( subcategory, function ( value, key ) {
                var obj = value.videos;
                if ( obj ) {
                    $scope.videoCount = $scope.videoCount + Object.keys( obj ).length;
                }
            } );
            return $scope.videoCount;
        };
        $scope.showVideo = function () {
        };
        $scope.filter = function ( mainslug, slug ) {
            sessionStorage.category = '';
            angular.forEach( slug.child_category, function ( value, key ) {
                sessionStorage.category = ( sessionStorage.category === '' ) ? value.slug : ',' + value.slug;
            } );
            $state.go( 'categoryvideos', {'slug' : mainslug}, {reload : true} )
        }
        $scope.innertag = function ( cat ) {
            var ret = [];
            angular.forEach( cat, function ( sessions ) {
                angular.forEach( sessions.child_category, function ( videos ) {
                    ret = ret.concat( videos.videos );
                } )
            } )
            return ret;
        }
        $scope.examOwlCarouselOptions = {loop : false,dots : false,nav : true,margin : 15,autoplay : false,mouseDrag : true,responsive : {0 : {items : 1},500 : {items : 2},700 : {items : 3},992 : {items : 4,loop : false}}};
        $scope.videoOwlCarouselOptions = {loop : true,dots : false,nav : true,margin : 15,autoplay : true,mouseDrag : true,responsive : {0 : {items : 1},500 : {items : 2},700 : {items : 3},992 : {items : 4,loop : false}}};
        $scope.clientOwlCarouselOptions = {loop : true,nav : false,margin : 10,dots : true,autoplay : true,mouseDrag : true,pagination : true,responsive : {0 : {items : 1},600 : {items : 1},700 : {items : 1},991 : {items : 2,loop : false}}};
        $scope.newsOwlCarouselOptions = {autoPlay : false,nav : true,loop : false,dots : false,items : 3,margin : 30,pagination : false,responsive : {0 : {items : 1},500 : {items : 2},700 : {items : 3},992 : {items : 3,loop : false}}};
    }] );
} )();