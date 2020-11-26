<?php

/**
 * Search Repository
 *
 * To manage the functionalities related to the search.
 *
 * @name Search Repository
 * @vendor Contus
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Webseries;

class SearchRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $video;
    
    public function __construct(Video $video)
    {
        parent::__construct();
        $this->video = $video;
        $this->webseries = new Webseries();
    }

    public function searchVideos()
    {
        $searchKeyword = $this->request->input('q');
        $is_webseries = $this->request->input('is_webseries');
        $this->setRules(['q' => 'required']);
        $this->validate($this->request, $this->getRules());
        //$search  = !empty($searchKeyword) ? str_replace("%20", ' ', $searchKeyword) : '';
        if($is_webseries){
            $test = $this->webseries->search($searchKeyword)->paginate(30);
            //echo '<pre>';print_r($test);exit;
            
        }else{
            return $this->video->search($searchKeyword)->paginate(30);
        }
    }

}
