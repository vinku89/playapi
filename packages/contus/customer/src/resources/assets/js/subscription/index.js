'use strict';

var subscriptionPlanController = ['$scope','requestFactory','$window','$sce','$timeout',function(scope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.subscriptions_plans = {};
  this.showResponseMessage = false;
  requestFactory.setThisArgument(this);
  /**
   *  To get the auth id
   *  
   */ 
  this.setQuery = function($authId) {
    this.authId = $authId;
  }
  
  /**
   *  Function is used to add the latest news
   *  @param $event
   */ 
  this.addSubscriptionsPlans = function ($event){
    scope.errors = {};
    this.subscriptions_plans={};
    this.subscriptions_plans.is_active = String(0);
  }
  
  /**
   *  Function is used to edit the latestnews
   *  
   *  @param records
   */ 
  this.editSubscriptionsPlans = function (records) {
    scope.errors = {};
    this.subscriptions_plans.id = records.id;
    this.subscriptions_plans.name = records.name;
    this.subscriptions_plans.type = records.type;
    this.subscriptions_plans.description = records.description;
    this.subscriptions_plans.amount = records.amount;
    this.subscriptions_plans.duration = records.duration;
    this.subscriptions_plans.is_active = String(records.is_active);
  }

  this.fillError = function(response){
   if(response.status == 422 && response.data.hasOwnProperty('message')){
      angular.forEach(response.data.message, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : message[0]};
        }
      });
    }
  };
  
  /**
   *  Function is used to save the latestnews
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {
    if (baseValidator.validateAngularForm($event.target,scope)) {
      if (id) { 
        requestFactory.post(requestFactory.getUrl('subscriptions-plans/edit/'+id),this.subscriptions_plans,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeSubscriptionEdit();
          $timeout(function(){
            self.subscriptions_plans = {};
          },100);
        },this.fillError);
        
      } else {
        requestFactory.post(requestFactory.getUrl('subscriptions-plans/add'),this.subscriptions_plans,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeSubscriptionEdit();
        },this.fillError);
      }
    }
  }
  
  
  
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeSubscriptionEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('subscriptions-plans/info'),this.defineProperties,function(){});
  };

  this.fetchInfo();
  

  /**
   *  Listen to the records to update property
   *  
   */ 
  scope.$on('afterGetRecords',function(e,data){ 
    if(angular.isUndefined(scope.searchRecords.is_active)){
        scope.searchRecords.is_active = 'all';
    }
  });
}];

window.gridControllers = {subscriptionPlanController : subscriptionPlanController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});