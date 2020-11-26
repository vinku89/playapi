<div class="col-md-9 ">
    <div class="row">
          <div class="col-md-12 col-xs-12 col-sm-12">
    <div class="row">
            <div class="panel panel-default payment-actions">
  <div class="panel-body">
   <i class="actions-img"></i>
  <div class="payment-actions-content except-profile">
     <ul class="video-member-options clearfix" >
                    <li class="" data-ng-repeat="subcrp in subscription">
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
        <h3 class="mytransaction-heading">My Transactions</h3>
        <div class="panel panel-default">
            <div class="tab-content">
                <div class="tab-pane active" id="subscriptions_plans">
                    <div class="table-responsive">
                        <table class="table table-hover">
                             <thead>
                                <tr>
                                    <th class="center">{{trans('customer::subscription.serial_no')}}</th>
                                    <th>Transaction Id</th>
                                    <th>Membership Name</th>
                                    <th>Status</th>
                                    <th>Transaction On</th>
                                    <th>Actions</th>
                                </tr>
                                </tr>
                           </thead>
                        
                            <tbody>
                                <tr class="search_text">
                                    <td></td>
                                    <td class="search_product">
                                        <input type="text" class="form-control" data-ng-model="searchRecords.transaction_id" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('customer::subscription.transaction_id')}}">
                                    </td>
                                    <td class="search_product">
                                        <input type="text" class="form-control" data-ng-model="searchRecords.plan_name" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('customer::subscription.transaction_id')}}">
                                    </td>
                                    <td></td><td></td>
                                    <td class="">
                                        <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('base::general.search_filter')}}">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button type="button" class="btn search" data-ng-click="gridReset()" data-boot-tooltip="true" title="{{trans('base::general.reset')}}">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-ng-if="noRecords" colspan="@{{heading.length + 1}}" class="no-data">{{trans('base::general.not_found')}}</td>
                                </tr>
                                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                                    <td class="center">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                                    <td>@{{record.transaction_id}}</td>
                                    <td>@{{record.plan_name}}</td>
                                     <td>@{{record.transaction_message}}</td>
                                     <td>@{{record.created_at+' 00:00:00' |convertDate|date:'dd-MM-yyyy'}}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="cusomt-pagination">@include('base::layouts.pagination')</div>
                </div>
            </div>
        </div>
    </div>
</div>
