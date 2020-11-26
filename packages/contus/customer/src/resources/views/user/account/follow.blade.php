 @section('profilecontent')
<div class="col-md-9">
    <div class="row">
          <div class="col-md-12 col-xs-12 col-sm-12">
    <div class="row">
            <div class="panel panel-default payment-actions">
  <div class="panel-body">
   <i class="actions-img"></i>
  <div class="payment-actions-content except-profile">
     <ul class="video-member-options clearfix" >
                    <li class="" data-ng-repeat="subcrp in subscriptions">
                        <span>Video / PDF / MP3</span>
                        <strong class="rate-card">@{{subcrp.name}}</strong>
                         <strong class="prices"><i class="fa fa-inr"></i> @{{subcrp.amount}}</strong>
                        <span class="video-valid-text">@{{subcrp.duration}} days</span>
                        <a ui-sref="subscribeinfo" class="action-subscription ripple">Subscribe Now</a> 
                    </li>
               </ul> 
   </div>
  </div>
</div></div>
        </div>
        <div class="myfavourite">
            <h3>My Playlists</h3>
               <p class="mynorecordfound" ng-hide="videos.data[0].name">No Playlists Found</p>
            <ul class="videos-grid clearfix ">
                <li ng-repeat="playlist in videos.data track by $index">
                    <a title="@{{playlist.name}}" ui-sref=" playlistList({slug:playlist.slug})" class="video-collections-links">
                        <span class="video-icon-overlay">
                            <span class="demo-label" ng-hide="true">demo</span>
                            <img src="contus/base/images/no-preview.png" ng-src="@{{playlist.playlist_image}}" alt="Owl Image">
                            <span class="video-timing">
                            </span>
                            <div class="videos-count-play">
                                <strong>@{{playlist.videos_count[0].video_count}}</strong>
                                <span>videos</span>
                                <i>icons</i>
                            </div>
                        </span>
                        <span class="trending-news-content">
                            <p class="settext-count">@{{playlist.name}}</p>
                        </span>
                    </a>
                    <div class="followers-count clearfix">
                        <p class="clearfix">
                            <span class="followers-counts">@{{playlist.follow_created_at|convertDate|convertAgoTime}}</span>
                            @if(auth()->user())
                            <button class="follow-btn ribble btn pull-right current-following" title="following" ng-click="unfoollow(playlist.slug)">Unfollow</button>
                            @else
                            <a ui-sref=" playlistList({slug:playlist.slug})" class="follow-btn ripple btn pull-right" title="follow">follow</a>
                            @endif
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection @include('customer::user.account.index')
