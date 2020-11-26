$( document ).ready( function () {
    baseValidator.initateThroughJquery( $( 'form[name="staticContentForm"]' ), 'staticContentForm' ).setLocale( window.Mara.locale );
} );
'use strict';
var staticPage = angular.module( 'staticPage', ["ui"] );
staticPage.directive( 'baseValidator', validatorDirective );
staticPage.factory( 'requestFactory', requestFactory );
staticPage.controller( 'StaticController', ['$scope','$rootScope','requestFactory',function ( $scope, $rootScope, requestFactory ) {
    requestFactory.get( requestFactory.getUrl( 'staticContent/static-data/' + angular.element( 'span#inititate' ).html() ), function ( response ) {
        $scope.staticData = {title : response.response.title,content : response.response.content}
        requestFactory.toggleLoader();
    }, $scope.fillError );
    $scope.errors = {};
    baseValidator.setRules( JSON.parse( angular.element( 'span#rules' ).html() ) );
    $scope.submitform = function ( $event ) {
        if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
            if ( angular.element( 'span#inititate' ).html() ) {
                requestFactory.post( requestFactory.getUrl( 'staticContent/edit/' + angular.element( 'span#inititate' ).html() ), $scope.staticData, function ( response ) {
                    location.href = requestFactory.getTemplateUrl( 'admin/staticContent' ) ;
                }, function ( resp ) {
                    $scope.fillError( resp );
                } );
            }
        }
    }
    /**
     *  Functtion is used to fill the error
     *  
     */
    $scope.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    $scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };
}] );

/**
* Manually bootstrap the Angular module here
*/
