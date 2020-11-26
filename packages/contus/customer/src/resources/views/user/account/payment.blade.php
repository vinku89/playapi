<section class="dashboard-container">
    <div class="container">
        <div class="row">
            <div class="payment-section text-center">
            <h3 class="subhead">Subscription Plans</h3>
            <p class="subtext">Subscribe to Pro Membership and enjoy the Videos</p>
              <form name="subscribeCustomer" novalidate
                data-base-validator enctype="multipart/form-data">
                <ul class="clearfix collection-payments">
                    <li class="sub-options" data-ng-repeat="record in subscriptions track by $index" data-ng-class="{'plan-activated':(subscription_plan.slug == record.slug)}">
                        <h3>@{{record.name}}</h3>
                        <div class="sub-cost">
                            <strong><i class="fa fa-inr"></i> @{{record.amount}}</strong>
                            <span>(For @{{record.duration}} Days)</span>
                        </div>
                       
                      
                        <div class="sub-limited sub-paybtn"  >
                            <a type="submit"
                                class="btn bt-subscribes ripple" data-ng-if="{{'subscription_plan.slug !== record.slug'}}"  href="{{url('addsubscriber')}}/@{{record.slug}}"  forces-load>Subscribe
                                Now</a>
                                  <a type="submit"
                                class="btn bt-subscribes ripple" data-ng-if="{{'subscription_plan.slug == record.slug'}}"  href="javascript:;">Subscribed
                               </a>
                         </div>
                    </li>
                </ul>
                <div class="payment-icons clearfix">
                <p class="subtext gray-subtext">Your credit card will be charged in INR</p>
                <ul class="clearfix pull-left cs-payment">
                <li><span class="pay-one"></span></li>
                    <li><span class="pay-two"></span></li>
                      <li><span class="pay-three"></span></li>


                </ul>
                 <ul class="clearfix pull-right cs-payment">
                   <li><span class="pay-four"></span></li>
                    <li><span class="pay-five"></span></li>
                </ul>
                </div>
                </form>
            </div>
            <div class="faq-container clearfix">
            <div class="row">
             <h3 class="subhead text-center">FAQ's</h3>
              <p class="subtext text-center">Questions and Answers for your clarifications</p>
              <div class="col-md-6">
              <div class="groups-items">
              <h3>Can I access all the videos by subscribing to Learning Space?</h3>
              <p>Yes, certainly. There are three payment schedules. Depending on the payment schedule you choose, all the videos are accessible up to the period of 360 days/180 days/90 days based on your subscription plan.</p>
             
              </div>
                 <div class="groups-items">
                  <h3>Can I have access to live as well as video lectures through one subscription?</h3>
              <p>Yes, the student can have access to whatever Learning Space produces within the period of the subscription.</p>
            
              </div>
              </div>
                 <div class="col-md-6">
              <div class="groups-items">
                  <h3>Can I download PDF formats / audio formats of the lectures?</h3>
              <p>Yes. Downloading facility is given for both PDF and audio formats. However, using them for commercial circulation as well as gaining monetary benefit is strictly prohibited and it is against the terms and conditions of Learning Space.</p>
              
              
              </div> 
              <div class="groups-items">
              <h3>Are the videos downloadable?</h3>
              <p>No. Videos are not downloadable and it is against the terms and conditions of Learning Space. 
                 Note:
                 If anyone wants to distribute or take commercial gains through circulation, it is against the terms and conditions and your subscription is liable to be rescinded and at the same time necessary proceedings will also be initiated.</p>
            
              </div>
              </div>
            </div>
            </div>
        </div>
    </div>
</section>