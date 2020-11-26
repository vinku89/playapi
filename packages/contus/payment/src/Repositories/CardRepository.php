<?php

/**
 * Card Repository
 *
 * To manage the functionalities related to the Payment module from Payment Controller
 *
 * @name Card Repository
 * @vendor Contus
 * @package Payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Payment\Models\Card;
use Contus\Customer\Models\SubscriptionPlan;

class CardRepository extends BaseRepository
{

    /**
     * Class property to hold the key which hold the card object
     *
     * @var object
     */
    protected $card;

    /**
     * Construct method
     *
     * @param Contus\Payment\Models\Card $card
     */
    public function __construct(Card $card)
    {
        parent::__construct();
        $this->card = $card;
        $this->subscription_plan = new SubscriptionPlan();
        $this->setRules([
          'name' => 'required',
          'card_number' => 'required|digits_between:12,20',
          'cvv' => 'required|integer|digits:3',
          'month' => 'required|in:01,02,03,04,05,06,07,08,09,10,11,12',
          'year' => 'required',
        ]);
    }

    /**
     * Fetch the cards list based on users
     *
     * @return array
     */
    public function fetchCardsByUser()
    {
        $response['success'] = false;
        try {
            $inputArray = $this->request->all();
            $response['data']['cards'] = $this->card->where('user_id', auth()->user()->id)->where('is_active', 1)->get();

            if(!empty($inputArray['plan'])) {
                $response['data']['plan'] = $this->subscription_plan->where('slug', $inputArray['plan'])->where('is_active', 1)->get();
            }


            $response['message'] = trans('payment::payment.fetch_list_success');
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['message'] = trans('payment::payment.fetch_list_error');
        }
        return $response;
    }


    /**
     * Store the card information into the table
     *
     * @return array
     */
    public function saveCards()
    {
        $response['success'] = false;
        $this->_validate();
        $card = $this->card;
        $card->user_id = auth()->user()->id;
        $card->fill($this->request->all());
        try {
            $card->save();
            $response['success'] = true;
            $response['data']['saved_card'] = $card;
            $response['message'] = trans('payment::payment.save_card_success');
        } catch (\Exception $e) {
            $response['message'] = trans('payment::payment.save_card_error');
        }
        return $response;
    }

    /**
     * Remove the cards by card id
     *
     * @package Payment
     * @return array
     */
    public function removeCardsById()
    {
        $this->setRules([
          'id' => 'required|regex:/^[\d\s,]*$/',
        ]);
        $this->_validate();
        $response = [];
        $response['success'] = false;
        $id = explode(',', $this->request->id);
        try {
            $this->card->whereIn('id', $id)->delete();
            $response['success'] = true;
            $response['message'] = trans('payment::payment.delete_card_success');
        } catch (\Exception $e) {
            $response['message'] = trans('payment::payment.delete_card_error');
        }
        return $response;
    }
}
