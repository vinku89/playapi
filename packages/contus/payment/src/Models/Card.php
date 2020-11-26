<?php

/**
 * Managing the user credit/debit card details.
 *
 * @name Card
 * @vendor Contus
 * @package payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Models;

use Contus\Base\Model;

class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','card_number','cvv','month','year', 'type' ];

    /**
     * Constructor method
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHiddenCustomer(['is_active','created_at','updated_at']);
    }

    /**
    * Function to formate created at
    *
    * @param date $date
    * @return string
    */
   public function getMonthAttribute($month)
   {
       if (strlen($month) === 1) {
           return '0'.$month;
       }
       return (string) $month;
   }

   /**
    * Function to formate created at
    *
    * @param date $date
    * @return string
    */
   public function getCardNumberAttribute($value)
   {
      return 'xxxx xxxx xxxx '.substr($value, -4);
   }
}
