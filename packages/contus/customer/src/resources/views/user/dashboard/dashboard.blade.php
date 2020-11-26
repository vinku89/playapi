<div class="homepage">
    <section class="banner-section">
        <div class="home-banner-container"><a href="@{{bannerImage.url}}">
            <div class="cs-video-container">
                <img class="tocenter img-responsive main-banner" src="{{$cdnUrl('images/banner.jpg')}}" ng-if="bannerImage.banner_image === ''">
                <p ng-style="{'background-image': 'url(' + bannerImage.banner_image + ')'}" class="tocenter bg img-responsive main-banner"  alt="Innovation &amp; Creativity" ng-if="bannerImage.type == 'image'"></p>
                <video class="tocenter img-responsive main-banner" autoplay loop ng-src="@{{bannerImage.video_image}}" src="@{{bannerImage.video_image}}" ng-if="bannerImage.type == 'video'">

            </div>
            <div class="banner-caption">
               <div class="container">
                   <div class="banner-caption-inner">
                      <a href="@{{bannerImage.url}}">
                         <div class="banner-content">
                             <div class="banner-content-icon"><i class="banner-play"></i></div>
                             <div class="banner-content-text">
                                <h3>@{{bannerImage.title}}</h3>
                                <p>@{{bannerImage.category_title}}</p> 
                             </div>
                         </div>
                      
                   </div>
               </div>
            </div></a>

    </section>
    
    <section class="scheduled-live-videos">
        <div class="container">
            <div class="row nomarginLR0  cs-center-div-container"  ng-if="false" data-ng-show="live.length">
                <h2 class="scheduled-live-videos-title">
                    <span class="video-sound"></span>
                    Scheduled Live Videos
                </h2>
                <div ng-repeat="video in live" class="col-md-3 mi-padding0 col-sm-6 cs-center-div">
                    <div class="row live-video-list">
                        <div class="live-video">
                            <a ui-sref="liveDetail({slug:video.slug})" title="@{{video.title}}">
                                <span class="demo-label" ng-show="video.liveVideoTime">Live Now</span>
                                <span class="live-video-container">
                                    <img class="img-responsive" ng-src="@{{video.selected_thumb}}" src="" />
                                    <span class="live-date" ng-show="video.liveVideoTime">
                                        <strong></strong>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="current-affiars">
                <h2 class="text-center current-affiars-title">Endless choice of videos to pick from..</h2>
                <h3 style="margin: 0px 0 44px; font-size: 20px; color: #0f1010; font-weight: 400; line-height: 25px;">Be spoilt for choices with our ever-growing collection of regional serials, music videos, movies & much more. 
                </h3>
                <div class="row nomarginLR0">
                    <div class="tabbable-panel cs-examgroups"><article class="vplay-artical-section" ng-repeat="cat in exams">
                        <div class="vplay-h3" data-ng-bind="cat.title" ></div>
                        <div>
                        <div class="item" ng-if="$even||$last" data-initialize-owl-carousel data-owl-carousel-options="examOwlCarouselOptions" ng-repeat="examvideos in cat.exams" title="@{{video.video_title}}">
                        	
                            <div class="items_@{{$index-1}} twoitems" data-ng-if="cat.exams[$index-1] && (!$last || $even)" ng-init="examvideo = cat.exams[$index-1]">
                                <a href="javascript:;" ui-sref="examList({slug:examvideo.slug})" title="@{{examvideo.name}}" class="course-details">
                                <img class="img-responsive" ng-src="@{{examvideo.group_image}}" src="" />
                                <strong class="course-details-title">
                                    @{{examvideo.name}}
                                    <span> @{{examvideo.group_videos[0].count}} Videos </span>
                                </strong>
                                </a>
                            </div>
                            <div class="items_@{{$index}} twoitems" ng-init="examvideo = cat.exams[$index]">
                                <a href="javascript:;" ui-sref="examList({slug:examvideo.slug})" title="@{{examvideo.name}}" class="course-details">
                                <img class="img-responsive" ng-src="@{{examvideo.group_image}}" src="" />
                                <strong class="course-details-title">
                                    @{{examvideo.name}}
                                    <span> @{{examvideo.group_videos[0].count}} Videos </span>
                                </strong>
                                </a>
                            </div>
                        </div></div></article>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="trending-videos">
        <h2 class="text-center trending-title">Watch what the world is watching</h2>
      <h3 style="text-align: center; margin: 0px 0 44px; font-size: 20px; color: #0f1010; font-weight: 400; line-height: 25px;">Latest popular videos hand-picked from our video libraries
      </h3>
        <div class="container">
            <div class="row nomarginLR0">
                <div class="tabbable-panel">
                    <div class="item" data-initialize-owl-carousel data-owl-carousel-options="videoOwlCarouselOptions" ng-repeat="video in trending.data" title="@{{video.video_title}}">
                        <div class="video-icon-overlay">
                            <a ui-sref="videoDetail({slug:video.slug})">
                                <span class="demo-label" ng-show="video.is_demo">demo</span>
                                <img src="" ng-src="@{{video.selected_thumb}}" alt="@{{video.video_title}}" style="weight: 237px; height: 135px;">
                                <div class="video-timing">
                                    <span class="play-icons"></span>
                                    <span class="time-label"> @{{video.video_duration}}</span>
                                </div>
                            </a>
                        </div>
                        <div class="trending-news-content">
                            <a ui-sref="videoDetail({slug:video.slug})">
                                <p class="settext-count">@{{video.title}}</p>
                            </a>
                            <span class="news-status"> @{{video.categories[0].title}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="client-says"  ng-show="testimonial.length">
        <div class="container">
            <div class="row nomarginLR0">
                <h2 class="client-says-title">
                    <span class=signal-icons></span>
                    Inner Voices... From The Core of the Hearts
                </h2>
                <div id="clientfeedback">
                    <div class="item" data-initialize-owl-carousel data-owl-carousel-options="clientOwlCarouselOptions" ng-repeat="content in testimonial">
                        <div class="media">
                            <div class="media-left">
                                <img alt="@{{content.name}}" class="media-object img-circle" ng-src="@{{content.image}}" err-src="{{$cdnUrl('images/user.png')}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 94px; height: 94px;">
                            </div>
                            <div class="media-body">
                                <p>@{{content.description}}</p>
                                <h4 class="media-heading posted-by">@{{content.name}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="news-glance" data-ng-show="latestnews.length">
                    <h2 class="news-glance-title">Insight</h2>
                    <div class="">
                        <div id="news-glance" data-initialize-owl-carousel data-owl-carousel-options="newsOwlCarouselOptions" ng-repeat="blog in latestnews">
                            <div class="item">
                                <a title="@{{blog.title}}" ui-sref="blogdetail({slug:blog.slug})" class="glance-videos effect-lexi">
                                    <img src="" ng-src="@{{blog.post_image}}" class="img-responsive" alt="@{{blog.title}}">
                                    <span class="date-label">
                                        @{{blog.created_at|convertDate|date:'MMM'}}
                                        <strong>@{{blog.created_at|convertDate|date:'dd'}}</strong>
                                    </span>
                                </a>
                                <p class="glance-news-name">@{{blog.title}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center " data-ng-show="latestnews.length">
                    <a title="View all" ui-sref="blog" class="btn btn-green ripple ">View all</a>
                </div>
            </div>
        </div>
    </section>
    <section class="section-ideas">
        <div class="container">
            <div class="row nomarginLR0">
                <h2 class="text-center ideas-title">Dive Into a World Of Uninterrupted Entertainment</h2>
                <div class="col-lg-4 col-sm-4 text-center ideas-content">
                    <div class="ideas-img">
                        <img class="tocenter img-responsive" src="contus/base/images/innovation.png" alt="Innovation &amp; Creativity">
                    </div>
                    <h3>Flexi-subscriptions</h3>
                    <p>Personalized subscription plans to help you get you what you want without burning a hole in your pocket.</p>
                </div>
                <div class="col-lg-4 col-sm-4 text-center ideas-content">
                    <div class="ideas-img">
                        <img class="tocenter img-responsive" src="contus/base/images/study-material.png" alt="Innovation &amp; Creativity">
                    </div>
                    <h3>Watch Anywhere</h3>
                    <p>Mobile, tablet, TV, desktop - you pick the choice, we will stream glitch-free content over to you.
					</p>
                </div>
                <div class="col-lg-4 col-sm-4 text-center ideas-content">
                    <div class="ideas-img">
                        <img class="tocenter img-responsive" src="contus/base/images/class-room.png" alt="Innovation &amp; Creativity">
                    </div>
                    <h3>Discover Entertainment</h3>
                    <p>Intelligent video suggestions based on your recent watch history, preferences and social trends.
</p>
                </div>
            </div>
        </div>
    </section>
    <section class="appStore-container">
        <div class="container">
            <div class="row nomarginLR0">
                <div class="text-center">
                    <h2>Watch Tv Anywhere, Any device, Anytime</h2>
                    <p class="download-app">
                    Watch your daily dose of entertainment anytime you want it on our Android & iOS apps.
                        <i style="display: none"></i>
                    </p>
                    <a class="appstore-ic" target="_blank" href="{{config ()->get ( 'settings.general-settings.site-settings.apple_appstore_url' )}}" title="App Store"> </a>
                    <span class="either-app"></span>
                    <a target="_blank" class="playstore-ic" href="{{config ()->get ( 'settings.general-settings.site-settings.google_playstore_url' )}}" title="Google play"> </a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    @if(!auth()->user())
    <section class="subscrubtion-section">
        <div class="container">
            <div class="subscrubtion-section-inner text-center">
                <h3>Let’s stay in touch </h3><br>
                <h4>Be in our loop for the latest updates about video releases, uploads, subscription offers & much more.</h4>
                <div class="subscriptions-form ">
                    <form id="subscriptions-form" class="form-inline">
                        <div class="form-group">
                            <div class="inner-addon left-addon">
                                <i class="userimg-line"></i>
                                <input type="text" class="form-control name-fileds" data-ng-model="$root.pass.name" id="" placeholder="Your Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="inner-addon left-addon">
                                <i class="msgimg-line"></i>
                                <input type="text" class="form-control" data-ng-model="$root.pass.email" id="exampleInputPassword3" placeholder="Email Address">
                            </div>
                        </div>
                        <button title="Get Started Now" type="submit" class="btn white-line-button" ui-sref="signup(pass)">get started now</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
