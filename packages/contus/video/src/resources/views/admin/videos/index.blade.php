@extends('base::layouts.default') @section('stylesheet')
    <link href="http://vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}"/>
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/cropper.css')}}"/>
    <style>
        .img-container img {
            max-width: 100%;
        }

        .img-preview {
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
        .uploaded_img.active {
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
    </style>
    <div data-ng-controller="VideoGridController as vgridCtrl">
        @include('video::admin.common.subMenu')
        <div class="contentpanel clearfix video_grid">
            @include('base::partials.errors')
            <div class="alert alert-success" data-ng-if="vgridCtrl.showResponseMessage">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <span>@{{vgridCtrl.responseMessage}}</span>
            </div>
            {{--        @if(Request::segment(2) == "livevideos")--}}
            {{--<div data-grid-view data-rows-per-page="10" data-route-name="videos" data-template-route="admin/videos" data-request-grid="videos" data-count="false" data-ng-init="selectTab('live_videos')"></div>--}}
            {{--@else--}}
            <div data-grid-view data-rows-per-page="10" data-route-name="videos" data-template-route="admin/videos"
                 data-request-grid="videos" data-count="false"></div>
            {{--@endif--}}
        </div>
        <div class="contentpanel clearfix add_video_container" id="video_frame">
            <i class="fa fa-times" aria-hidden="true" data-ng-click="vgridCtrl.hideUploadOption()"></i>
            <form name="videoForm" enctype="multipart/form-data">
                <div id="file_drop_area" class="upload_video_container">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <h2 id="preset_error"
                        data-ng-show="vgridCtrl.numberOfActivePresets == 0">{{ __('video::videos.preset_error') }}</h2>
                    <div data-ng-show="vgridCtrl.numberOfActivePresets > 0">
                        <div id="upload_errors_wrap">
                            <h2 id="upload_error">{{ __('video::videos.upload_error') }}</h2>
                            <h2 id="upload_staus_when_error"></h2>
                        </div>
                        <h2 id="upload_title">
                            <span>{{ __('video::videos.drag_and_drop') }}</span>
                            {{ __('video::videos.your_video_file') }}
                        </h2>
                        <p>{{ __('video::videos.accepted_video_formats') }}</p>
                        <p id="video_error">{{ __('video::videos.select_valid_file') }}</p>
                        <p id="upload_percentage"></p>
                        <div class="upload_file_input">
                            <input type="file" class="filestyle" id="video" name="video" data-buttonName="btn-primary"
                                   multiple>
                            <span>{{ __('video::videos.browse_from_computer') }}</span>
                        </div>
                        <div id="video_upload_button_wrap" class="video_upload_div_btn">
                            <button class="btn btn-primary" type="button"
                                    title="{{ __('video::videos.upload') }}">{{ __('video::videos.upload') }}</button>
                        </div>
                    </div>
                </div>
                <div data-ng-show="vgridCtrl.numberOfActivePresets > 0 && false"
                     style=" text-align: center; padding-bottom: 20px">
                    <button id="google_drive_upload_button" style="padding: 10px" data-ng-click="vgridCtrl.onApiLoad()"
                            type="submit" value="Submit">
                        <img src="{{$getBaseAssetsUrl('images/admin/google_drive.png')}}">
                    </button>
                    <!-- The Google API Loader script. -->
                    <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
                    <script type="text/javascript" src="https://apis.google.com/js/client.js"></script>
                </div>
            </form>
            <div class="col-xs-12 col-sm-12 progress-container">
                <div id="progress-bar-wrap" class="progress progress-striped active">
                    <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%"></div>
                </div>
            </div>
        </div>
        <!-- Video form fields start -->
        <div id="form_field_div">
        </div>
        <div id="dynamic_content1" style="display:none">
            <div class="contentpanel clearfix video-upload" id="video_thumb_container1">
                <div class="upload_video_container">
                    <img src="{{$getBaseAssetsUrl('images/no-preview.png')}}">
                    <span id="upload_title1"></span>
                </div>
                <div class="loading">
                    <p id="upload_percentage1"></p>
                    <div class="col-xs-12 col-sm-12 progress-container">
                        <div id="progress-bar-wrap1" class="progress progress-striped active">
                            <div id="progress-bar1" class="progress-bar progress-bar-success" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="video_forms1" class="video-container" data-base-validator>
                <form name="videoEditForm" method="POST" data-base-validator
                      data-ng-submit="vgridCtrl.saveVideoEdit($event)" enctype="multipart/form-data">
                    <div>
                        <input type="hidden" id="video_id1" name="video_id" data-ng-model="vgridCtrl.editVideo.id"/>
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
                        <div class="clearfix">
                            <div class="form-group wid-50 p-right"
                                 data-ng-class="{'has-error': errors.category_ids.has}"
                                 data-ng-hide="editVideo.is_live">
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
                                            <span>
                                                <i class="fa fa-minus-circle"></i>
                                            </span>
                                                @{{vgridCtrl.allCategories[category.id]}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <input ng-hide="true" type="text" name="category_ids" class="form-control"
                                       placeholder="{{ __('video::videos.categories_place_holder') }}"
                                       data-ng-model="vgridCtrl.editVideo.category_ids">
                                <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.category_ids.message }}</p>
                            </div>
                            <div class="form-group wid-50" data-ng-class="{'has-error': errors.exam_ids.has}"
                                 data-ng-hide="editVideo.is_live">
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
                                            <span>
                                                <i class="fa fa-minus-circle"></i>
                                            </span>
                                                @{{vgridCtrl.allExams[exams.id]}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.exam_ids.message
                                    }}</p>
                            </div>
                        </div>
                        
                        <div class="form-group" data-ng-class="{'has-error': errors.description.has}">
                            <label class="control-label">{{ __('video::videos.description') }}</label>
                            <span class="asterisk">*</span>
                            <textarea name="description" data-ng-model="vgridCtrl.editVideo.description"
                                      class="form-control" rows="5"
                                      placeholder="{{ __('video::videos.description_placeholder') }}"></textarea>
                            <p class="help-block" data-ng-show="errors.published_on.has">@{{ errors.published_on.message
                                }}</p>
                        </div>
                        <div class="clearfix">
                            <div class="form-group wid-50 p-right" data-ng-class="{'has-error': errors.presenter.has}">
                                <label class="control-label">
                                    {{ __('video::videos.presenter') }}
                                    <span class="asterisk">*</span>
                                </label>
                                <input type="text" name="presenter" class="form-control"
                                       placeholder="{{ __('video::videos.presenter_placeholder') }}"
                                       data-ng-model="vgridCtrl.editVideo.presenter">
                                <p class="help-block" data-ng-show="errors.presenter.has">@{{ errors.presenter.message
                                    }}</p>
                            </div>
                            <div class="form-group wid-50">
                                <label class="control-label">
                                Published on
                                </label>
                                <input type="text" name="published_on" id="published_on1"
                                       ng-model="vgridCtrl.editVideo.published_on" datetime-picker size="30"
                                       placeholder="YYYY-MM-DD" data-validation-name="Published on"
                                       value="{{old('published_on')}}" class="form-control"
                                       ng-blur="dateBlur($event,vgridCtrl.editVideo.published_on)"
                                       ng-keyup="dateKeyup($event,vgridCtrl.editVideo.published_on)"/>
                                <p class="help-block" data-ng-show="errors.published_on.has">@{{ errors.published_on.message
                                    }}</p>
                            </div>
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
                            <p class="help-block" data-ng-show="errors.is_featured.has">@{{ errors.is_featured.message
                                }}</p>
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
                            <div data-select-Two class="form-control tagOuterBox kewwords_tag clearfix">
                                <div class="tagBox">
                                    <span data-ng-repeat="tag in keywords" class="result_tag">
                                        @{{tag}}
                                        <span class="removetag fa fa-times"
                                              data-ng-click="removeKeyword($index)"></span>
                                    </span>
                                </div>
                                <div contentEditable="true" data-keyword-editable class="edit_keywords"
                                     data-ng-model="vgridCtrl.searchKeywords.search_tags" title="Click to edit"></div>
                            </div>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}" ng-hide="true">
                            <label class="control-label">
                                {{ __('video::videos.status') }}
                                <span class="asterisk">*</span>
                            </label>
                            <select class="form-control" name="is_active" data-ng-model="vgridCtrl.editVideo.is_active"
                                    data-validation-name="status">
                                <option value="">{{ __('video::videos.select_status') }}</option>
                                <option value="1">{{ __('video::videos.message.active') }}</option>
                                <option value="0">{{ __('video::videos.message.inactive') }}</option>
                            </select>
                            <p class="help-block" data-ng-show="errors.is_active.has">@{{ errors.is_active.message
                                }}</p>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.trailer_status.has}" ng-hide="true">
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
                                        <img class="uploaded_img" alt="">
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
                    <button class="btn btn-primary pull-right submitbutton">{{ __('video::videos.submit') }}</button>
                    &nbsp;
                    <a class="btn btn-danger pull-right mr10"
                       href="{{url('admin/videos')}}">{{ __('video::videos.cancel') }}</a>
                </form>
            </div>
        </div>
        <!-- video form field end  -->
        <nav class="st-menu st-effect-7" id="menu-7">
            <div class="embed">
                <video id="video_player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="none"
                       width="460" height="300" poster="" data-setup="{}">
                    <p class="vjs-no-js">{{ __('video::videos.video_not_supported') }}</p>
                </video>
                <div id="__code_message" data-ng-show="__codeMessage">{{ __('video::videos.__code_in_progress') }}</div>
            </div>
            <div class="pop_over_continer">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#details" data-ng-click="vgridCtrl.setVideoEditRules()"
                           data-toggle="tab">{{ __('video::videos.details') }}</a>
                    </li>
                    <li>
                        <a href="#video_presets" data-toggle="tab">{{ __('video::videos.presets') }}</a>
                    </li>
                    <li>
                        <a href="#video_thumbnails" data-ng-click="vgridCtrl.setThumbUploadRules()"
                           data-toggle="tab">{{ __('video::videos.thumbnails') }}</a>
                    </li>
                </ul>
                <div class="pop_over_inner">
                    <div class="tab-content">
                        <div class="tab-pane active" id="details">
                            <form name="videoEditForm" method="POST" data-base-validator
                                  data-ng-submit="vgridCtrl.saveVideoEdit($event)" enctype="multipart/form-data">
                                <div class="video_form">
                                    <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                                        <label class="control-label">
                                            {{ __('video::videos.title') }}
                                            <span class="asterisk">*</span>
                                        </label>
                                        <input type="text" name="title" class="form-control"
                                               placeholder="{{ __('video::videos.title_placeholder') }}"
                                               data-ng-model="vgridCtrl.editVideo.title">
                                        <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message
                                            }}</p>
                                    </div>
                                    <div class="form-group" data-ng-class="{'has-error': errors.category_ids.has}">
                                        <label class="control-label">
                                            {{ __('video::videos.categories') }}
                                            <span class="asterisk">*</span>
                                        </label>
                                        <div>
                                            <div class="admin_category_sub clearfix">
                                                <input type="text" class="form-control"
                                                       data-ng-model="vgridCtrl.categoryField"
                                                       placeholder="{{ __('video::videos.categories_place_holder') }}"
                                                       data-ng-keyup="vgridCtrl.showCategoriesSuggestions($event)">
                                                <ul data-ng-if="vgridCtrl.categorySuggestions.length > 0"
                                                    class="list_category">
                                                    <li data-ng-repeat="suggestion in vgridCtrl.categorySuggestions"
                                                        data-ng-click="vgridCtrl.addCategoriesToVideos(suggestion.id,suggestion.name)">
                                                        @{{suggestion.name}}
                                                    </li>
                                                </ul>
                                                <ul class="select_list_category">
                                                    <li data-ng-repeat="category in vgridCtrl.multipleCategories"
                                                        data-ng-click="vgridCtrl.removeCategoriesFromVideos($index)">
                                                    <span>
                                                        <i class="fa fa-minus-circle"></i>
                                                    </span>
                                                        @{{category.name}}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="help-block" data-ng-show="errors.category_ids.has">@{{
                                            errors.category_ids.message }}</p>
                                    </div>
                                    <div class="form-group" data-ng-class="{'has-error': errors.short_description.has}">
                                        <label class="control-label">
                                            {{ __('video::videos.short_description') }}
                                            <span class="asterisk">*</span>
                                        </label>
                                        <input type="text" name="short_description" class="form-control"
                                               placeholder="{{ __('video::videos.short_description_placeholder') }}"
                                               data-ng-model="vgridCtrl.editVideo.short_description">
                                        <p class="help-block" data-ng-show="errors.short_description.has">@{{
                                            errors.short_description.message }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{ __('video::videos.description') }}</label>
                                        <textarea name="description" data-ng-model="vgridCtrl.editVideo.description"
                                                  class="form-control" rows="5"
                                                  placeholder="{{ __('video::videos.description_placeholder') }}"></textarea>
                                    </div>
                                    <div class="form-group" data-ng-class="{'has-error': errors.is_featured.has}">
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
                                    <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}">
                                        <label class="control-label">
                                            {{ __('video::videos.status') }}
                                            <span class="asterisk">*</span>
                                        </label>
                                        <select class="form-control" name="is_active"
                                                data-ng-model="vgridCtrl.editVideo.is_active"
                                                data-validation-name="status">
                                            <option value="">{{ __('video::videos.select_status') }}</option>
                                            <option value="1">{{ __('video::videos.message.active') }}</option>
                                            <option value="0">{{ __('video::videos.message.inactive') }}</option>
                                        </select>
                                        <p class="help-block" data-ng-show="errors.is_active.has">@{{
                                            errors.is_active.message }}</p>
                                    </div>
                                </div>
                                <div class="panel-footer clearfix">
                                    <button class="btn btn-primary pull-right">{{ __('video::videos.submit') }}</button>
                                    &nbsp;
                                    <a class="btn btn-danger pull-right mr10"
                                       href="{{url('admin/videos')}}">{{ __('video::videos.cancel') }}</a>
                                </div>
                            </form>
                        </div>
                        <div id="video_presets" class="tab-pane">
                            <div class="presets_wrap">
                                <div class="preset_wrap"
                                     data-ng-repeat="preset in vgridCtrl.editVideo.videoPresets track by $index">@{{
                                    $index+1 }}. @{{ preset }}
                                </div>
                            </div>
                        </div>
                        <div id="video_thumbnails" class="tab-pane">
                            <form name="thumbnailUploadForm" method="POST" data-base-validator
                                  data-ng-submit="vgridCtrl.thumbnailUpload($event)" enctype="multipart/form-data">
                                <div class="video_form">
                                    <div class="form-group" data-ng-class="{'has-error': errors.thumbnail.has}">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="input-append">
                                                <div class="uneditable-input">
                                                    <i class="glyphicon glyphicon-file fileupload-exists"></i>
                                                    <span class="fileupload-preview"></span>
                                                </div>
                                                <span class="btn btn-default btn-file">
                                                <span class="fileupload-new">{{__('video::videos.select_image')}}</span>
                                                <span class="fileupload-exists">{{__('video::videos.change')}}</span>
                                                <input type="file" id="thumb-image" name="image"
                                                       data-action="{{url('api/admin/videos/thumbnail')}}"/>
                                            </span>
                                                <a href="#" class="btn btn-default fileupload-exists video-thumb-remove"
                                                   data-dismiss="fileupload"
                                                   data-ng-click="vgridCtrl.removeThumbnailProperty()">{{__('video::videos.remove')}}</a>
                                                <p class="help-block hide"></p>
                                            </div>
                                        </div>
                                        <p class="help-block" data-ng-show="errors.thumbnail.has">@{{
                                            errors.thumbnail.message }}</p>
                                        <div class="form-group">
                                            <div class="clsFileUpload">
                                            <span id="thumb-delete"
                                                  data-ng-click="vgridCtrl.deleteThumbnail();vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'"
                                                  data-ng-show="vgridCtrl.editVideo.thumbnail_image"
                                                  data-boot-tooltip="true"
                                                  title="{{__('video::videos.delete_thumbnail')}}">
                                                <i class="fa fa-remove" aria-hidden="true"></i>
                                            </span>
                                                <img id="thumb-preview"
                                                     data-ng-show="vgridCtrl.editVideo.thumbnail_image"
                                                     data-ng-src="@{{vgridCtrl.editVideo.thumbnail_image}}"
                                                     width="180px" height="180px">
                                                <div id="thumb-progress" class="hide clsProgressbar"
                                                     data-ng-click="vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'"></div>
                                                <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer clearfix">
                                    <button class="btn btn-primary pull-right">{{ __('video::videos.submit') }}</button>
                                    &nbsp;
                                    <a class="btn btn-danger pull-right mr10"
                                       href="{{url('admin/videos')}}">{{ __('video::videos.cancel') }}</a>
                                </div>
                            </form>
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
                </div>
            </div>
        </nav>
        <div class="modal fade" id="videoDeleteModal" data-role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">{{__('base::gridlist.delete_record')}}</h5>
                    </div>
                    <div class="modal-body">
                        <div data-ng-show="videoConfirmationDeleteBox">
                            <p>{{__('base::gridlist.delete_confirm')}}</p>
                        </div>
                    </div>
                    <div class="clearfix modal-footer video_delete_footer">
                        <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right"
                              data-dismiss="modal">{{__('base::gridlist.cancel')}}</span>
                        <span data-ng-click="vgridCtrl.confirmDeleteVideos('single-video')"
                              class="btn btn-primary pull-right mr10"
                              data-dismiss="modal">{{__('base::gridlist.confirm')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="videoBulkDeleteModal" data-role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">{{__('base::gridlist.bulk_action')}}</h5>
                    </div>
                    <div class="modal-body" data-ng-show="vgridCtrl.isDeleteBulkRecord ">
                        <div>
                            <p>{{__('base::gridlist.bulk_delete_confirm')}}</p>
                        </div>
                    </div>
                    <div class="modal-body" data-ng-show="vgridCtrl.isActivateBulkRecord">
                        <div>
                            <p>{{__('base::gridlist.bulk_activate_confirm')}}</p>
                        </div>
                    </div>
                    <div class="modal-body" data-ng-show="vgridCtrl.isDeactivateBulkRecord">
                        <div>
                            <p>{{__('base::gridlist.bulk_deactivate_confirm')}}</p>
                        </div>
                    </div>
                    <div class="clearfix modal-footer video_delete_footer" data-ng-show="vgridCtrl.isDeleteBulkRecord ">
                        <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right"
                              data-dismiss="modal">{{__('base::gridlist.cancel')}}</span>
                        <span data-ng-click="vgridCtrl.confirmDeleteVideos('bulk-video')"
                              class="btn btn-primary pull-right mr10"
                              data-dismiss="modal">{{__('base::gridlist.confirm')}}</span>
                    </div>
                    <div class="clearfix modal-footer video_delete_footer"
                         data-ng-show="vgridCtrl.isActivateBulkRecord">
                        <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right"
                              data-dismiss="modal">{{__('base::gridlist.cancel')}}</span>
                        <span data-ng-click="vgridCtrl.confirmActivateOrDeactivateVideos(1)"
                              class="btn btn-primary pull-right mr10"
                              data-dismiss="modal">{{__('base::gridlist.confirm')}}</span>
                    </div>
                    <div class="clearfix modal-footer video_delete_footer"
                         data-ng-show="vgridCtrl.isDeactivateBulkRecord">
                        <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right"
                              data-dismiss="modal">{{__('base::gridlist.cancel')}}</span>
                        <span data-ng-click="vgridCtrl.confirmActivateOrDeactivateVideos(0)"
                              class="btn btn-primary pull-right mr10"
                              data-dismiss="modal">{{__('base::gridlist.confirm')}}</span>
                    </div>
                </div>
            </div>
        </div>
        >
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
    </div>
@endsection @section('scripts')
    <script src="{{$getBaseAssetsUrl('js/cropper.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
    <script src="http://vjs.zencdn.net/ie8/1.1.0/videojs-ie8.min.js"></script>
    <script src="http://vjs.zencdn.net/5.0.2/video.min.js"></script>
    <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/angular/angular-ui.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/videos/videoGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script type="text/javascript">
        // <![CDATA[
        window.VPlay = {
            route: {
                siteUrl: "{{url('/')}}",
            },
            developerKey: "{{ $developer_key }}",
            clientId: "{{ $client_id }}",
        };
        // ]]>
    </script>
@endsection