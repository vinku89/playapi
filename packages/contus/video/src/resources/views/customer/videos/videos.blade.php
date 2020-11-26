
<section class="breadcrumbs-section">
    <div class="container">
        <div class="row">
            <nav class="breadcrumb">
                <a class="breadcrumb-item" href="{{URL::to('/')}}">Home</a>
                <a class="breadcrumb-item" ui-sref="category" >
                  Categories
                </a>
                <a class="breadcrumb-item" ui-sref="categoryvideos({slug:categories.slug})">
                    <span>@{{categories.title}}</span>
                </a>
            </nav>
        </div>
    </div>
</section>
<section class="categorybrowse-videos">
    <div class="container">
        <div class="row">
            <div class="col-md-3 pleft0">
                <div class="mobile-filter dashboard-bg">
                    <span class="showmobilefilter" data-mobile-toggle setclass="mobileFilter-links">
                        Filter By
                        <i class="fa  fa-filter pull-right"></i>
                    </span>
                </div>
                <div class="panel-group filter-options-container mobileFilter-links" id="accordion">
               <strong class="total-category">Categories <a ng-if="(categoryFilter.length||tagsFilter.length)?true:false" href="javascript:;" ng-click="clearAllFilters();" class="clearll" title="clear all" > Clear all</a></strong>
                    <div data-ng-repeat="subcategory in categories.child_category" class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" title="@{{subcategory.title}} - @{{(getLength(subcategory.child_category))?getLength(subcategory.child_category)+' Video(s)':'No videos'}}" ng-class="{'after':(getLength(subcategory.child_category) == 0 )}" data-toggle="collapse" href="javascript:;" data-parent="accordion" data-target="#@{{subcategory.slug}}">
                                    <strong>@{{subcategory.title}} </strong>
                                    <span>(@{{getLength(subcategory.child_category)}})</span>
                                </a>
                            </h4>
                        </div>
                        <div id="@{{subcategory.slug}}" class="panel-collapse collapse " ng-show="getLength(subcategory.child_category)" ng-class="(classinchecker[subcategory.slug]) ?'in':''">
                            <div class="panel-body filter-options" custom-scroll="{ 'autoHide': false }" >
                                <ul class="clearfix">
                                    <li data-ng-repeat="section in Collection.child_category">
                                        <div class='input'>
                                            <input ng-click="toggleSelectionCategory(section.slug)" ng-checked="categoryFilter.indexOf(section.slug)> -1?true:false" ng-model="filterCategory" type="checkbox" id=@{{section.slug}}>
                                            <label for="@{{section.slug}}"> @{{section.title}} </label>
                                            <span class="filtered-videos-count">(@{{section.videos_count[0].count}})</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle tags-nonaccord" data-toggle="collapse" href="javascript:;">
                                    <strong>
                                        tags
                                        <span>(@{{getLength(tags)}})</span>
                                    </strong>
                                </a>
                            </h4>
                        </div>
                        <div class="panel-body searched-tags">
                        <div class="for-scroll" custom-scroll="{ 'autoHide': false }" ><button class="btn btn-default" data-ng-class="tagsFilter.indexOf(tagId)> -1?'active':''" data-ng-repeat="(tagId,tag) in tags"  scroll-toptag ng-click="toggleSelectionTags(tagId)">@{{tag}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 mi-padding0">
                <div class="row">
                    <div data-ng-if="live.length>0" class="scheduled-filteredvideos">
                        <h3>Scheduled Live Videos</h3>
                        <div id="scheduled-filteredvideos">
                            <div class="item"   data-initialize-owl-carousel data-owl-carousel-options="videoOwlCarouselOptions" data-show-nav="true" ng-repeat="video in live">
                                <a ui-sref="liveDetail({slug:video.slug})" class="video-icon-overlay">
                                  <span class="demo-label" ng-show="video.liveVideoTime">Live Now</span>
                                    <img scheduledStartTime ng-src="@{{video.selected_thumb}}" src="{{$cdnUrl('images/no-preview.png')}}" alt="Owl Image">
                                	<span class="css-schedules-video-timing" ng-show="video.liveVideoTime">
                                     <span class="play-icons"></span>
                                     </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="searched-videos-grid">
                        <h3 class="col-md-10">
                            @{{videos.total}} results found for
                            <span>@{{($root.fields.search)?$root.fields.search:categories.title}}</span>
                        </h3>
                        <div class="col-md-2 text-right list-grid-bar" ng-init="searchedvideoslist=0;">
                            <a href="javascript:;" ng-click="searchedvideoslist=0" ng-class="{'isactive':!searchedvideoslist}">
                                <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
                            </a>
                            <a href="javascript:;" ng-click="searchedvideoslist=1" ng-class="{'isactive':searchedvideoslist}">
                                <span class="fa fa-list-ul" aria-hidden="true"></span>
                            </a>
                        </div>
                        <div class="suggested-searches clearfix text-left" ng-if="(categoryFilter.length||tagsFilter.length)?true:false">
                            <div ng-init="showmorefilterscattags=1;" class="showmorefilterscattags">
                                <label class="">Selected filters :</label>
                                <span class="cat-name-ref" ng-repeat="categoryName in categoryFilter">@{{sections[categoryName]}}<a href="javascript:;" ng-click="toggleSelectionCategory(categoryName)" title="close"></a></span>
                                <span class="cat-name-ref" ng-repeat="tagName in tagsFilter">@{{tags[tagName]}}<a href="javascript:;" ng-click="toggleSelectionTags(tagName)" title="close"></a></span>
                            </div>
                             <a title="Show More" class="cs-result-more" data-ng-show ="showmorefilterscattags" ng-click = "showmorefilterscattags = !showmorefilterscattags" href="javascript:;"><strong>+ </strong>show more</a>
                            <a title="Show Less" class="cs-result-less" data-ng-hide ="showmorefilterscattags" ng-click = "showmorefilterscattags = !showmorefilterscattags" href="javascript:;"><strong>- </strong>show less</a>
                        </div>

                        <ul class="videos-grid clearfix" ng-class="{'searched-videos-list':searchedvideoslist}">
                            <li data-ng-repeat="(id,video) in videos.data">
                                <div class="forgrid" ng-hide="searchedvideoslist"> <a ui-sref="videoDetail({slug:video.slug})" class="video-collections-links">
                                        <span class="video-icon-overlay">
                                            <span class="demo-label"  ng-show="video.is_demo">demo</span>
                                            <img src="{{$cdnUrl('images/no-preview.png')}}" ng-src="@{{(video.thumbnail_image)?video.thumbnail_image:video.selected_thumb}}" alt="Owl Image">
                                            <span class="video-timing">
                                                <span class="play-icons"></span>
                                                <span ng-show="video.video_duration" class="time-label"> @{{video.video_duration}}</span>
                                            </span>
                                            <div ng-hide="true" class="videos-count-play">
                                                <strong>31</strong>
                                                <span>videos</span>
                                                <i>icons</i>
                                            </div>
                                        </span>
                                        <span class="trending-news-content">
                                            <p class="settext-count">@{{video.title.trunc(75)}}</p>
                                        </span>
                                    </a>
                                    <div class="followers-count">
                                        <p class="clearfix">
                                            <span class="followers-counts">@{{video.created_at|convertDate|convertAgoTime}}</span>
                                           @if(auth()-> user())
                                           <a class="cs-dwn-icons pull-right"  data-toggle="modal" data-target="#downloading-options"
                                                     href=""><i class="dwn-icon"></i></a> 
                                                      @endif
                                             <button @if(auth()-> user())  init-favourite
                                                     @else
                                                     ui-sref="login"
                                                     @endif
                                                     title="My Favourite" class="follow-btn btn pull-right wishlist init-favourite" ng-class="{'favourited-wish':video.is_favourite}">
                                                <i class="wishlist-icon"></i>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                                <div class="forlist" ng-show="searchedvideoslist">
                                    <div class="media"><div class="media-left">
                                           <a ui-sref="videoDetail({slug:video.slug})" >
                                           <span class="video-icon-overlay">
                                           <span class="demo-label"  ng-show="video.is_demo">demo</span>
                                           <img class="media-object" src="{{$cdnUrl('images/no-preview.png')}}" ng-src="@{{(video.thumbnail_image)?video.thumbnail_image:video.selected_thumb}}" alt="Owl Image" style="width: 196px; height: 120px;">
                                           <span class="video-timing">
                                               <span class="play-icons"></span>
                                           </span>
                                       </span>
                                           </a>
                                       </div>
                                        <div class="media-body">
                                            <a ui-sref="videoDetail({slug:video.slug})">
                                                <h4 class="media-heading">
                                                    <span class="trending-news-content">
                                                        <p class="settext-count">@{{video.title.trunc(80)}}</p>
                                                    </span>
                                                </h4>
                                            </a>
                                            <div class="">
                                                <p>@{{video.short_description.trunc(200)}}</p>
                                                <div class="followers-count">
                                                    <p class="clearfix">
                                                        <span class="time-label">
                                                            <i class="fa fa-clock-o"></i>
                                                            @{{video.video_duration}}
                                                        </span>
                                                        <span class="timedot"></span>
                                                        <span class="followers-counts">@{{video.created_at|convertDate|convertAgoTime}}</span>
                                                        <button @if(auth()-> user())  init-favourite
                                                     @else
                                                     ui-sref="login"
                                                     @endif
                                                     title="My Favourite" class="follow-btn btn pull-right wishlist init-favourite" ng-class="{'favourited-wish':video.is_favourite}">
                                                            <i class="wishlist-icon"></i>
                                                        </button>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="show-more-videos mt-inc text-center" ng-show="videos.next_page_url !== null">
                            <a href="javascript:;" class="btn btn-green ripple " ng-click="loadmorerelatedvideo()">Show More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
