    @section('profilecontent')<div class="col-md-9">
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
            <h3>My Favourites</h3>
             <p class="mynorecordfound" ng-hide="videos.data[0].title"> No Favourites Found </p>
            <ul class="videos-grid clearfix">
                <li data-ng-repeat="(id,video) in videos.data">
                    <div title ="@{{video.title}}">
                        <a  title="@{{video.title}}" ui-sref="videoDetail({slug:video.slug})"
                            class="video-collections-links"> <span
                            class="video-icon-overlay"> <span
                                class="demo-label"  ng-show="video.is_demo">demo</span>
                                <img
                                ng-src="@{{video.selected_thumb}}"
                                src="{{$cdnUrl('images/no-preview.png')}}"
                                alt="Owl Image"> <span
                                class="video-timing"> <span
                                    class="play-icons"></span><span
                                    ng-show="video.video_duration" class="time-label">
                                        @{{video.video_duration}}</span>
                            </span>
                                <div ng-hide="true"
                                    class="videos-count-play">
                                    <strong>31</strong> <span>videos</span>
                                    <i>icons</i>
                                </div>
                        </span><span class="trending-news-content">
                                <p class="settext-count">@{{video.title}}</p>
                        </span>
                        </a>
                        <div class="followers-count clearfix">
                            <p class="clearfix">
                                <span class="followers-counts">@{{video.favourite_created_at|convertDate|convertAgoTime}}</span>
                                <button title="My Favourite" ng-click="unfoollow(video.slug)"
                                    class="follow-btn ripple btn pull-right wishlist favourited-wish"><i class="wishlist-icon"></i></button>
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>    @endsection
@include('customer::user.account.index')