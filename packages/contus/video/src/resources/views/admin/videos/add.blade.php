@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/cropper.css')}}"/>
<style>
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
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection
@section('content')
<div ng-app="grid"
	ng-controller="wowzaController as wowzaCtrl">
	
    @include('video::admin.common.subMenu')
	<div class="pageheader clearfix">
		<h2 class="pull-left">
			<span ng-hide="true" id="inititate" data-ng-init="init()"></span>
			<h2 class="titleseperatepage">Add Wowza live stream</h2>
		</h2>
	</div>
	<form name="wowzaForm" method="POST" data-base-validator
		data-ng-submit="wowzaCtrl.save($event)"
		enctype="multipart/form-data">
		{!! csrf_field() !!}
		<div class="contentpanel">
			@include('base::partials.errors')
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="add_form clearfix">
								<div ng-if="true" class="">

									<div class="form-group" 
										data-ng-class="{'has-error': errors.title.has}">
										<label class="control-label">
											{{__('cms::latestnews.title')}} <span class="asterisk">*</span>
										</label> <input type="text" name="title"
											data-ng-model="wowzaCtrl.editVideo.title"
											class="form-control"
											placeholder="{{__('cms::latestnews.title_placeholder')}}"
											value="{{old('title')}}" />
										<p class="help-block" data-ng-show="errors.title.has">@{{
											errors.title.message }}</p>
									</div>

									<div class="form-group" data-ng-class="{'has-error': errors.category_ids.has}" >
		                                <label class="control-label">
		                                    {{ __('video::videos.category') }}
		                                    <span class="asterisk">*</span>
		                                </label>
		                                <div>
		                                    <div class="admin_category_sub clearfix">
		                                        <input type="text" class="form-control" data-ng-model="wowzaCtrl.categoryField"
		                                               placeholder="{{ __('video::videos.categories_place_holder') }}"
		                                               data-ng-keyup="wowzaCtrl.showCategoriesSuggestions($event)">
		                                        <ul data-ng-if="wowzaCtrl.categorySuggestions.length > 0" class="list_category">
		                                            <li data-ng-repeat="suggestion in wowzaCtrl.categorySuggestions"
		                                                data-ng-click="wowzaCtrl.addCategoriesToVideos(suggestion.id,suggestion.name)">
		                                                @{{suggestion.name}}
		                                            </li>
		                                        </ul>
		                                        <ul class="select_list_category">
		                                            <li data-ng-repeat="category in wowzaCtrl.multipleCategories"
		                                                data-ng-click="wowzaCtrl.removeCategoriesFromVideos($index)">
		                                                @{{wowzaCtrl.allCategories[category.id]}}
		                                            	<span><i class="fa fa-times"></i></span>
		                                            </li>
		                                        </ul>
		                                    </div>
		                                </div>
		                                <input style="display: none;" type="text" name="category_ids" class="form-control"
                                       placeholder="{{ __('video::videos.presenter_placeholder') }}"
                                       data-ng-model="wowzaCtrl.editVideo.category_ids">

                                <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.category_ids.message }}</p>
		                            </div>

		                            <div class="form-group" data-ng-class="{'has-error': errors.exam_ids.has}" >
		                                <label class="control-label">{{ __('video::videos.genre') }} </label>
		                                <div>
		                                    <div class="admin_category_sub clearfix">
		                                        <input type="text" class="form-control" data-ng-model="wowzaCtrl.examField"
		                                               placeholder="{{ __('video::videos.exam_place_holder') }}"
		                                               data-ng-keyup="wowzaCtrl.showExamsSuggestions($event)">
		                                        <ul data-ng-if="wowzaCtrl.examSuggestions.length > 0" class="list_category">
		                                            <li data-ng-repeat="exam in wowzaCtrl.examSuggestions"
		                                                data-ng-click="wowzaCtrl.addExamToVideos(exam.id,exam.title)">
		                                                @{{exam.title}}
		                                            </li>
		                                        </ul>
		                                        <ul class="select_list_category">
		                                            <li data-ng-repeat="exams in wowzaCtrl.multipleExams"
		                                                data-ng-click="wowzaCtrl.removeExamsFromVideos($index)">
		                                                @{{wowzaCtrl.allExams[exams.id]}}
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
		                                <textarea name="description" data-ng-model="wowzaCtrl.editVideo.description"
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
		                                <input data-validation-name="Cast"  type="text" name="presenter" class="form-control"
		                                       placeholder="{{ __('video::videos.presenter_placeholder') }}"
		                                       data-ng-model="wowzaCtrl.editVideo.presenter">
		                                <p class="help-block" data-ng-show="errors.presenter.has">@{{ errors.presenter.message
		                                    }}</p>
		                            </div>
		                            
		                            <div class="form-group" data-ng-class="{'has-error': errors.published_on.has}">
		                                <label class="control-label">
		                                    Published on
		                                </label>
		                                <input type="text" name="published_on" id="published_on"
		                                       ng-model="wowzaCtrl.editVideo.published_on" datetime-picker size="30"
		                                       placeholder="YYYY-MM-DD" data-validation-name="Published on"
		                                       value="{{old('published_on')}}" class="form-control"
		                                       ng-blur="dateBlur($event,wowzaCtrl.editVideo.published_on)"
		                                       ng-keyup="dateKeyup($event,wowzaCtrl.editVideo.published_on)"/>
		                                <p class="help-block" data-ng-show="errors.published_on.has">@{{
		                                    errors.published_on.message }}</p>
		                            </div>

		                            <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}">
		                                <label class="control-label">
		                                    {{ __('video::videos.status') }}
		                                    <span class="asterisk">*</span>
		                                </label>
		                                <select class="form-control" name="is_active" 
												data-ng-init="wowzaCtrl.editVideo.is_active = '0' "
		                                        data-ng-model="wowzaCtrl.editVideo.is_active" data-validation-name="status">
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
										        data-ng-init="wowzaCtrl.editVideo.trailer_status = '0' "
		                                        data-ng-model="wowzaCtrl.editVideo.trailer_status">
		                                    <option value="0">{{ __('video::videos.no') }}</option>
		                                    <option value="1">{{ __('video::videos.yes') }}</option>
		                                </select>
		                                <p class="help-block" data-ng-show="errors.trailer_status.has">@{{
		                                    errors.trailer_status.message }}</p>
		                            </div>
									
									<div class="form-group">
										<label class="control-label">Stream by</label>
										<div class="col-xs-12 mb-1">
											<label>
												<input ng-model="wowzaCtrl.checkStream" type="radio" name="checkStream" value="Yes">
												<span>HLS Url</span>
											</label>
											<label>
												<input ng-model="wowzaCtrl.checkStream" data-ng-init="wowzaCtrl.checkStream = 'No' " type="radio" name="checkStream" value="No">
												<span>{{__('video::videos.aspect_ratio')}}</span>
											</label>
										</div>
										<div ng-if="wowzaCtrl.checkStream == 'Yes'">
											<div class="form-group"
												 data-ng-class="{'has-error': errors.hls.has}">
												<input type="text" name="hls"
																data-ng-model="wowzaCtrl.editVideo.hls"
																class="form-control"
																placeholder="Enter HLS url"
																value="{{old('hls')}}" />
												<p class="help-block" data-ng-show="errors.hls.has">@{{
												errors.hls.message }}</p>
											</div>
										</div>

										<br><br>
										<div ng-if="wowzaCtrl.checkStream == 'No'">
											<div class="form-group"
												 data-ng-class="{'has-error': errors.post_creator.has}">
												<select class="form-control mb10" name="aspect_ratio"
														data-ng-model="wowzaCtrl.editVideo.aspect_ratio" data-ng-init="wowzaCtrl.editVideo.aspect_ratio = '640X360' " >
													<option value="640X360">640X360</option>
													<option value="1280X720">1280X720</option>
													<option value="1920X1080">1920X1080</option>
												</select>
												<p class="help-block" data-ng-show="errors.aspect_ratio.has">@{{
												errors.aspect_ratio.message }}</p>
											</div>
										</div>
									</div>
									<div class="profile_image_upload">
		                                <div class="form-group" data-ng-class="{'has-error': errors.thumbnail.has}">
		                                    <label class="control-label">{{ __('video::videos.thumnail') }}</label>
		                                    <div class="fileupload fileupload-new"
		                                         data-provides="fileupload">
		                                        <div class="input-append">
		                                            <div class="uneditable-input">
		                                                <i class="glyphicon glyphicon-file" ng-show="wowzaCtrl.editVideo.thumbnail_image.length"></i>
		                                                <span class="fileupload-preview" ng-show="wowzaCtrl.editVideo.thumbnail_image.length">@{{ wowzaCtrl.editVideo.thumbnail_image }}</span>
		                                            </div>
		                                            <span class="btn btn-default btn-file">
		                                                <span ng-hide="wowzaCtrl.editVideo.thumbnail_image.length">{{__('video::videos.select_image')}}</span>
		                                                <span ng-hide="!wowzaCtrl.editVideo.thumbnail_image.length">{{__('video::videos.change')}}</span>
		                                                <input type="file" class="uploadImg" name="image"/>
		                                            </span>
		                                            <img ng-src="@{{ wowzaCtrl.editVideo.thumbnail_image }}" ng-class="{'active': wowzaCtrl.editVideo.thumbnail_image}" class="uploaded_img" alt="">
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
		                                                <i class="glyphicon glyphicon-file" ng-show="wowzaCtrl.editVideo.poster_image.length"></i>
		                                                <span class="fileupload-preview" ng-show="wowzaCtrl.editVideo.poster_image.length">@{{ wowzaCtrl.editVideo.poster_image }}</span>
		                                            </div>
		                                            <span class="btn btn-default btn-file">
		                                                <span ng-hide="wowzaCtrl.editVideo.poster_image.length">{{__('video::videos.select_image')}}</span>
		                                                <span ng-hide="!wowzaCtrl.editVideo.poster_image.length">{{__('video::videos.change')}}</span>
		                                                <input type="file" class="uploadPosterImg" name="image"/>
		                                            </span>
		                                            <img ng-src="@{{ wowzaCtrl.editVideo.poster_image }}" ng-class="{'active': wowzaCtrl.editVideo.poster_image}" class="uploaded_poster_img" alt="">
		                                        </div>
		                                        <p class="intimation">Only jpeg,png files allowed.</p>
		                                    </div>
		                                    <p class="help-block" data-ng-show="errors.poster.has">@{{
		                                        errors.poster.message }}</p>
		                                </div>
		                            </div>
									<div class="form-group" ng-if="false" data-ng-class="{'has-error': errors.scheduled_time.has}">
										<label class="control-label">{{__('video::videos.scheduled_time')}}</label><span class="asterisk">*</span>
										 <input datetime-picker  type="text" name="scheduled_time" id="scheduled_time" data-ng-model="wowzaCtrl.editVideo.scheduled_time" size="30"  placeholder="{{__('video::videos.scheduled_time')}}" data-validation-name = "scheduled_time" value="{{date ( "Y-m-d H:i:s")}}" class="form-control" ng-blur="dateBlur($event,wowzaCtrl.editVideo.scheduled_time)" ng-keyup="dateKeyup($event,wowzaCtrl.editVideo.scheduled_time)"/>
										<p class="help-block" data-ng-show="errors.scheduled_time.has">@{{
											errors.scheduled_time.message }}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
						<div class="padding10">
							<div class="fixed-btm-action">
								<div class="text-right btn-invoice">
									<a class="btn btn-white mr5" href="javascript:;" onclick="window.history.back();">{{__('base::general.cancel')}}</a>
									<button class="btn btn-primary submitbutton">{{__('base::general.submit')}}</button>
								</div>
							</div>
						</div>
	</form>

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
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular/angular-ui.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/latestnews/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('angular/angular-ui.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<link rel="stylesheet" href="https://rawgit.com/kineticsocial/angularjs-datetime-picker/master/angularjs-datetime-picker.css" />
 <script src="https://rawgit.com/kineticsocial/angularjs-datetime-picker/master/angularjs-datetime-picker.js"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/index.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/latestnews.js')}}"></script>
<style>
.st-container {
	overflow-x: inherit;
}
</style>
@endsection
