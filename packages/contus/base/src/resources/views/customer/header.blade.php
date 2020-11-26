<div id="preloaders" >
    <div id="statuss">
        <i></i>
    </div>
</div>
<div ng-if="false" class="top-news-section" slideshowhide style="display:none">
    <div class="top-news-section-inner clearfix">
        <div class="pull-left">
            <span class="live-label">Next Live On</span>
            <div class="live-video">
                <div class="live-video-add">
                    <a ui-sref="liveDetail({slug:countdownlive.slug})" title="@{{$root.countdownlive.title}}">
                        <img class="tocenter small-img img-responsive" src="{{$cdnUrl('images/no-preview.png')}}" ng-src="@{{$root.countdownlive.selected_thumb}}" alt="Live Video">
                    </a>
                </div>
                <div class="live-video-content">
                    <a ui-sref="liveDetail({slug:countdownlive.slug})" title="@{{$root.countdownlive.title}}">
                        <p>
                            @{{$root.countdownlive.title}}
                            <i class="fa fa-angle-right"></i>
                        </p>
                        <span class="video-icon">@{{$root.countdownlive.scheduledStartTime|convertDate|date:'MMM dd, yyyy'}}</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <div class="countdown"></div>
        </div>
    </div>
    <div class="close-top-news">
        <a href="javascript:;" id="close-tpabel" ng-click="$root.countdownlive.slug = ''">Close</a>
    </div>
</div>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="row nomarginLR0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="" href="{{url('/')}}" title="{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}}">
                    <img src="{{$getBaseAssetsUrl('images/logo.png')}}" alt="{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}}" />
                </a>
            </div>
            <div class="header-seacrhbox">
                <form data-ng-submit="filterSearchGlobal()" search-root list="lists">
                    <div class="input-group stylish-input-group">
                        <input list="searchsuggestions" type="text" class="form-control" placeholder="Search for videos" ng-model="fields.search">
                        <span class="input-group-addon">
                            <button type="submit">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <div id="navbar" class="navbar-collapse collapse top-main-menu pull-right">
                <ul class="nav navbar-nav navbar-right">
                    <li class="browseby dropdown">
                        <a class="dropdown-toggle ripple" id="browse-videos"  aria-haspopup="true" aria-expanded="false" href="javascript:;" title="Browse Videos">Browse Videos</a>
                        <div class="sub-level dropdown-menu">
                            <ul class="second-level" category-list list="lists">
                                <li ng-repeat="subcategory in categoriesList"  class="hoveractive">
                                    <a href="javascript:;" title="@{{subcategory.title}}" class="toggle-childs">

                                        <span class="category-name-overflow">@{{subcategory.title}}</span>
                                       <span class="cs-total-count">(@{{subcategory.child_category.total}})</span>
                                    </a>
                                    <ul class="third-level">
                                        <li ng-repeat="section in subcategory.child_category.data">
                                            <a ng-click="subCategoryClik()" ui-sref="categorysection({category:subcategory.parent_category.slug,slug:section.slug})" title="@{{section.title}}">
                                                @{{section.title}} (@{{section.videos_count[0].count}})
                                                <i class="fa fa-caret-right"></i>
                                            </a>
                                        </li>
                                        <li class="view-all-course ripple">
                                            <a title="View all courses" class="" ng-click = "viewAllCource(subcategory.slug)" ui-sref="categoryvideos({slug:subcategory.parent_category.slug})"> View All</a>
                                        </li>
                                    </ul>
                                </li>@{{categoriesList.lenght | json }}
                                <li class="nocategory" ng-if="!(categoriesList.length)">
                                {{trans('base::general.category_notfound') }}
                                </li>
                            </ul>
                            <div class="view-all" ng-show="categoriesList.length > 1">
                                <a ui-sref="category" class="ripple" title="View entire list">
                                    <i class="fa fa-list-ul"></i>
                                    View entire list
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="playlist">
                        <a ui-sref="playlist" class="ripple" title="Playlists">Playlists</a>
                    </li>
                    <li class="live-videos">
                        <a ui-sref="livevideos" class="ripple" title="Live Videos">Live Videos</a>
                    </li>
                    @if(\Auth::check())
                    <li class="notifications">
                        <a ui-sref="notifications" title="notifications">
                            <span class="notify-mobi">Notifications</span>
                        </a>
                    </li>
                    <li class="logged-user dropdown">
                        <a title="{{Auth::user()->name}}">
                            <span class="username">Hi, {{Auth::user()->name}}</span>
                            <span class="user-img">
                                <img alt="profileimage" class="user-img" ng-src="{{auth()->user()->profile_picture}}"  err-src="{{$cdnUrl('images/user.png')}}" src="{{$s3BucketUrl('images/user.png')}}">
                            </span>
                        </a>
                         <ul class="dropdown-menu">
			                <li><a ui-sref="profile" class="my-icon" title="My Profile"><i></i>My Profile</a></li>
			                <li><a href="{{url('/auth/logout')}}" class="logout-icon" title="Logout" log-out><i></i>Logout</a></li> 
			              </ul>
                    </li>
                    @else
                    <li class="login">
                        <a ui-sref="login" ui-sref-active="active ripple" title="login">login</a>
                    </li>
                    <li class="signup">
                        <a ui-sref="signup" ui-sref-active="active ripple" title="Sign up">Sign up</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
@if(Session::has('multiple_login'))
<div class="login_flash_message" ng-hide="login_msg">
</div>
@endif
