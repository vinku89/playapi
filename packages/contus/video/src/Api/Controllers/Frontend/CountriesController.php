<?php

/**
 * Countries Controller
 *
 * To manage the Countries such as create, edit and delete
 *
 * @name Countries Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Contus\Base\ApiController;
use Contus\Video\Repositories\CountriesRepository;
use Contus\User\Models\SiteLanguage;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Repositories\CategoryRepository;

class CountriesController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $GroupRepository
     */
    public function __construct(CountriesRepository $countryRepository,  CategoryRepository $categoryRepository) {
      
        parent::__construct ();
        $this->repository = $countryRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 
            'info' => [ 'locale' => trans ( 'validation' ),
            'isActive' => [ 'In-active','Active' ],
            StringLiterals::RULES => $this->repository->getRules (),
            'language' => SiteLanguage::where('is_active',1)->get()->toArray(),
            'allCategories' => $this->categoryRepository->getAllCategoryInfo(),
            ] 
            ] );   
    }
    
    /**
     * Function to assign videos to country
     *
     * @return \Contus\Base\response
     */
    public function postAdd() {
        $save = $this->repository->addOrUpdateCountry ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::countries.created.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::countries.error.added' ) );
    }
    
    /**
     * Function to eidt the country
     *
     * @return \Contus\Base\response
     */
    public function postEdit($id) {
        $save = $this->repository->addOrUpdateCountry ($id);
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::countries.created.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::countries.error.updated' ) );
    }

    /**
     * Function to bulk activate or deactivate the country in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postBulkUpdateStatus()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            if ($this->request->get('isStatus') == 'activate') {
                $isActionCompleted = $this->repository->countriesActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'activate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::countries.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'deactivate') {
                $isActionCompleted = $this->repository->countriesActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::countries.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }

    /**
     * Function to get the all country list in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function countriesList()
    {
        $countries['country_list'] = $this->repository->getCountryList();
        $countries['flag_url'] = 'https://bestbox.net/public/flags/'; 
        return ($countries) ? $this->getSuccessJsonResponse([ 'message' => trans('video::countries.fetched'),'response' => $countries ]) : $this->getErrorJsonResponse([ ], trans('general.fetch_failed'));
    }

}
