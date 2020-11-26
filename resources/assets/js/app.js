'use strict';

/*
 * Contalog Admin AngularJS
 */
( function () {

    "use strict";
    /**
     * Initiating the app
     */
    angular.module( "app", ["app.controllers","app.routes","app.directive","app.config","app.filter","app.services","flow"] );
    angular.module( "app.routes", ["ui.router","ui.bootstrap",'oc.lazyLoad',"ngAnimate"] ), angular.module( "app.controllers", ['djds4rce.angular-socialshare',"flow"] ), angular.module( "app.config", ["angular-loading-bar","ngToast"] ), angular.module( "app.factory", ['requestFactory'] ), angular.module( "app.filter", [] ), angular.module( "app.directive", [] );
    angular.module( "app.services", [] );
    /**
     * to set whitelist domain resource Url
     */
    angular.module( "app.config" ).config( ['$locationProvider','$sceDelegateProvider','$httpProvider','cfpLoadingBarProvider','ngToastProvider',function ( $locationProvider, $sceDelegateProvider, $httpProvider, cfpLoadingBarProvider, ngToastProvider ) {
        $locationProvider.html5Mode( true );
        $sceDelegateProvider.resourceUrlWhitelist( ['self',] );
        cfpLoadingBarProvider.includeSpinner = false; // Show the spinner.
        cfpLoadingBarProvider.includeBar = true; // Show the bar.
        ngToastProvider.configure( {dismissButton : true,animation : 'fade',dismissOnClick : true,verticalPosition : 'bottom',horizontalPosition : 'center',maxNumber : 1} );
        $httpProvider.interceptors.push( function ( $q, $rootScope ) {
            return {responseError : function ( res ) {
                if ( res.status == 403 ) {
                    window.location = $rootScope.currentUrl;
                }
                return $q.reject( res );
            },request : function ( request ) {
                return request
            },response : function ( response ) {
                return response
            }}
        } );
    }] ).run( function ( $rootScope ) {
        $rootScope.countdownlive = null;
        $rootScope.fields = {search : sessionStorage.search};
        $rootScope.pass = {name : '',email : ''};
    } ).run( function ( $FB ) {
        $FB.init( '160422307860254' );
    } ).run( function ( $rootScope, $location, $state, $stateParams ) {
        $rootScope.location = $location;
        $rootScope.httpCount = 0;
        $rootScope.httpLoaderLocalElement = 0;
        $rootScope.categoryFlag = 0;
        $rootScope.subcatslug = '';
        $rootScope.subCategoryClik = function () {
            $rootScope.categoryFlag = 1;
            $rootScope.subcatslug = '';
        }
        $rootScope.viewAllCource = function ( slug ) {
            $rootScope.subcatslug = slug;
            if ( $state.$current.self.name === 'categoryvideos' ) {
                $rootScope.$broadcast( 'reloadRoute' );
            }
        }
    } ).run( function ( $rootScope, $location, $anchorScroll, $window, $state, requestFactory, $uibModal, $uibModalStack, $ocLazyLoad, ngToast ) {
        $rootScope.login = function () {
            $uibModal.open( {templateUrl : 'loginModel?keyword=' + new Date(),keyboard : false,backdrop : 'static',animation : true,size : 'md',windowClass : 'login-popup text-center',controller : 'loginController',} );
        };
        $rootScope.signup = function () {
            $uibModal.open( {templateUrl : 'signUpModel?keyword=' + new Date(),keyboard : false,backdrop : 'static',animation : true,size : 'md',windowClass : 'signup-popup text-center',controller : 'signupController',} );
        };
        $rootScope.newpassword = function () {
            $uibModal.open( {templateUrl : 'newpasswordModel?keyword=' + new Date(),backdrop : 'static',keyboard : false,backdrop : 'static',animation : true,size : 'md',windowClass : 'login-popup text-center',controller : 'newpasswordController',} );
        };
        $rootScope.documentDownload = function ( $template ) {
            $uibModal.open( {template : $template,backdrop : 'static',keyboard : false,backdrop : 'static',animation : true,size : 'md',windowClass : 'downloading-options',} );
        };
        $rootScope.closeDownload = function () {
            $uibModalStack.dismissAll();
        }
        $rootScope.notavailables = function () {
            ngToast.create( {className : 'success',content : '<strong>Files not available at this moment </strong>'} );
        }
        $rootScope.closePopUp = function ( state ) {
            sessionStorage.nextURI = '';
            sessionStorage.nextURIParams = '';
            if ( ["login","signup","newpassword",''].indexOf( state.$current.self.name ) > -1 ) {
                $state.go( 'dashboard' );
            } else {
                $uibModalStack.dismissAll();
            }
        };
        $rootScope.$on( '$stateChangeStart', function ( event, toState, toParams, fromState, fromParams ) {
            $rootScope.httpCount = 1;
            $uibModalStack.dismissAll();
            if ( ( toState.name == "playlistList" && fromState.name == 'playlistdetail' ) || ( toState.name == "examList" && fromState.name == 'examdetail' ) ) {
                history.back();
            }
            if ( fromState.name == "categoryvideos" && toState.name == "categorysection" ) {
                if ( $rootScope.categoryFlag == 0 ) {
                    history.back();
                }
                $rootScope.categoryFlag = 0;
            }
            if ( toState.name === "dashboard" && fromState.name === '' && angular.isString(sessionStorage.nextURIParams) && angular.isString(sessionStorage.nextURI) && sessionStorage.nextURI!=='' && sessionStorage.nextURI != 'dashboard' ) {
                event.preventDefault();
                if(sessionStorage.nextURIParams!=='{}'){
                  $state.go( sessionStorage.nextURI,JSON.parse( sessionStorage.nextURIParams )  );
                }else{
                $state.go( sessionStorage.nextURI);
              }

            }
            if ( ["login","signup","newpassword"].indexOf( toState.name ) > -1 ) {
                if ( fromState.name !== 'dashboard' && fromState.name !== 'login' && fromState.name !== 'signup' && fromState.name !== 'newpassword' ) {
                    sessionStorage.nextURI = fromState.name;
                    sessionStorage.nextURIParams = JSON.stringify( fromParams );
                    event.preventDefault();
                    switch ( toState.name ) {
                        case 'login':
                            $rootScope.login();
                            $rootScope.httpCount = 0;
                            break;
                        case 'signup':
                            $rootScope.signup();
                            $rootScope.httpCount = 0;
                            break;
                        case 'newpassword':
                            $rootScope.newpassword();
                            $rootScope.httpCount = 0;
                            break;
                    }
                    ;
                }
            }
            if ( !requestFactory.isLoggedIn() && angular.isString( toState.access ) && toState.access !== '' ) {
                sessionStorage.nextURI = toState.name;
                sessionStorage.nextURIParams = JSON.stringify( toParams );
                event.preventDefault();
                if ( fromState.name === '' ) {
                    if ( toState.access === 'login' ) {
                        $rootScope.login();
                        $rootScope.httpCount = 0;
                    } else {
                        sessionStorage.nextURI = '';
                        sessionStorage.nextURIParams = '';
                        $state.go( 'dashboard' );
                    }
                } else {
                    $rootScope.login();
                    $rootScope.httpCount = 0;
                }
            }
        } )
        // when the route is changed scroll to the proper element.
        $rootScope.$on( '$stateChangeSuccess', function ( state, newRoute, routeParam, fromState, fromParams ) {
            $window.scrollTo( 0, 0 );
            if ( newRoute.name !== 'categoryvideos' ) {
                $rootScope.fields.search = '';
            }
            if ( newRoute.name !== 'signup' ) {
                $rootScope.pass = {name : '',email : ''};
            }
            if ( newRoute.name === sessionStorage.nextURI ) {
                sessionStorage.nextURI = '';
                sessionStorage.nextURIParams = '';
            }
            $rootScope.httpCount = 0;
        } );
    } );
} )();
