<section class="dashboard-container">
	<div class="container">
		<div class="row">
			<div class="col-md-3 pleft0">
				<h5 title="my account" data-mobile-toggle setclass="dashboard-links"
					class="dashboard-bg myaccfor-mobile">
					{{trans('customer::customer.myaccount')}} <span
						class="fa fa-bars pull-right" aria-hidden="true"></span>
				</h5>
				<ul class="list-group dashboard-links">
					<li class="list-group-item active-links ripple"><a ui-sref="profile"
						ui-sref-active="active" title="My Profile" class="my-icon"> <i></i>
							My Profile
					</a></li>
					<li class="list-group-item  ripple"><a ui-sref="following" class="ply-icon"
						ui-sref-active="active" title="My Playlists"><i></i> My Playlists
							<span class="badge"></span></a></li>
					<li class="list-group-item  ripple"><a class="fav-icon"
						ui-sref="favourites" ui-sref-active="active" title="My favourites">
							<i></i> {{trans('customer::customer.myfavourites')}}<span
							class="badge"></span>
					</a></li>
					<li ng-hide="true" class="list-group-item  ripple"><a class="plan-icon"
						ui-sref="subscriptions" ui-sref-active="active" title="My Plan"> <i></i>
							{{trans('customer::customer.myplans')}} <span class="badge"></span>
					</a></li>
                    <li class="list-group-item  ripple"><a class="sub-plan-icon"
                        ui-sref="subscribeinfo" ui-sref-active="active" title="Subscription Plans"> <i></i>
                            Subscription Plans <span class="badge"></span>
                    </a></li>
					<li  class="list-group-item  ripple"><a ui-sref="transactions"
						ui-sref-active="active" class="trans-icon" title="My Transcations"><i></i>
							{{trans('customer::customer.mytransaction')}}<span class="badge"></span>
					</a></li>
					<li class="list-group-item  ripple"><a ui-sref="notifications"
						class="noti-icon" ui-sref-active="active" title="Notifications"> <i></i>
							{{trans('customer::customer.mynotifications')}}<span
							class="badge"></span>
					</a></li>
					@if(Auth::user()->google_user_id || Auth::user()->facebook_user_id) @else
					<li class="list-group-item  ripple"><a ui-sref="password" class="pwd-icon"
						ui-sref-active="active"
						title="{{trans('customer::customer.mychangepassword')}}"> <i></i>
							{{trans('customer::customer.mychangepassword')}}
					</a></li>
					@endif
					<li class="list-group-item  ripple"><a class="logout-icon"
						href="{{url('/auth/logout')}}" title="logout"
						log-out> <i></i>
							Logout
					</a></li>
				</ul>
			</div>
			@yield('profilecontent')
		</div>
	</div>
</section>

