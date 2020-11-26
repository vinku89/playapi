<div class="forgotform-page">
	<form method="POST"  enctype="multipart/form-data" name="forgotformreset" id="{{url('forgotPassword/'.$random)}}" novalidate data-base-validator  data-ng-submit="forgotpassword($event)">
		 <input type="hidden" name="_token" id="csrf-token"
            value="{{csrf_token()}}" />
		<h4>Kindly enter your new password here</h4>
		<input type="hidden" value="$random">
		<div class="form-group"
			data-ng-class="{'has-error': errors.password.has}">
			<label class="sr-only" for="">New Password</label> <input
				type="password" name="password" data-ng-model="user.password"
				class="form-control"
				placeholder="{{trans('customer::customer.newpassword')}}" >
				 <p class="help-block"
                        data-ng-show="errors.password.has">@{{errors.password.message }}</p>
		</div>
		<div class="form-group"
			data-ng-class="{'has-error': errors.password_confirmation.has}">
			<label class="sr-only" for="">Confirm Password</label> <input
				type="password" name="password_confirmation"
				data-ng-model="user.password_confirmation" class="form-control"
				placeholder="{{trans('customer::customer.confirmnewpassword')}}" data-validation-name="{{trans('customer::customer.password_confirm')}}">
				  <p class="help-block"
                        data-ng-show="errors.password_confirmation.has">@{{errors.password_confirmation.message }}</p>
		</div>
		<div class="">
			<button type="submit" class="btn btn-green full-btn ripple">Submit</button>
		</div>
	</form>
</div>