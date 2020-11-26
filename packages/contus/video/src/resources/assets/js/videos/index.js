'use strict';
var wowzaController = ['$scope','flowFactory','requestFactory','$window','$sce','$timeout',function ( scope, flowFactory, requestFactory, $window, $sce, $timeout ) {
    var self = this;
    this.latestnews = {};
    this.editVideo = {};
    this.showResponseMessage = false;
    scope.checkStream = "Yes";
    requestFactory.setThisArgument( this );
    scope.init = function ( id ) {
        /*requestFactory.get( requestFactory.getUrl( 'videos/latestnews/' + id ), function ( response ) {
            this.editLatestNews( response.message )
        }, this.fillError );*/

        requestFactory.get( requestFactory.getUrl( 'videos/info' ), self.defineProperties, function () {
        } );
    }
    scope.existingFlowObject = flowFactory.create( {target : document.querySelector( 'meta[name="base-api-url"]' ).getAttribute( 'description' ) + '/latest/latestnews-image',permanentErrors : [404,500,501],testChunks : false,maxChunkRetries : 1,chunkRetryInterval : 5000,simultaneousUploads : 4,singleFile : true} );
    scope.existingFlowObject.on( 'fileSuccess', function ( event,message ) {
        if ( message) {
            self.editVideo.selected_thumb = message;
            angular.element( '.loaders' ).hide();
            angular.element( '.submitbutton' ).attr('disabled', false)
        }
    } );
    scope.existingFlowObject.on( 'fileAdded', function ( file ) {
        if ( file.size > 2097152 ) {
            return false;
        }
        angular.element( '.loaders' ).show();                  
        angular.element( '.submitbutton' ).attr('disabled', true)
    } );

    /**
     *  To get the profile rules
     *  
     */
    this.defineProperties = function ( data ) {
        this.info = data.info;
        this.allCategories = data.info.allCategories;
        this.allExams = data.info.allCollection;
        this.allCountries = data.info.allCountries;
        console.log(this.info.video_edit_rules)
        baseValidator.setRules( this.info.video_edit_rules );

        $('#published_on').datepicker({
          format: "dd-mm-yyyy",
          viewMode: 'years',
          autoclose: true
        }).datepicker('setDate', this.editVideo.published_on);
    };

    /**
     *  To get the auth id
     *  
     */
    this.setQuery = function ( $authId ) {
        this.authId = $authId;
    }

    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };

    /**
     *  Function is used to save the latestnews
     *  
     *  @param $event,id
     */
    this.save = function ( $event) {
        scope.errors={};
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
                requestFactory.post( requestFactory.getUrl( 'createlivestream' ), this.editVideo, function ( response ) {
                    window.location.href=requestFactory.getTemplateUrl( 'admin/livevideos' ) ;
                }, this.fillError );
        }
    }
    scope.$on( 'afterGetRecords', function ( e, data ) {
        if ( angular.isUndefined( scope.searchRecords.aspect_ratio ) ) {
            scope.searchRecords.aspect_ratio = 'all';
        }
    } );

    /*
     * Function to add a category to the category field in video edit form.
     */
    this.addCategoriesToVideos = function ( id, categoryName ) {
        self.editVideo.category_ids = [];
        self.multipleCategories = [];
        self.editVideo.category_ids.push( id );
        self.multipleCategories.push( {id : id,name : categoryName} );
        self.categoryField = '';
        self.examField = '';
        self.categorySuggestions = [];
    };
    /*
     * Function to add a category to the category field in video edit form.
     */
    this.addExamToVideos = function ( id, examName ) {
        self.editVideo.exam_ids = [];
        self.multipleExams = [];
        self.editVideo.exam_ids.push( id );
        self.multipleExams.push( {id : id,title : examName} );
        self.examField = '';
        self.examSuggestions = [];
    };

    /*
     * Function to show categories suggestions in category field of video edit form.
     */
    this.showCategoriesSuggestions = function ( $event ) {
        var name = $event.target.value;
        self.categorySuggestions = [];
        if ( typeof name === 'string' && name != '' && name.length >= 1 ) {
            angular.forEach( self.allCategories, function ( value, key ) {
                key = Number( key );
                if ( value.toLowerCase().indexOf( name.toLowerCase() ) != -1 ) {
                    // && self.editVideo.category_ids.indexOf( key ) == -1 
                    self.categorySuggestions.push( {id : key,name : value} );
                }
            } );
        } else {
            self.categorySuggestions = [];
        }
    };

    /*
     * Function to show categories suggestions in category field of video edit form.
     */
    this.showExamsSuggestions = function ( $event ) {
        var title = $event.target.value;
        self.examSuggestions = [];
        if ( typeof title === 'string' && title != '' && title.length >= 1 ) {
            angular.forEach( self.allExams, function ( value, key ) {
                key = Number( key );
                if ( value.toLowerCase().indexOf( title.toLowerCase() ) != -1  ) {
                    //&& self.editVideo.exam_ids.indexOf( key ) == -1
                    self.examSuggestions.push( {id : key,title : value} );
                }
            } );
        } else {
            self.examSuggestions = [];
        }
    };
     /*
     * Function to remove a category from the category field in video edit form.
     */
    this.removeCategoriesFromVideos = function ( index ) {
        // Check if there are more than one category selected. If yes, allow to remove the category and if no, restrict from removing the category.
        if ( self.editVideo.category_ids.length > 1 ) {
            var categoryId = self.multipleCategories [index].id;
            var categoryIdIndex = self.editVideo.category_ids.indexOf( categoryId );
            if ( categoryIdIndex > -1 ) {
                self.editVideo.category_ids.splice( categoryIdIndex, 1 );
            }
            self.multipleCategories.splice( index, 1 );
        }
    };

    /*
     * Function to remove a category from the category field in video edit form.
     */
    this.removeExamsFromVideos = function ( index ) {
        // Check if there are more than one category selected. If yes, allow to remove the category and if no, restrict from removing the category.
        if ( self.editVideo.exam_ids.length > 0 ) {
            var examId = self.multipleExams [index].id;
            var examIdIndex = self.editVideo.exam_ids.indexOf( examId );
            if ( examIdIndex > -1 ) {
                self.editVideo.exam_ids.splice( examIdIndex, 1 );
            }
            self.multipleExams.splice( index, 1 );
        }
    };

     /**
   * Image Upload Script
   *
   * */
  function readAsUrl(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('image').src = e.target.result;
      };
      reader.onloadend = function(e) {
        $('#modal').modal('show');
      };
      reader.readAsDataURL(input.files[0]);
    }
  };

    $(document).ready(function() {
        var image = document.getElementById('image');
        $(document).on('change', '.uploadImg', function() {
          $('.crop-body').show();
          readAsUrl(this);
        });
        var cropBoxData;
        var canvasData;
        var cropper;
        $(document).on('show.bs.modal', '#modal', function() {
          $('.error_msg').hide();
          setTimeout(function() {
            cropper = new Cropper(image, {
              autoCropArea: 1,
              viewMode: 3,
              aspectRatio: 40 / 43,
              preview: '.img-preview',
              cropBoxResizable: false,
              minCropBoxWidth: 200,
              minCropBoxHeight: 245,
              dragCrop: false,
              mouseWheelZoom: false,
              resizable: false,
              ready: function() {
                //Should set crop box data first here
                cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
              }
            });
          }, 500);
        });
        $(document).on('hidden.bs.modal', '#modal', function() {
          document.getElementsByClassName("uploadImg")[0].value = "";
          $('#submit-image').prop('disabled', false);
          cropper.destroy();
        });
        $(document).on('click', '#submit-image', function() {
          cropBoxData = cropper.getCropBoxData();
          canvasData = cropper.getCroppedCanvas().toBlob(function(blob) {
            var formData = new FormData();
            formData.append('module', 'video');
            formData.append('size', 'thumb');
            formData.append('image', blob);
            $('.crop-body').hide();
            $('.loader-container').show();
            $('#submit-image').prop('disabled', true);
            $.ajax($('meta[name="base-api-url"]').attr('content') + '/videos/thumbnail', {
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              success(data) {
                $('.uploaded_img').attr('src', data.info);
                $('.uploaded_img').show();
                self.editVideo.thumbnail = data.info;
                $('.loader-container').hide();
                $('#modal').modal('hide');
              },
              error() {
                $('.loader-container').hide();
                $('.error_msg').show().text("Please upload bigger image");
              },
            })
          }, 'image/jpeg');
        });
    })
  /**
   * End of image upload script
   *
   * */


    /**
     * Poster Image Upload Script
     *
     * */
    function readAsPosterUrl(input) {
        if (input.files && input.files[0]) {
          var readerImg = new FileReader();
          readerImg.onload = function(e) {
            document.getElementById('poster_image').src = e.target.result;
          };
          readerImg.onloadend = function(e) {
            $('#poster_modal').modal('show');
          };
          readerImg.readAsDataURL(input.files[0]);
        }
    };

    $(document).ready(function() {
        var posterImage = document.getElementById('poster_image');
        $(document).on('change', '.uploadPosterImg', function() {
          $('.crop-body').show();
          readAsPosterUrl(this);
        });
        var cropBoxImgData;
        var canvasImgData;
        var cropperImg;
        $(document).on('show.bs.modal', '#poster_modal', function() {
          $('.poster_error_msg').hide();
          setTimeout(function() {
            cropperImg = new Cropper(posterImage, {
              autoCropArea: 1,
              viewMode: 3,
              aspectRatio: 817 / 500,
              preview: '.poster_img-preview',
              cropBoxResizable: false,
              minCropBoxWidth: 400,
              minCropBoxHeight: 300,
              dragCrop: false,
              mouseWheelZoom: false,
              resizable: false,
              ready: function() {
                //Should set crop box data first here
                cropperImg.setCropBoxData(cropBoxImgData).setCanvasData(canvasImgData);
              }
            });
          }, 500);
        });
        $(document).on('hidden.bs.modal', '#poster_modal', function() {
          document.getElementsByClassName("uploadPosterImg")[0].value = "";
          $('#submit_poster_image').prop('disabled', false);
          cropperImg.destroy();
        });
        $(document).on('click', '#submit_poster_image', function() {
          cropBoxImgData = cropperImg.getCropBoxData();
          canvasImgData = cropperImg.getCroppedCanvas().toBlob(function(blob) {
            var formImgData = new FormData();
            formImgData.append('module', 'video');
            formImgData.append('size', 'poster');
            formImgData.append('image', blob);
            $('.crop-body').hide();
            $('.poster_loader-container').show();
            $('#submit_poster_image').prop('disabled', true);
            $.ajax($('meta[name="base-api-url"]').attr('content') + '/videos/thumbnail', {
              method: "POST",
              data: formImgData,
              processData: false,
              contentType: false,
              success(data) {
                $('.uploaded_poster_img').attr('src', data.info);
                $('.uploaded_poster_img').show();
                self.editVideo.poster_image = data.info;
                $('.poster_loader-container').hide();
                $('#poster_modal').modal('hide');
              },
              error() {
                $('.poster_loader-container').hide();
                $('.poster_error_msg').show().text("Please upload bigger image");
              },
            })
          }, 'image/jpeg');
        });
    })
}];

window.gridControllers = {wowzaController : wowzaController};
window.gridInitApp = angular.module( 'grid', ['flow','angularjs-datetime-picker'] );
window.gridDirectives = {baseValidator : validatorDirective};

$( document ).ready( function () {
    var loader = $( '#preloader' );
    loader.find( '#status' ).css( 'display', 'none' );
    loader.css( 'display', 'none' );
} );