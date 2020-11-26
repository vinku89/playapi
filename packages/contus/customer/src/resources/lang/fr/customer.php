<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines used to display in customre block.
    |
    */

    'customer' => 'Customer',
    'age' => 'Age',
    'dob'=>'DOB',
    'updatedNotifications'=>'Successfully Updated the Notification Status',
    'add_new_customer' => 'Add New Customer',
    'edit_new_customer' => 'Edit Customer',
    'customer_heading' => 'Customer',
    'update_customer' => 'Update Customer',
    'customername' => 'Username',
    'name' => 'Name',
    'email' => 'Email',
    'secondary_email' => 'Secondary Email',
    'phone' => 'Phone',
    'secondary_phone' => 'Secondary Phone',
    'customer_groups' => 'Customer Groups',
    'designation' => 'Designation',
    'customer_confirm_passowrd'=>'The Confirm Password field is required',
    'select_customer_groups' => 'Select Customer Groups',
    'status' => 'Status',
    'active' => 'Active',
    'inactive' => 'In Active',
    'select_image' => 'Select Image',
    'select_file' => 'Select file',
    'sex' => 'Sex',
    'gender' => 'Gender',
    'male' => 'Male',
    'password' => 'Password',
    'password_confirm' => 'Confirm Password',
    'male' => 'Male',
    'female' => 'Female',
    'select_gender' => 'Select Gender',
    'sex_placeholder' => 'Eg. Male',
    'change' => 'Change',
    'remove' => 'Remove',
    'customers' => 'Customers',
    'customername_placeholder' => 'Enter Your Name',
    'email_placeholder' => 'Enter Your Email',
    'phone_placeholder' => 'Enter Your Mobile Number',
    'designation_placeholder' => 'Enter Your Designation',
    'serial_no' => 'S.No.',
    'general_information' => 'General Information',
    'basic_information' => 'Some basic information about this customer.',
    'subscription_success'=>'Abonnement ajouté avec succès',
    'success' => 'Client ajouté avec succès.',
    'error' => "Le client n'a pas été ajouté avec succès.",
    'subscription_error'=>"L'abonnement n'a pas été ajouté avec succès.",
    'updated' => 'Votre profil a été mis à jour avec succés.',
    'updatedError' => "Le client n'a pas été mis à jour avec succès.",
    'deleted' => 'Le client a été supprimé avec succès.',
    'deletedError' => 'Impossible de trouver le client à supprimer.',
    'showError' => 'Impossible de trouver le client.',
    'showallError' => "Impossible de trouver s'il vous plaît essayez à nouveau.",
    'selected_deleted' => 'Client sélectionné supprimé avec succès.',
    'forgotsuccess'=>'Forgot Password link has been sent to your Registered Email. Kindly check with your Email',
    'manage_customers' => 'Manage Customer',
    'create_customers' => 'Create Customer',
    'manage_active' => 'Active',
    'manage_inactive' => 'In Active',
    'manage_all' => 'All',
    'group' => 'Group',
    'customer_group' => 'Customer Groups',
    'select_customergroup' => 'Select Customer Groups',
    'edit_customer' => 'Edit Customer',
    'delete_customer' => 'Delete Customer',
    'mytransaction' =>'My Transactions',
     'mynotifications'=>'Notifications',
     'myplans'=>'My Plans',
     'myfavourites'=> 'My Favourites',
     'myprofile'=>'MY ACCOUNT',
     'myaccount'=>'My Account',
     'mychangepassword'=>'Change password',

    'changepassword' => [
        'changepassword' => 'Change Password',
        'oldpassword' => 'Old Password',
        'newpassword' => 'New Password',
        'confirmpassword' => ' Confirm Password',
        'not_match' => 'Re-enter Password didnot match',
        'placeholder_oldpassword' => 'Old Password',
        'placeholder_newpassword' => 'New Password',
        'placeholder_confirmpassword' => 'Confirm Password',
        'placeholder_confirmpassword_required'=>'Password confirm field is required',
        'wrong_old' => 'Old password is wrong',
        'success' => 'Mot de passe mis à jour avec succès.',
        'incorrect' => "Ancien mot de passe est incorrect. S'il vous plaît entrer le mot de passe correct.",
        'otperror' => 'Incorrect OTP, Please try again',
    ],
        'subscription'=>[
          'success'=>"Subscription addedd successfully",
          'error'=>"Subscription failed please try again"
        ],
        'add_new_subscription' => "Add Subscription",
        'orderid' => 'Transaction ID',
        'plan'=>'Subscription Plan',
        'start_date' => 'Plan Start Date',
        'orderid_placeholder'=>"Transaction Id",
    'loginsuccess' => 'Logged In Successfully.',
    'registersuccess' => 'You have registered successfully',
    'email_not_registered' => 'This email address is not registered with us. Please try again or create a new account.',
    'invalid_user_details' => 'Incorrect Email or Password. Please try again',
    'loginerror' => 'Your Email or Password is not correct or your account might be inactive.',
    'registererror' => 'Unable to register, Please try again.',
    'profile' => [
        'editprofile' => 'Edit Profile',
        'success' => 'Profile successfully updated.',
    ],
  'message'=>[
      'signup'=>'Welcome to ' . config ()->get ( 'settings.general-settings.site-settings.site_name' ) . ' , Thanks for signing up',
    'multiplelogin'=>'You have already logged in  with other browser.  Please login again to continue'
  ],
    'emailreset' => [
            'error' => 'Your Email do not match with our records.',
            'success' => 'OTP Generated.! Please check your Email.',
    ],
    'my_profile' => 'My Profile',
    'enter_customer_name' => 'Enter Customer Name',
    'enter_email' => 'Enter Customer Email',
    'enter_group_name' => 'Enter Group Name',
    'manage_dashboard' => 'Welcome ' . config ()->get ( 'settings.general-settings.site-settings.site_name' ),
    'login_inactive' => 'Account you are trying to login is inactive.',
    'profile_image' => 'Profile Image',
        
    'newpassword' =>'New password',
    'confirmnewpassword'=>'Confirm new password',
    'exams'=>'Genre'
];