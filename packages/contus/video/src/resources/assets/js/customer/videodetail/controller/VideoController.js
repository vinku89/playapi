( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'VideoDetailController', ['$http','$sce','$filter','$rootScope','requestFactory','$stateParams','$scope','ngToast','data','relateddata','$state',function ( $http, $sce, $filter, $rootScope, requestFactory, $stateParams, $scope, ngToast, data, relateddata, $state ) {
        var successResponseData;
        $scope.showmoretext = 1;
        $scope.pCommentStatus = 0;
        $scope.PostQuestionstatus = 0;
        var dataBinder = function ( response ) {
            $scope.subscription = response.subscription;
            $scope.tags = response.videos.tags;
            $scope.category = response.videos.categories [0];
            $scope.videos = response.videos;
            $scope.exam = response.exam;
            $scope.randsub = $scope.subscription.data [Math.floor( ( Math.random() * $scope.subscription.data.length ) + 0 )];
        };
        $scope.passclass = function ( pass ) {
            if ( $stateParams.video == pass ) {
                return true;
            } else if ( $stateParams.slug == pass ) {
                return true;
            } else {
                return false;
            }
        }
        $scope.passroute = function ( pass ) {
            if ( $scope.passclass( pass ) ) {
                return false;
            }
            if ( angular.isObject( $scope.live ) && $scope.live.total ) {
                $state.go( 'liveDetail', {slug : pass}, {reload : true} );
            } else {
                $state.go( 'videoDetail', {slug : pass}, {reload : true} );
            }
        }
        $scope.related = [];

        $scope.stateparams = $stateParams;
        var dataBinderrelated = function ( response ) {
            if ( angular.isObject( response.playlist ) ) {
                $scope.playlist = response.playlist;
                response = response.videos;
            } else if ( angular.isObject( response.all_live_videos ) ) {
                $scope.live = response.all_live_videos;
                response = response.all_live_videos;
            }
            $scope.related = response;
            setTimeout( function () {
                $( '.scrollfinderrelated' ).mCustomScrollbar( 'update' );
                setTimeout( function () {
                    if ( document.querySelector( 'div.media.active' ) !== null ) {
                        var off = document.querySelector( 'div.media.active' ).offsetTop;
                        $( '.scrollfinderrelated' ).mCustomScrollbar( "scrollTo", off, {scrollEasing : "easeOut",scrollInertia : 180} );
                    }
                }, 500 );
            }, 500 );
        };
        dataBinder( data.data.response );
        dataBinderrelated( relateddata.data.response );

        var success = function ( success ) {
            dataBinderrelated( success.response );
        };
        var fail = function ( fail ) {
            return fail;
        };
        $scope.toggleSelectionTags = function ( tog ) {
            sessionStorage.tag = tog;
            $state.go( 'categoryvideos', {'slug' : $scope.category.parent_category.parent_category.slug}, {reload : true} )
        };
        $scope.loadmorerelatedcategory = function () {
            if ( $scope.related.next_page_url !== null ) {
                $http( {method : 'POST',url : $scope.related.next_page_url,headers : requestFactory.getHeaders(),data : {},ignoreLoadingBar: true} ).then( function ( r ) {
                    dataBinderrelated( r.data.response );
                }, function () {
                } );
            }
        }
        $scope.loadmorerelatedcategory();
        $http( {method : 'get',url : requestFactory.getUrl( 'recommended' ),headers : requestFactory.getHeaders(),ignoreLoadingBar: true} ).then( function ( r ) {
            $scope.recommended = r.data.response;
        }, function () {
        } );
    }] )

} )();