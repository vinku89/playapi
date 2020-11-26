/**
 * customer controller
 */
( function () {
    "use strict";
    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'baseValidator', validatorDirective );
    controller.controller( 'myAccountController', ['$scope','flowFactory','$state','$filter','ngToast','$rootScope','$document','requestFactory','data',function ($scope, flowFactory, $state, $filter, ngToast, $rootScope, $document, requestFactory, data ) {
        $scope.profile = {};
        $scope.exams = {};
        $scope.subscription_plan = {};
        $scope.subscription = {};
        $scope.examSelection = [];
        $scope.profile.exam = {};
        $scope.profile.exam = "";
        var successResponseData;
        $scope.profile.daysleft = null;
        var dataBinder = function () {
            $scope.recentlyViewed = successResponseData.message.recentlyViewed;
            $scope.profile = successResponseData.message.profile;
            $scope.exams = successResponseData.message.exams;
            $scope.userexams = successResponseData.message.userexams;
            $scope.subscription_plan = successResponseData.message.subscription_plan;
            angular.forEach( successResponseData.message.userexams [0].exams, function ( value, key ) {
                $scope.selectexam( value.slug )
            } );
            $('#age').datepicker({format:"dd-mm-yyyy",endDate: new Date(),viewMode: 'years',autoclose: true}).datepicker('setDate', successResponseData.message.profile.age);
            if ( successResponseData.message.profile.expires_at !== null && successResponseData.message.profile.expires_at !== '' ) {
                $scope.profile.daysleft = convertDaysLeft( successResponseData.message.profile.expires_at );
            }
            $scope.subscription = successResponseData.message.subscription [Math.floor( ( Math.random() * successResponseData.message.subscription.length ) + 0 )];
            $scope.subscriptions = successResponseData.message.subscription;
        };
        $scope.existingFlowObject = flowFactory.create( {target : document.querySelector( 'meta[name="base-api-url"]' ).getAttribute( 'content' ) + '/editProfile/profile-image',permanentErrors : [404,500,501],headers : requestFactory.getHeaders(),testChunks : false,maxChunkRetries : 1,chunkRetryInterval : 5000,simultaneousUploads : 4,singleFile : true} );
        $scope.clearProfilepic = function () {
            successResponseData.message.profile.profile_picture = "contus/base/images/user.png";
        }

        var convertDaysLeft = function ( expireDate ) {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; // January
            // is 0!
            var yyyy = today.getFullYear();

            if ( dd < 10 ) {
                dd = '0' + dd
            }

            if ( mm < 10 ) {
                mm = '0' + mm
            }
            today = yyyy + "-" + mm + "-" + dd;

            var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
            today = new Date( today );
            expireDate = new Date( expireDate );

            return Math.round( Math.abs( ( today.getTime() - expireDate.getTime() ) / ( oneDay ) ) );
        }
        $scope.existingFlowObject.on( 'fileSuccess', function ( event,message ) {
            if ( message ) {
                $scope.profile.profile_picture = message;
            }
        } );
        $scope.existingFlowObject.on( 'fileAdded', function ( file ) {
            if ( file.size > 2097152 ) {
                return false;
            }
        } );
        var success = function ( success ) {
            successResponseData = success;
            dataBinder();
        };
        var fail = function ( fail ) {
            return fail;
        };
        $scope.selectexam = function ( slug ) {
            var idx = $scope.examSelection.indexOf( slug );
            // Is currently selected
            if ( idx > -1 ) {
                $scope.examSelection.splice( idx, 1 );
            }
            // Is newly selected
            else {
                $scope.examSelection.push( slug );
            }
            $scope.profile.exam = $scope.examSelection.join( ',' );
        }
        var date = angular.element( '#age' );
        $scope.dateKeyup =  function(e,date) {
          var input = date;
          if(e.keyCode == 8) return false;
          if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
          var values = input.split('-').map(function(v) {
            return v.replace(/\D/g, '')
          });
          if (values[0]) values[0] = checkValue(values[0], 31);
          if (values[1]) values[1] = checkValue(values[1], 12);
          var output = values.map(function(v, i) {
            return v.length == 2 && i < 2 ? v + '-' : v;
          });
          $scope.profile.age = output.join('').substr(0, 10);
        }

        $scope.dateBlur =  function(e,date) {
          var input = date;
          var values = input.split('-').map(function(v, i) {
            return v.replace(/\D/g, '')
          });
          var output = '';
          if (values.length == 3) {
            var currDate = new Date();
            var year = values[2].length !== 4 ? currDate.getFullYear() : parseInt(values[2]);
            var month = parseInt(values[1]) - 1;
            var day = parseInt(values[0]);
            var d = new Date(year, month, day);
            if (!isNaN(d)) {
              var dates = [d.getDate(),d.getMonth() + 1,d.getFullYear()];
              output = dates.map(function(v) {
                v = v.toString();
                return v.length == 1 ? '0' + v : v;
              }).join('-');
            };
          };
          angular.element( '#age' ).click();
          $scope.profile.age =  output;
        }
        var checkValue = function ( str, max ) {
            if ( str.charAt( 0 ) !== '0' || str == '00' ) {
                var num = parseInt( str );
                if ( isNaN( num ) || num <= 0 || num > max )
                    num = 1;
                str = num > parseInt( max.toString().charAt( 0 ) ) && num.toString().length == 1 ? '0' + num : num.toString();
            }
            ;
            return str;
        };
        successResponseData = data.data;
        dataBinder();
        $scope.editCust = function ( $event ) {
            if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                var authURI = 'customerProfile';
                requestFactory.post( requestFactory.getUrl( authURI ), $scope.profile, function ( response ) {
                    ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
                    window.location =  requestFactory.getTemplateUrl('profile' );
                }, $scope.fillError );
            }
        };
        $scope.subscribeCust = function ( $event ) {
            if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                var authURI = 'addsubscriber';
                window.location = requestFactory.getTemplateUrl( authURI + '/' + $scope.subscription.slug );
            }
        };
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
} )();
