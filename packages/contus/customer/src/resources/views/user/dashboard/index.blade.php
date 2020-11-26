@extends('base::customer.default') @section('content')
@include('base::partials.errors')
<div id="controllerpreloader"
    ng-class="{'loader':($root.httpLoaderLocalElement||($root.httpCount !== 0))}">
    <div id="status" >
    @if(app('request')->input('type') == 'mobile')
    <i></i>
    @endif
    </div>
</div>
<toast></toast>
<ui-view class="animated" ng-class="{'animated-show':($root.httpCount === 0),'animated-hide':($root.httpCount !== 0)}" ></ui-view>
@endsection @section('scripts')
<script src="http://platform.twitter.com/widgets.js"></script>
<script src="{{$getCustomerAssetsUrl('js/route/route.js?v=')}}{{env('ASSERT_VERSION',time())}}"></script>
@if($auth->check() && isset($auth->user()->id)) @else
<script src="{{$getCustomerAssetsUrl('js/auth/controller/SignupController.js?v=')}}{{env('ASSERT_VERSION',time())}}"></script>
@endif @endsection
