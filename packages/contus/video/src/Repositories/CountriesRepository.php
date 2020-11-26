<?php

/**
 * Countries Repository
 *
 * To manage the functionalities related to the Countries module from Countries Controller
 * @name       CountriesRepository
 * @vendor Contus
 * @package Countries
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Video\Contracts\ICountriesRepository;
use Contus\Video\Models\Countries;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\Video;
use Contus\Video\Models\CountryCategory;
use DB;
class CountriesRepository extends BaseRepository implements ICountriesRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_countries;

    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Contus\Video\Models\Countries $countries            
     */
    public function __construct(Countries $countries) {
        parent::__construct ();
        $this->_countries = $countries;
    }
    /**
     * Function to get all countries.
     *
     * @return string The hierarchy string.
     */
    public function getAllCountries() {
        return $this->_countries->pluck ( 'name', 'code','id','flag');
    }

    /**
     * Function to get all countries.
     *
     * @return string The hierarchy string.
     */
    public function getCountryList() {
        
        if($this->request->has('page')) {
            $data = $this->_countries->where('is_active', 1)->with(['categories' =>function($query){
                $query->distinct('id');
            }])->paginate(10);
        } else {
            $data = $this->_countries->where('is_active', 1)->with(['categories' =>function($query){
                $query->distinct('id');
            }])->get();
        }

        // db::raw('SELECT country_categories.country_id FROM country_categories INNER JOIN videos ON videos.id = country_categories.video_id WHERE videos.is_live =1 group BY country_categories.country_id order BY country_id ASC');
        $result = DB::select( DB::raw('SELECT country_categories.country_id FROM country_categories INNER JOIN videos ON videos.id = country_categories.video_id LEFT JOIN countries c ON country_categories.country_id= c.id WHERE c.is_active = 1 and videos.is_live =1 and videos.is_active = 1 and videos.job_status="Complete" and videos.is_archived = 0 and videos.liveStatus != "complete" and country_categories.video_id != 0 group BY country_categories.country_id order BY country_id ASC'));
        $countries = array();
        foreach($result as $country) {
            array_push($countries,$country->country_id);
        }

        foreach($data as $item) {
            if(in_array($item['id'],$countries)){
                $list[] = $item;
            }
        }
        return $list;
    }

    public function getLivetvCountryList() {
        
        $result = DB::select( DB::raw('SELECT c.id as country_id,c.name as country_name,c.code,count(v.id) as counts FROM country_categories cc JOIN categories cat ON cc.category_id=cat .id JOIN videos v ON cc.video_id=v.id LEFT JOIN countries c ON cc.country_id= c.id where c.is_active = 1 and v.is_live = 1 and v.is_active = 1 and v.job_status="Complete" and v.is_archived = 0 and v.liveStatus != "complete" and cc.video_id != 0 group by c.id ORDER By c.name'));
        
        return $result;
    }

}