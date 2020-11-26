@extends('base::layouts.default') @section('stylesheet')
    <link href="http://vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}"/>
    <link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/cropper.css')}}"/>
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
    <style type="text/css">
        .custom-color {
            color: #a94442;
        }

        .kewwords_tag .tagBox {
            height: auto;
            padding: 0;
        }

        .kewwords_tag .edit_keywords {
            float: left;
            padding: 0px 10px;
            background: #fff;
        }

        .kewwords_tag .result_tag {
            display: inline-block;
            background-color: #e4e4e4;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            padding: 0 5px;
        }

        .kewwords_tag span.removetag {
            border: none;
            margin-left: 5px;
            padding: 1px;
            cursor: pointer;
        }

        .tagOuterBox:after {
            content: '';
            display: block;
            clear: both;
            background-color: #D0D0D0;
        }

        .tagBox {
            float: left;
        }

        .tagOuterBox .contentEditable {
            float: left;
        }

        div[contentEditable] {
            cursor: pointer;
            background-color: #D0D0D0;
        }

        .img-container img {
            max-width: 100%;
        }

        .img-preview, .poster_img-preview {
            width: 200px;
            height: 200px;
            overflow: hidden;
        }

        .img-cropper {
            width: 50%;
        }

        .uploaded_img {
            width: 200px;
            height: 215px;
            margin: 10px;
            display: none;
        }
        .uploaded_poster_img {
            width: 400px;
            height: 250px;
            margin: 10px;
            display: none;
        }
        .uploaded_img.active, .uploaded_poster_img.active {
            display: block;
        }
        .loader-container, .poster_loader-container {
            display: none;
            text-align: center;
        }
        p.error_msg, p.poster_error_msg {
            display: none;
            color: red;
            text-align: center;
        }
    </style>
    @include('video::admin.common.subMenu')
    <div class="contentpanel product order_list">
        <div class="panel main_container clearfix" style="border: 1px solid __parent;">
            <div class=" add_form detail_video_form">
                <h4 style="padding: 0 0 20px 0;">Video</h4>
                <div class="" data-base-validator data-ng-controller="VideoDetailController as vgridCtrl">
                    <form name="videoEditForm" method="POST" data-ng-init="vgridCtrl.fetchData('{{$id}}')"
                          data-base-validator data-ng-submit="vgridCtrl.saveVideoEdit($event,'{{URL::previous()}}')"
                          enctype="multipart/form-data">
                        <div>
                            <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                                <label class="control-label">
                                    {{ __('video::videos.title') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <input type="text" name="title" class="form-control"
                                       placeholder="{{ __('video::videos.title_placeholder') }}"
                                       data-ng-model="vgridCtrl.editVideo.title">
                                <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                            </div>

                            <div class="form-group" data-ng-class="{'has-error': errors.category_ids.has}"
                                 >
                                <label class="control-label">
                                    {{ __('video::videos.category') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <div>
                                    <div class="admin_category_sub clearfix">
                                        <input type="text" class="form-control" data-ng-model="vgridCtrl.categoryField"
                                               placeholder="{{ __('video::videos.categories_place_holder') }}"
                                               data-ng-keyup="vgridCtrl.showCategoriesSuggestions($event)">
                                        <ul data-ng-if="vgridCtrl.categorySuggestions.length > 0" class="list_category">
                                            <li data-ng-repeat="suggestion in vgridCtrl.categorySuggestions"
                                                data-ng-click="vgridCtrl.addCategoriesToVideos(suggestion.id,suggestion.name)">
                                                @{{suggestion.name}}
                                            </li>
                                        </ul>
                                        <ul class="select_list_category">
                                            <li data-ng-repeat="category in vgridCtrl.multipleCategories"
                                                data-ng-click="vgridCtrl.removeCategoriesFromVideos($index)">
                                                @{{vgridCtrl.allCategories[category.id]}}
                                            <span><i class="fa fa-times"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <input style="display: none;" type="text" name="category_ids" class="form-control"
                                       placeholder="{{ __('video::videos.presenter_placeholder') }}"
                                       data-ng-model="vgridCtrl.editVideo.category_ids">

                                <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.category_ids.message }}</p>
                            </div>

                        
                            <div class="form-group" data-ng-class="{'has-error': errors.exam_ids.has}"
                                 >
                                <label class="control-label">{{ __('video::videos.genre') }} </label>
                                <div>
                                    <div class="admin_category_sub clearfix">
                                        <input type="text" class="form-control" data-ng-model="vgridCtrl.examField"
                                               placeholder="{{ __('video::videos.exam_place_holder') }}"
                                               data-ng-keyup="vgridCtrl.showExamsSuggestions($event)">
                                        <ul data-ng-if="vgridCtrl.examSuggestions.length > 0" class="list_category">
                                            <li data-ng-repeat="exam in vgridCtrl.examSuggestions"
                                                data-ng-click="vgridCtrl.addExamToVideos(exam.id,exam.title)">
                                                @{{exam.title}}
                                            </li>
                                        </ul>
                                        <ul class="select_list_category">
                                            <li data-ng-repeat="exams in vgridCtrl.multipleExams"
                                                data-ng-click="vgridCtrl.removeExamsFromVideos($index)">
                                                @{{vgridCtrl.allExams[exams.id]}}
                                            <span><i class="fa fa-times"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.exam_ids.message
                                    }}</p>
                            </div>
                            
                            <div class="form-group" data-ng-class="{'has-error': errors.description.has}">
                                <label class="control-label">{{ __('video::videos.description') }}<span class="asterisk">*</span></label>
                                <textarea name="description" data-ng-model="vgridCtrl.editVideo.description"
                                          class="form-control" rows="5"
                                          placeholder="{{ __('video::videos.description_placeholder') }}"></textarea>
                                <p class="help-block" data-ng-show="errors.description.has">@{{
                                    errors.description.message }}</p>
                            </div>
                            <div class="form-group" data-ng-class="{'has-error': errors.presenter.has}">
                                <label class="control-label">
                                    {{ __('video::videos.presenter') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <input data-validation-name="Cast" type="text" name="presenter" class="form-control"
                                       placeholder="{{ __('video::videos.presenter_placeholder') }}"
                                       data-ng-model="vgridCtrl.editVideo.presenter">
                                <p class="help-block" data-ng-show="errors.presenter.has">@{{ errors.presenter.message
                                    }}</p>
                            </div>
                            
                            <div class="form-group" data-ng-class="{'has-error': errors.published_on.has}">
                                <label class="control-label">
                                    Published on
                                </label>
                                <input type="text" name="published_on" id="published_on"
                                       ng-model="vgridCtrl.editVideo.published_on" datetime-picker size="30"
                                       placeholder="YYYY-MM-DD" data-validation-name="Published on"
                                       value="{{old('published_on')}}" class="form-control"
                                       ng-blur="dateBlur($event,vgridCtrl.editVideo.published_on)"
                                       ng-keyup="dateKeyup($event,vgridCtrl.editVideo.published_on)" autocomplete="off" />
                                <p class="help-block" data-ng-show="errors.published_on.has">@{{
                                    errors.published_on.message }}</p>
                            </div>
                            <div class="form-group" data-ng-class="{'has-error': errors.is_featured.has}"
                                 ng-hide="true">
                                <label class="control-label">
                                    {{ __('video::videos.is_featured') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <select class="form-control" name="is_featured"
                                        data-ng-model="vgridCtrl.editVideo.is_featured">
                                    <option value="">{{ __('video::videos.select_featured_status') }}</option>
                                    <option value="1">{{ __('video::videos.yes') }}</option>
                                    <option value="0">{{ __('video::videos.no') }}</option>
                                </select>
                                <p class="help-block" data-ng-show="errors.is_featured.has">@{{
                                    errors.is_featured.message }}</p>
                            </div>
                            <div class="form-group" ng-hide="true" data-ng-if="vgridCtrl.editVideo.is_featured == 1"
                                 data-ng-class="{'has-error': errors.is_feature_time.has}">
                                <label class="control-label">
                                    {{ __('video::videos.is_feature_time') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <input type="text" name="is_feature_time" class="form-control"
                                       placeholder="{{ __('video::videos.is_feature_time_placeholder') }}"
                                       data-ng-model="vgridCtrl.editVideo.is_feature_time">
                                <p class="help-block" data-ng-show="errors.is_feature_time.has">@{{
                                    errors.is_feature_time.message }}</p>
                            </div>


                            <div class="form-group">
                                <label class="control-label">{{ __('video::videos.search_tags') }}</label>
                                <div data-select-Two style="height: inherit !important;"
                                     class="form-control tagOuterBox kewwords_tag clearfix">
                                    <div class="tagBox">
                                    <span data-ng-repeat="tag in keywords" class="result_tag">
                                        @{{tag}}
                                        <span class="removetag fa fa-times"
                                              data-ng-click="removeKeyword($index)"></span>
                                    </span>
                                    </div>
                                    <div contentEditable="true" data-keyword-editable class="edit_keywords"
                                         data-ng-model="vgridCtrl.searchKeywords.search_tags"
                                         title="Click to edit"></div>
                                </div>
                            </div>
                            <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}">
                                <label class="control-label">
                                    {{ __('video::videos.status') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <select class="form-control" name="is_active"
                                        data-ng-model="vgridCtrl.editVideo.is_active" data-validation-name="status">
                                    <option value="">{{ __('video::videos.select_status') }}</option>
                                    <option value="1">{{ __('video::videos.message.active') }}</option>
                                    <option value="0">{{ __('video::videos.message.inactive') }}</option>
                                </select>
                                <p class="help-block" data-ng-show="errors.is_active.has">@{{ errors.is_active.message
                                    }}</p>
                            </div>
                            <div ng-hide="true" class="form-group" data-ng-class="{'has-error': errors.trailer_status.has}">
                                <label class="control-label">
                                    {{ __('video::videos.is_banner_video') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <select class="form-control" name="trailer_status"
                                        data-ng-model="vgridCtrl.editVideo.trailer_status">
                                    <option value="0">{{ __('video::videos.no') }}</option>
                                    <option value="1">{{ __('video::videos.yes') }}</option>
                                </select>
                                <p class="help-block" data-ng-show="errors.trailer_status.has">@{{
                                    errors.trailer_status.message }}</p>
                            </div>
                            <div class="profile_image_upload">
                                <div class="form-group" data-ng-class="{'has-error': errors.thumbnail.has}">
                                    <label class="control-label">{{ __('video::videos.thumnail') }}</label>
                                    <div class="fileupload fileupload-new"
                                         data-provides="fileupload">
                                        <div class="input-append">
                                            <div class="uneditable-input">
                                                <i class="glyphicon glyphicon-file" ng-show="vgridCtrl.editVideo.thumbnail_image.length"></i>
                                                <span class="fileupload-preview" ng-show="vgridCtrl.editVideo.thumbnail_image.length">@{{ vgridCtrl.editVideo.thumbnail_image }}</span>
                                            </div>
                                            <span class="btn btn-default btn-file">
                                                <span ng-hide="vgridCtrl.editVideo.thumbnail_image.length">{{__('video::videos.select_image')}}</span>
                                                <span ng-hide="!vgridCtrl.editVideo.thumbnail_image.length">{{__('video::videos.change')}}</span>
                                                <input type="file" class="uploadImg" name="image"/>
                                            </span>
                                            <img ng-src="@{{ vgridCtrl.editVideo.thumbnail_image }}" ng-class="{'active': vgridCtrl.editVideo.thumbnail_image}" class="uploaded_img" alt="">
                                        </div>
                                        <p class="intimation">Only jpeg,png files allowed.</p>
                                    </div>
                                    <p class="help-block" data-ng-show="errors.thumbnail.has">@{{
                                        errors.thumbnail.message }}</p>
                                </div>
                            </div>
                            <div class="profile_image_upload">
                                <div class="form-group" data-ng-class="{'has-error': errors.poster.has}">
                                    <label class="control-label">{{ __('video::videos.poster') }}</label>
                                    <div class="fileupload fileupload-new"
                                         data-provides="fileupload">
                                        <div class="input-append">
                                            <div class="uneditable-input">
                                                <i class="glyphicon glyphicon-file" ng-show="vgridCtrl.editVideo.poster_image.length"></i>
                                                <span class="fileupload-preview" ng-show="vgridCtrl.editVideo.poster_image.length">@{{ vgridCtrl.editVideo.poster_image }}</span>
                                            </div>
                                            <span class="btn btn-default btn-file">
                                                <span ng-hide="vgridCtrl.editVideo.poster_image.length">{{__('video::videos.select_image')}}</span>
                                                <span ng-hide="!vgridCtrl.editVideo.poster_image.length">{{__('video::videos.change')}}</span>
                                                <input type="file" class="uploadPosterImg" name="image"/>
                                            </span>
                                            <img ng-src="@{{ vgridCtrl.editVideo.poster_image }}" ng-class="{'active': vgridCtrl.editVideo.poster_image}" class="uploaded_poster_img" alt="">
                                        </div>
                                        <p class="intimation">Only jpeg,png files allowed.</p>
                                    </div>
                                    <p class="help-block" data-ng-show="errors.poster.has">@{{
                                        errors.poster.message }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="fixed-btm-action">
                            <button class="btn btn-primary pull-right submitbutton">{{ __('video::videos.submit') }}</button>
                            &nbsp;
                            <a href="{{URL::previous()}}"
                               class="btn btn-danger pull-right mr10">{{ __('video::videos.cancel') }}</a>
                        </div>
                    </form>

                    <div class="modal fade" id="videoDeleteModal" data-role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h5 class="modal-title">{{__('base::gridlist.delete_thumbnailrecord')}}</h5>
                                </div>

                                <div class="modal-body">
                                    <div>{{__('base::gridlist.delete_thumbnailmessage')}}</div>
                                    <div data-ng-show="videoConfirmationDeleteBox">
                                        <p>{{__('base::gridlist.delete_confirm')}}</p>
                                    </div>
                                </div>
                                <div class="clearfix modal-footer video_delete_footer">
                                    <span data-ng-click="vgridCtrl.cancelDeleteVideos()"
                                          class="btn btn-danger pull-right"
                                          data-dismiss="modal">{{__('base::gridlist.cancel')}}</span>
                                    <span data-ng-click="vgridCtrl.confirmDeleteVideos();"
                                          class="btn btn-primary pull-right mr10"
                                          data-dismiss="modal">{{__('base::gridlist.confirm')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="postersModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">{{__('video::videos.upload_posters')}}</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 upload_image_box">
                        <div class="form-group">
                            <input type="file" id="poster-images" multiple="multiple" name="image"
                                   data-action="{{url('api/admin/videos/posters')}}" class="form-control">
                        </div>
                    </div>
                    <div class="upload_btn">
                        <div class="form-group">
                            <input type="button" class="btn  add_new_product" value="Upload Image"
                                   id="poster-image-upload-proceed">
                        </div>
                        <div class="clsFileUpload">
                            <div id="poster-progress" class="clsProgressbar clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="castImageModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">{{__('video::videos.upload_image_for_cast_member')}}</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 upload_image_box">
                        <div class="form-group">
                            <input type="file" id="cast-images" name="image"
                                   data-action="{{url('api/admin/videos/cast-images')}}" class="form-control">
                        </div>
                    </div>
                    <div class="upload_btn">
                        <div class="form-group">
                            <input type="button" class="btn  add_new_product" value="Upload Image"
                                   id="cast-image-upload-proceed">
                        </div>
                        <div class="clsFileUpload">
                            <div id="cast-progress" class="clsProgressbar clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog img-cropper" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 loader-container">
                            <img src="{{url('contus/base/images/loader.gif')}}">
                        </div>
                        <p class="error_msg"></p>
                        <div class="crop-body">
                            <div class="col-md-8">
                                <div class="img-container">
                                    <img id="image" src="" alt="Picture">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="img-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit-image">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Poster Modal -->
    <div class="modal fade" id="poster_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog img-cropper" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 poster_loader-container">
                            <img src="{{url('contus/base/images/loader.gif')}}">
                        </div>
                        <p class="poster_error_msg"></p>
                        <div class="crop-body">
                            <div class="col-md-8">
                                <div class="img-container">
                                    <img id="poster_image" src="" alt="Picture">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="poster_img-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit_poster_image">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection @section('scripts')
    <script src="{{$getBaseAssetsUrl('js/cropper.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/angular/angular-ui.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/videos/videoDetail.js')}}"></script>
@endsection
