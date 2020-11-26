<?php

namespace Contus\Customer\Models;

use Contus\Base\Model;

class Subscribers extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscribers';

    protected $fillable = ['customer_id', 'subscription_plan_id', 'start_date', 'end_date', 'creator_id', 'is_active'];
    /**
     * Belongs to many relation with subscription plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriptionplan() {
      return $this->belongsTo( SubscriptionPlan::class, 'subscription_plan_id','id' );
    }

}
