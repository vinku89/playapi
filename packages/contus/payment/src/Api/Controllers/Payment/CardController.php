<?php

/**
 * Card Controller
* To manage the credit and debit card functionalities
*
* @name Card Controller
* @vendor Contus
* @package Payment
* @version 1.0
* @author Contus<developers@contus.in>
* @copyright Copyright (C) 2018 Contus. All rights reserved.
* @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
*/
namespace Contus\Payment\Api\Controllers\Payment;

use Contus\Base\ApiController;
use Contus\Payment\Repositories\CardRepository;

class CardController extends ApiController
{
    /**
     * class property to hold the instance of CardRepository
     *
     * @var \Contus\Payment\Repositories\CardRepository
     */
    protected $repository;
    /**
     * Construct method
     */
    public function __construct(CardRepository $cardRepository)
    {
        parent::__construct();
        $this->repository = $cardRepository;
    }

    /**
     * Fetch the cards list based on users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCards()
    {
        $cards = $this->repository->fetchCardsByUser();
        if ($cards['success']) {
            return $this->getSuccessJsonResponse(['response' => $cards['data'], 'message' => $cards['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $cards['message']]);
    }

    /**
     * Store the card information into the table.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCards()
    {
        $cards = $this->repository->saveCards();
        if ($cards['success']) {
            return $this->getSuccessJsonResponse(['response' => $cards['data'], 'message' => $cards['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $cards['message']]);
    }

    /**
     * Remove the cards by card id.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeCards()
    {
        $cards = $this->repository->removeCardsById();
        if ($cards['success']) {
            return $this->getSuccessJsonResponse(['message' => $cards['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $cards['message']]);
    }
}
