<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name CommentsRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Illuminate\Database\QueryException;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\Group;
use Contus\Base\Helpers\StringLiterals;
use Contus\Notification\Repositories\NotificationRepository;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class LiveStreamRepository extends BaseRepository {
    /**
      * Class initializer
      *
      * @return void
      */
    public function __construct() {
         parent::__construct ();
         $this->liveStream = new Video();
    }

        /**
      * This method is use to trigger wowza live stream create api.
      *
      * @see \\Contus\Base\Contracts\ResourceInterface::store()
      *
      * @return boolean
      */
    public function store() {
         return $this->createLiveStream ( $this->request->all () );
    }
    
     /**
      * /**
      * This method is used to create live stream and save the details in db
      *
      * @param array $requestData          
      * @return boolean
      */
    public function createLiveStream($requestData) {
        $requestData['scheduled_time'] = date ( "Y-m-d H:i:s");
        if(isset($requestData['hls'])){
            $this->setRules ( ['hls'=>'required|url','title'=>'required','description'=>'required'] );
            $this->_validate ();
            $liveStream = $this->liveStream;
            $liveStream->stream_id = 'wowza';
             $liveStream->encoder_type = 'wowza';
             $liveStream->source_url = 'wowza' ;
             $liveStream->stream_name = 'wowza' ;
             $liveStream->username = config()->get('wowza.wowza.username');
             $liveStream->password = config()->get('wowza.wowza.password');
             $liveStream->liveStatus = 'ready';
             $liveStream->job_status = 'Complete';
             $liveStream->is_live = '1';
             $liveStream->title = $requestData['title'];
             $liveStream->scheduledStartTime =  date ( "Y-m-d H:i:s", strtotime ($requestData['scheduled_time']));
             $liveStream->description = $requestData['description'];
             $liveStream->hls_playlist_url = isset($requestData ['hls']) ? $requestData ['hls'] :"" ;
             
             $liveStream->broadcast_location ='wowza' ;
             $liveStream->creator_id = 1;
             $liveStream->updator_id = 1;
             $liveStream->save ();
             return 'success';
        }else{
            $this->setRules ( ['aspect_ratio'=>'required','title'=>'required','description'=>'required'] );
            $this->_validate ();
        }
         $aspectRatio = $requestData ['aspect_ratio'];
         $aspectRatio = explode ( "X", $aspectRatio );
         $aspect_ratio_width = $aspectRatio [0];
         $aspect_ratio_height = $aspectRatio [1];
      /**    
      * /**
      * This method is used for encoder is wowza_gocoder
      *        
      * @return array
      */
      //event_name
      $requestData ['encoder_type'] = 'push';
      $requestData ['encoder'] = 'other_rtmp';
      $requestData ['broadcast_location'] = 'asia_pacific_singapore';
         if ($requestData ['encoder_type'] == 'push') {
            $body = [ 
                'live_stream' => ['name' => $requestData['title'],'transcoder_type' => 'transcoded','billing_mode' => 'pay_as_you_go','broadcast_location' => $requestData ['broadcast_location'],'encoder' => $requestData ['encoder'],"use_stream_source" => 'false','delivery_method' => $requestData ['encoder_type'],'aspect_ratio_width' => $aspect_ratio_width,'aspect_ratio_height' => $aspect_ratio_height,'player_type' => 'wowza_player','player_responsive' => 'true','recording' => 'true','video_fallback' => 'true'] 
              ];
         }
         $client = new \GuzzleHttp\Client ();
         try {
             $responce = $client->post ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . '/' . 'live_streams'), [ 
                      'headers' => [ 
                               'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                               'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                               'Content-Type' => 'application/json' 
                      ],
                      'body' => json_encode ( $body ) 
             ] );         
             $wowzaResponce = json_decode ( $responce->getBody (), 1 );
             $liveStream = $this->liveStream;
             $liveStream->stream_id = $wowzaResponce ['live_stream'] ['id'];
             $liveStream->encoder_type = $requestData ['encoder_type'];
             $liveStream->source_url = isset($wowzaResponce ['live_stream'] ['source_connection_information'] ['primary_server']) ? $wowzaResponce ['live_stream'] ['source_connection_information'] ['primary_server'] :"" ;
             $liveStream->stream_name = isset($wowzaResponce ['live_stream'] ['source_connection_information'] ['stream_name']) ? $wowzaResponce ['live_stream'] ['source_connection_information'] ['stream_name'] :"" ;
             $liveStream->username = isset($wowzaResponce ['live_stream'] ['source_connection_information'] ['username']) ? $wowzaResponce ['live_stream'] ['source_connection_information'] ['username'] :"" ;
             $liveStream->password = isset($wowzaResponce ['live_stream'] ['source_connection_information'] ['password']) ? $wowzaResponce ['live_stream'] ['source_connection_information'] ['password'] :"" ;
             $liveStream->liveStatus = 'ready';
             $liveStream->job_status = 'Complete';
             $liveStream->is_live = '1';
             $liveStream->title = $requestData['title'];
             $liveStream->scheduledStartTime = date ( "Y-m-d H:i:s");
             $liveStream->description = $requestData['description'];
             $liveStream->hls_playlist_url = isset($wowzaResponce ['live_stream'] ['player_hls_playback_url']) ? $wowzaResponce ['live_stream'] ['player_hls_playback_url'] :"" ;
             $liveStream->broadcast_location = isset($wowzaResponce ['live_stream'] ['broadcast_location']) ? $wowzaResponce ['live_stream'] ['broadcast_location'] :"" ;
             $liveStream->presenter = isset($requestData ['presenter']) ? $requestData ['presenter'] :"" ;
             $liveStream->creator_id = 1;
             $liveStream->updator_id = 1;


             if (isset($requestData[StringLiterals::THUMBNAIL])) {
                $thumbUrl = explode("/", $requestData['thumbnail']);
                $liveStream->thumbnail_image = $thumbUrl [count($thumbUrl) - 1];
                $liveStream->thumbnail_path = $thumbUrl [count($thumbUrl) - 1];
            }
            if (isset($requestData[StringLiterals::POSTERIMAGE])) {
                $posterUrl = explode("/", $requestData['poster_image']);
                $liveStream->poster_image = $posterUrl [count($posterUrl) - 1];
            }
            $liveStream->is_active = $requestData['is_active'];
            $liveStream->save ();
            
            $this->saveVideoCategories($liveStream->id);

             return 'success';
         } catch ( RequestException $e ) {
             return $e->getMessage ().$e->getLine();
         }
    }
        /**
      * Method to start the livestream         
      * @return boolean
      */
    public function startLiveStreamRepository() {
         $liveStreamId = $this->liveStream->where ( 'id', $this->request->id )->get ()->first ();
         $client = new \GuzzleHttp\Client ();
         try {
             $this->setRule ( 'id', 'required' )->_validate ();
            $responce = $client->put ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . '/live_streams/' . $liveStreamId->stream_id . '/start/'), [ 
                      'headers' => [ 
                               'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                               'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                               'Content-Type' => 'application/json' 
                      ] ]);
             $wowzaResponce = array_get ( json_decode ( $responce->getBody (), 1 ), 'live_stream.state' );
             $this->liveStream->where ( 'id', $this->request->id )->update ( [ 
                      'liveStatus' => $wowzaResponce ,'scheduledStartTime' => date ( "Y-m-d H:i:s")
             ] );         
             return $wowzaResponce;
         } catch ( RequestException $e ) {
               if (array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' ) == "The requested resource has been deleted.") {
                $this->liveStream->where ( 'id', $this->request->id )->delete ();
                return 'deleted';
            }
             return array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' );
         }
    }
    /**
      * Method to stop the livestream
      *         
      * @return boolean
      */
    public function stopLiveStreamRepository() {
         $liveStreamStopId = $this->liveStream->where ( 'id', $this->request->id )->get ()->first ();
         $client = new \GuzzleHttp\Client ();
         try {
             $this->setRule ( 'id', 'required' )->_validate ();
             $responce = $client->put ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . '/live_streams/' . $liveStreamStopId->stream_id . '/stop/'), [ 
                      'headers' => [ 
                               'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                               'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                               'Content-Type' => 'application/json' 
                      ] 
             ] );
             $wowzaStopResponce = array_get ( json_decode ( $responce->getBody (), 1 ), 'live_stream.state' );
             $this->liveStream->where ( 'id', $this->request->id )->update ( [ 
                      'liveStatus' => $wowzaStopResponce,'scheduledStartTime' => date ( "Y-m-d H:i:s")
             ] );

             return $wowzaStopResponce;
         } catch ( RequestException $e ) {
             if (array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' ) == "The requested resource has been deleted.") {
                $this->liveStream->where ( 'id', $this->request->id )->delete ();
                return 'deleted';
             }
             return array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' );
         }
    }


    /**
    * Method to get the status of livestream
    *
    * @return boolean
    */
    public function statusLiveStreamRepository() {
      $client = new \GuzzleHttp\Client ();
      $liveStreamId = $this->liveStream->where ( 'id', $this->request->id )->get ()->first ();
      try {
        $responce = $client->get ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . '/live_streams/' . $liveStreamId->stream_id . '/state/'), [ 
                        'headers' => [ 
                                 'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                                 'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                                 'Content-Type' => 'application/json' 
                        ] 
               ] );
        $wowzaResponce = array_get ( json_decode ( $responce->getBody (), 1 ), 'live_stream.state' );
        if ($wowzaResponce == 'started' || $wowzaResponce == 'stopped') {
           $this->liveStream->where ( 'id', $this->request->id )->update ( [ 
                        'liveStatus' => $wowzaResponce ,'scheduledStartTime' => date ( "Y-m-d H:i:s")
               ] );
        }             
        return $wowzaResponce;
      } catch ( RequestException $e ) {
           if (array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' ) == "The requested resource has been deleted.") {
              $this->liveStream->where ( 'id', $this->request->id )->delete ();
           }
        }
    }


    /**
    * Method to get the status of livestream
    *
    * @return boolean
    */
    public function statusLiveStreamAll() {
      $client = new \GuzzleHttp\Client ();
      $liveStreams = $this->liveStream->where ( 'username','!=', '' )->get ();
      foreach ($liveStreams as $liveStreamId) {
        try {
          $responce = $client->get ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . '/live_streams/' . $liveStreamId->stream_id . '/state/'), [ 
                          'headers' => [ 
                                   'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                                   'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                                   'Content-Type' => 'application/json' 
                          ] 
                 ] );
          $wowzaResponce = array_get ( json_decode ( $responce->getBody (), 1 ), 'live_stream.state' );
          if ($wowzaResponce == 'starting' || $wowzaResponce == 'started' || $wowzaResponce == 'stopped'){
            Video::where ( 'id', $liveStreamId->id )->update ( [ 
              'liveStatus' => $wowzaResponce
            ] );
          }
        } catch ( RequestException $e ) {
           if (array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' ) == "The requested resource has been deleted.") {
              Video::where ( 'id', $liveStreamId->id )->delete ();
           }
        }
      }
    }

    /**
     * Function to save categories of a video in the database.
     *
     * @param integer $id
     * The id of the video whose categories are being saved.
     */
    public function saveVideoCategories($id)
    {
        $this->livevideoCategory = new VideoCategory ();
        $this->livevideoCategory->where(StringLiterals::VIDEOID, $id)->delete();
        if ($this->request->has(StringLiterals::CATEGORYIDS) && is_array($this->request->input(StringLiterals::CATEGORYIDS)) && count($this->request->input(StringLiterals::CATEGORYIDS)) > 0) {
            foreach ($this->request->input(StringLiterals::CATEGORYIDS) as $categoryId) {
                $this->livevideoCategory = new VideoCategory ();
                $this->livevideoCategory->video_id = $id;
                $this->livevideoCategory->category_id = $categoryId;
                $this->livevideoCategory->save();
                $livevideo = Video::find($id);
                $categoryy = Category::where('id', $categoryId)->first();
                Cache::forget('relatedCategoryList' . $categoryy->slug);
                $livevideo->collections()->detach();
            }
        }
        if ($this->request->has('exam_ids') && is_array($this->request->input('exam_ids')) && count($this->request->input('exam_ids')) > 0) {
            $livevideo->collections()->attach(Group::whereIn('id', $this->request->exam_ids)->pluck('id')->toArray());
            $groups = Group::whereIn('id', $this->request->exam_ids)->get();
            foreach ($groups as $group) {
                Cache::forget('groupList' . $group->slug);
            }
        }
    }
}