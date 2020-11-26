<head>
    <title>{{config ()->get ( 'settings.general-settings.site-settings.page_title' )}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="{{config ()->get ('settings.general-settings.site-settings.page_description')}}">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-api-url" content="{{ url('api/v1') }}">
    <meta name="base-template-url" content="{{URL::to('/')}}">
    <meta name="public-access-token" content="8YZKroRBFPV0aX0Hz9YTydI6gZq5pu">
    <meta name="s3bucketurl" content="{{$cdnUrl('images/placeholder-1501669136.jpg')}}">
    <meta name="assertversion" content="{{env('ASSERT_VERSION',time())}}">
    <meta name="ganalticsid" content="{{env('G_ANALTICS_ID')}}">
    <meta name="google-site-verification" content="Sr-Bhfz160DvluSPE83IH_SDRkOXQULbD_iBxzSgPPQ" />
    <base href="{{URL::to('/')}}/">
    @if($auth->check() && $authUser = $auth->user())
    <meta name="access-token" content="{{$authUser->access_token}}">
    <meta name="user-id" content="{{$authUser->id}}">
    @endif

    <link rel="shortcut icon" href="{{asset('assets/images').'/'.config( 'settings.general-settings.site-settings.favicon' )}}">
    <!--[if lt IE 9]>
	<script src="{{$getBaseAssetsUrl('js/html5shiv.js')}}"></script>
	<![endif]-->
    <!-- style-->
    <link href="{{$getBaseAssetsUrl('css/common.css?v=')}}{{env('ASSERT_VERSION',time())}}" type="text/css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet">
    @if(app('request')->input('type') == 'mobile')
    <link href="{{$getBaseAssetsUrl('css/mobile.css')}}"  type="text/css" rel="stylesheet">
    @endif
    @section('stylesheet')
    @show
</head>