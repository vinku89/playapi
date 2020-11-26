<!-- newpassword modal -->
<div class="modal-header login-title">
	<a href="javascript:;" class="close close-btn" data-dismiss="modal"
		aria-label="Close" ng-click="cancel()"> </a>
	<h2 class="modal-title" id="myModalLabel">Forgot Password</h2>
</div>
<div class="modal-body form-content text-left">

	<p class="terms-privacy cs-margin-tp">Enter your registered email address below and
		we will send you instructions on resetting your password.</p>
	@include('base::partials.errors')
	<form name="loginForm" method="POST" novalidate data-base-validator
		enctype="multipart/form-data"
		data-ng-submit="submitForgotPassowrd($event)">
		<div class="form-group"
			data-ng-class="{'has-error': errors.email.has}">
			<input type="email" name="email" data-ng-model="forgot.email"
				class="form-control" id=""
				placeholder="Enter Email address"
				value="{{ old('email') }}">
			<p class="help-block" data-ng-show="errors.email.has">@{{
				errors.email.message }}</p>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-yellow full-btn">Submit</button>
		</div>
	</form>

</div>