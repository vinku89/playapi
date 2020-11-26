
<section class="breadcrumbs-section">
    <div class="container">
        <div class="row">
            <nav class="breadcrumb">
                <a class="breadcrumb-item" href="{{URL::to('/')}}">Home</a>
                <a class="breadcrumb-item" ui-sref="playlist">
                    <span>Playlists</span>
                </a>
            </nav>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row mr-norow">
        	<div class="cs-title"><span class="center-title">Playlists</span>
        	<select name="sortby" class="pull-right cs-select" data-ng-model="sortby" ng-change="selectSortBy(sortby)">>
	  			<option value="recently">Recently Added</option>
	  			<option value="mostpopular">Most Popular</option>	  			
	  			<option value="latest">Latest Video Added</option>
			</select></div>
            <div class="playlist-collections-container">
                <div class="cs-playlist-collections-slider clearfix">
                    <div class="item" ng-repeat="playlist in category.data">
                        <a title="@{{playlist.name}}" ui-sref="playlistList({slug:playlist.slug})" class="video-collections-links">
                            <span class="video-icon-overlay">
                                <span class="demo-label" ng-hide="true">demo</span>
                                <img  ng-src="@{{playlist.playlist_image}}" src="{{$cdnUrl('images/no-preview.png')}}" alt="Owl Image">

                                <span class="video-timing">
                                </span>
                                <div class="videos-count-play" >
                                    <strong>@{{playlist.videos_count[0].video_count}}</strong>
                                    <span>videos</span>
                                    <i>icons</i>
                                </div>
                            </span>
                            <span class="trending-news-content">
                                <p class="settext-count">@{{playlist.name}}</p>
                                <span class="createdat" ng-show="sortby == 'recently'">Created @{{playlist.created_at}}</span>
                                <span class="updatedat" ng-show="sortby != 'recently'">Updated @{{playlist.updated_at}}</span>
                            </span>
                        </a>
                        <div class="followers-count clearfix">
                            <p class="clearfix">
                                @if(auth()->user())
                                <button title="@{{(playlist.following)?'un follow':'follow'}}" class="follow-btn ripple btn pull-right" ng-click="togglefollowplaylist(playlist)" ng-class="{'current-following':playlist.auth_follower.length}">@{{(playlist.auth_follower.length)?'Unfollow':'Follow'}}</button>
                                @else
                                <a ui-sref="playlistList({slug:playlist.slug})" class="follow-btn ripple btn pull-right" title="Follow">follow</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                 <div class="text-center adjusting-button-25 " ng-show="category.next_page_url !== null">
                <a href="javascript:;" title="Show More" class="btn btn-green" ng-click="loadmorecategories()">Show More</a>
            </div>
            </div>
           
        </div>
    </div>
</section>
