 @section('profilecontent')
<div class="col-md-9 ">
    <div class="row">
         <div class="row">
        <div class="col-md-5 mi-padding0 col-xs-12 col-sm-6">
            <div class="panel panel-default myaccount">
          <div class="panel-body lesspadding">
                  <div class="user-acc-details">
                    <div class="media">
                        <div class="media-left">
                        <span class="cs-uimg-circle">
                            <img alt="" class="media-object"
                                ng-src="@{{profile.profile_picture}}"
                                err-src="{{$cdnUrl('images/user.png')}}"
                                src="{{$cdnUrl('images/user.png')}}"
                                data-holder-rendered="true"
                                style="width: 70px; height: 70px;"></span>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading text-blue">@{{profile.name}} <a title="Edit" class="pull-right edit-info"
                                 ui-sref="editProfile">Edit <i class="fa  fa-pencil"></i></a></h4>
                            <p ng-if="profile.phone"><i class="fa  fa-mobile icon-size"></i> @{{profile.phone}}</p>
                            <p><i class="fa  fa-envelope"></i> @{{profile.email}}</p>
                            <p ng-show="@{{profile.daysleft}}">@{{profile.daysleft}} days left</p>
                            <p ng-if="profile.daysleft===0">Expires Today</p>
                            <p>@{{subscription_plan.name}}</p>
                        </div>
                    </div>
                </div>
  </div>
</div>
        </div>
          <div class="col-md-7 dpv-remove-lp mi-padding0 col-xs-12 col-sm-6">
            <div class="panel panel-default payment-actions">
			  <div class="panel-body">
			   <i class="actions-img"></i>
			   <div class="payment-actions-content">
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
			</div>
        </div>

    </div>

        <div class="recently-viewed">
            <h3 data-ng-hide="recentlyViewed.length == 0">Recently viewed</h3>
            <ul class="videos-grid clearfix">
                <li ng-repeat="video in recentlyViewed">
                    <div class="">
                        <a ui-sref="videoDetail({slug:video.slug})"
                            title="@{{video.title}}"
                            class="video-collections-links"> <span
                            class="video-icon-overlay"> <span
                                class="demo-label"  ng-show="video.is_demo">demo</span>
                                <img src="{{$cdnUrl('images/no-preview.png')}}"
                                ng-src="@{{video.selected_thumb}}"
                                title="@{{video.title}}" alt="@{{video.title}}" > <span
                                class="video-timing"> <span
                                    class="play-icons"></span><span
                                    class="time-label">@{{video.video_duration}}</span>
                            </span>
                        </span> <span class="trending-news-content">
                                <p class="settext-count">@{{video.title}}</p>
                        </span>
                        </a>
                        <div class="followers-count">
                            <p class="clearfix">
                                <span class="followers-counts">@{{video.recent_created_at|convertDate|convertAgoTime}}</span>
                                <button title="Favourite" init-favourite
                                    class="follow-btn ribble btn pull-right wishlist" ng-class="{'favourited-wish':video.is_favourite}">
                                    <i class="wishlist-icon"></i>
                                </button>
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
</div>
@endsection @include('customer::user.account.index')
