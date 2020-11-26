@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<div ng-app="staticPage" ng-controller="StaticController">
    <div class="menu_container clearfix">
        <div class="page_menu pull-left">
            <ul class="nav">
                {{-- <li>
                    <a href="{{url('admin/latest')}}">{{trans('cms::latestnews.latest_news')}}</a>
                </li> --}}
                <li>
                    <a href="{{url('admin/emails')}}">{{ trans('cms::staticcontent.email') }}</a>
                </li>
                {{-- <li>
                    <a href="{{url('admin/smsTemplate')}}">{{ trans('cms::staticcontent.sms') }}</a>
                </li> --}}
                <li>
                    <a href="{{url('admin/staticContent')}}" class="active">{{ trans('cms::staticcontent.static_content') }}</a>
                </li>
                {{-- <li>
                    <a href="{{url('admin/testimonial')}}" class="">{{ trans('cms::staticcontent.testimonial') }}</a>
                </li> --}}
                <li>
                    <a href="{{url('admin/banner')}}" class="">{{ trans('cms::staticcontent.banner') }}</a>
                </li>
                	{{-- <li><a href="{{url('admin/contactus')}}" class="">{{
		         trans('cms::staticcontent.contactus') }}</a></li> --}}
            </ul>
        </div>
    </div>
    <div class="pageheader clearfix">
        <span ng-hide="true" id="inititate">{{$id}}</span>
        <span ng-hide="true" id="rules">{!! json_encode($rules) !!}</span>
        <h2  class="titleseperatepage">{{trans('cms::staticcontent.edit_new_content')}}</h2>
    </div>
    <form name="staticcontentForm" method="POST" data-ng-submit="submitform($event)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="contentpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="add_form clearfix">
                                <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                                    <label class="control-label">
                                        {{trans('cms::staticcontent.title')}}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <input type="text" name="title" data-ng-model="staticData.title" class="form-control" placeholder="{{trans('cms::staticcontent.title_placeholder')}}" value="{{old('title')}}" />
                                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                                </div>
                                <div class="form-group" data-ng-class="{'has-error': errors.content.has}">
                                    <label class="control-label">
                                        {{trans('cms::staticcontent.content')}}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <textarea ui-tinymce="{resize:false,height:400}" name="content" class="form-control" data-ng-model="staticData.content" placeholder="{{trans('cms::staticcontent.content_placeholder')}}" value="{{old('content')}}"></textarea>
                                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.content.message }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="padding10">
                <div class="fixed-btm-action">
                    <div class="text-right btn-invoice">
                        <a class="btn btn-white mr5" href="{{url('admin/staticContent')}}">{{trans('base::general.cancel')}}</a>
                        <button class="btn btn-primary">{{trans('base::general.submit')}}</button>
                    </div>
                </div>
            </div>

    </form>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('angular/angular-ui.js')}}"></script>
<script src="{{$getBaseAssetsUrl('tinymce/tiny_mce.js')}}"></script>
<script src="{{$getBaseAssetsUrl('tinymce/jquery.tinymce.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/static/static.js')}}"></script>
<script type="text/javascript">
    $('#tree').checktree();
        // <![CDATA[
             window.Mara = {
            		 staticContentForm : {
                    rules : {!! json_encode($rules) !!}
                },
                route : {

                },
                locale : {!! json_encode(trans('validation')) !!}
             };

        // ]]>
    </script>
@endsection
