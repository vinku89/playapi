
<!-- Login -->
<div class="modal-header login-title">
    <a href="javascript:;" class="close close-btn" data-dismiss="modal" aria-label="Close" ng-click="cancel()"> </a>
    <h2 class="modal-title" id="myModalLabel">Log in</h2>
</div>
<div class="modal-body form-content text-left">
    <div class="">
        <a href="{{url('auth/google')}}"  force-load class="loginwith-google  ripple" title="">
            <span class="gplus-icon"></span>
            google
        </a>
        <a href="{{url('auth/facebook')}}"  force-load class="loginwith-facebook  ripple" title="">
            <span class="fb-icon"></span>
            facebook
        </a>
        <span class="ortext">or</span>
    </div>
    @include('base::partials.errors')
    <form name="loginForm" method="POST" novalidate data-base-validator enctype="multipart/form-data" data-ng-submit="login($event)">
        <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
        <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
            <input type="email" name="email" data-ng-model="user.email" class="form-control" id="" placeholder="{{trans('customer::customer.email')}}" value="{{ old('email') }}">
            <p class="help-block" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
        </div>
        <div class="form-group" data-ng-class="{'has-error': errors.password.has}">
            <input type="password" name="password" data-ng-model="user.password" class="form-control" id="" placeholder="{{trans('customer::customer.password')}}">
            <p class="help-block" data-ng-show="errors.password.has">@{{ errors.password.message }}</p>
        </div>
         <p class="">
        <a title="Forgot Password?" ui-sref="newpassword" class="forgot-links" >Forgot Password?</a>
         </p>
        <div class="form-group">
            <button type="submit" title="Sign in" class="btn btn-green full-btn ripple">Sign in</button>
        </div>

    </form>
      <span class="ortext">or</span>
   <div class="sign-link text-center">
    <span>
        Don't have an account?
        <a ui-sref="signup" title="Sign up Now">Sign up Now</a>
    </span>
</div>
    <p class="terms-privacy">
        Continue By clicking one of the buttons above, you agree to our
        <a title="Terms and Privacy Policy" href="javascript:;" ng-click="loadterms()">
            <a ui-sref="staticContent({slug:'terms-and-condition'})" target="_blank">
                <span>T&amp;C</span>
            </a>
            &amp;
            <a title="privacy policy" ui-sref="staticContent({slug:'privacy-policy'})" target="_blank">Privacy Policy</a>
            . We'll share offers with you by email. You can update your email preferences anytime

    </p>
</div>
