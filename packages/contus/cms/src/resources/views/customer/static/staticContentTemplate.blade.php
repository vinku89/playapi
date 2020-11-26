<section class="contact-us" data-ng-init="getcontactusrules()">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <form name="staticcontentForm" method="POST" data-base-validator data-ng-submit="savecontactus($event)" enctype="multipart/form-data" novalidate>
        {!! csrf_field() !!}
        <div class="location-map" ng-if="$root.location.absUrl()=== '{{URL::to('/')}}/content/contact-us' || $root.location.absUrl()=== '{{URL::to('/')}}/content/contact-us?type=mobile'">
            <iframe id="map" width="100%" height="450"></iframe>
            <span id="address" style="display: none;">Learning Space Educational Services Pvt. Ltd,Eluru Road, Vijayawada</span>
        </div>
        <div class="container" ng-if="$root.location.absUrl()!== '{{URL::to('/')}}/content/contact-us' && $root.location.absUrl() !== '{{URL::to('/')}}/content/contact-us?type=mobile'">
            <div class="row">
                <div class="col-md-12">
                    <h2>@{{staticcontent.title}}</h2>
                    <p ng-bind-html="to_trusted(staticcontent.content)" class="static-content"></p>
                </div>
            </div>
        </div>
        <div class="container" ng-if="$root.location.absUrl()=== '{{URL::to('/')}}/content/contact-us' || $root.location.absUrl()=== '{{URL::to('/')}}/content/contact-us?type=mobile'">
            <div class="row">
                <div class="col-md-7 col-xs-12 col-sm-12">
                    <div class="contact-form text-center">
                        <h3>Let's talk</h3>
                        <p>
                            <strong>Questions? Comments? We'd love to hear from you</strong>
                            please don't hesitate to get in touch
                        </p>
                        @if(Auth::user() != "")
                        <div class="enquiry row text-left" data-ng-init="authContactUs({{Auth::user()}})">
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                                    <label class="control-label">
                                    </label>
                                    <input type="text" name="name" data-ng-model="staticcontent.name" value="{{Auth::user()->name}}" class="form-control" placeholder="{{trans('cms::staticcontent.name_placeholder')}}" />
                                    <p class="help-block" data-ng-show="errors.name.has">@{{errors.name.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
                                    <label class="control-label">

                                    </label>
                                    <input type="text" name="email" data-ng-model="staticcontent.email" class="form-control" placeholder="{{trans('cms::staticcontent.email_placeholder')}}" />
                                    <p class="help-block" data-ng-show="errors.email.has">@{{errors.email.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': errors.phone.has}">
                                    <label class="control-label">

                                    </label>
                                    <input type="text" name="phone"  maxlength="10" data-ng-model="staticcontent.phone" class="form-control" placeholder="{{trans('cms::staticcontent.phone_placeholder')}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                                    <p class="help-block" data-ng-show="errors.phone.has">@{{errors.phone.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group" data-ng-class="{'has-error': errors.message.has}">
                                    <label class="control-label">

                                    </label>
                                    <textarea name="message" data-ng-model="staticcontent.message" class="form-control" placeholder="{{trans('cms::staticcontent.message_placeholder')}}" row="7"></textarea>
                                    <p class="help-block" data-ng-show="errors.message.has">@{{errors.message.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': recaptchaerror}">
                                    <div class="g-recaptcha" data-sitekey="6LeFWDAUAAAAAEGNOrR0uGTKLS56dobl3K7QjGun" id="recaptcha" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
                                    <p class="help-block" data-ng-show="recaptchaerror">@{{recaptchaerror }}</p>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <button title="Submit" title="Submit" class="btn cs-btn-pull-right btn-blue pull-right">submit your message</button>
                                </div>
                            </div>
                        </div>
                        
                        @else
                        <div class="enquiry row text-left">
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                                    <label class="control-label">
                                    </label>
                                    <input type="text" name="name" data-ng-model="staticcontent.name" class="form-control" placeholder="{{trans('cms::staticcontent.name_placeholder')}}" />
                                    <p class="help-block" data-ng-show="errors.name.has">@{{errors.name.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
                                    <label class="control-label">

                                    </label>
                                    <input type="text" name="email" data-ng-model="staticcontent.email" class="form-control" placeholder="{{trans('cms::staticcontent.email_placeholder')}}" />
                                    <p class="help-block" data-ng-show="errors.email.has">@{{errors.email.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': errors.phone.has}">
                                    <label class="control-label">

                                    </label>
                                    <input type="text" name="phone"  maxlength="15" data-ng-model="staticcontent.phone" class="form-control" placeholder="{{trans('cms::staticcontent.phone_placeholder')}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                                    <p class="help-block" data-ng-show="errors.phone.has">@{{errors.phone.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group" data-ng-class="{'has-error': errors.message.has}">
                                    <label class="control-label">

                                    </label>
                                    <textarea name="message" data-ng-model="staticcontent.message" class="form-control" placeholder="{{trans('cms::staticcontent.message_placeholder')}}" row="7"></textarea>
                                    <p class="help-block" data-ng-show="errors.message.has">@{{errors.message.message }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" data-ng-class="{'has-error': recaptchaerror}">
                                    <div class="g-recaptcha" data-sitekey="6LeFWDAUAAAAAEGNOrR0uGTKLS56dobl3K7QjGun" id="recaptcha" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
                                    <p class="help-block" data-ng-show="recaptchaerror">@{{recaptchaerror }}</p>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <button title="Submit" title="Submit" class="btn cs-btn-pull-right btn-blue pull-right">submit your message</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-5 col-xs-12 col-sm-12">
                	<div class="cs-address-content">
                        <h2>@{{staticcontent.title}}</h2>
                        <p ng-bind-html="staticcontent.content"></p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<script>
recaptcha: {
   required: true
     }
angular.element(document).ready(function() {
var getAddress=encodeURIComponent($('#address').text());
$('#map')
.attr('src','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3191.3403368352265!2d174.75979531552457!3d-36.88220568908516!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d0d4632b3e7d2c3%3A0xcccf9d03ee62e7f!2s445+Mount+Eden+Rd%2C+Mount+Eden%2C+Auckland+1024%2C+New+Zealand!5e0!3m2!1sen!2sin!4v1532930934483');


});

</script>
