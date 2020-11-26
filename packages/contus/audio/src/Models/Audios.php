<?php
/**
 * Audios Model
 *
 * Audio management related model
 *
 * @name Audios
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Models;

use Contus\Base\Model;
use Contus\Audio\Scopes\ActiveRecordScope;
use Contus\Audio\Scopes\ActiveTracksScope;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Contus\Audio\Models\Albums;
use Contus\Audio\Models\Artist;
use Contus\Audio\Models\FavouriteAudio;
use Contus\Customer\Models\Customer;
use Contus\Audio\Models\AudioPlayHistory;

use ScoutElastic\Searchable;
use Contus\Base\Elastic\Indices\AudioIndexConfigurator;
use Contus\Base\Elastic\Rules\AudioSearchRule;
use Contus\Audio\Traits\AudioTrait;

class Audios extends Model{
    use HybridRelations, Searchable, AudioTrait;
     /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'audios';
    protected $connection = 'mysql';
    protected $appends = ['is_favourite', 'album_name', 'artist_name', 'artist_slug', 'album_slug'];

    /**
     * Set the elastic index name
     */
    protected $indexConfigurator = AudioIndexConfigurator::class;

    /**
     * Rules for the elasticearch to search records
     */
    protected $searchRules = [
            AudioSearchRule::class,
        ];

    /**
     * The "booting" method of the model.
     *
     * @vendor Contus
     * @package Audio
     * @return void
     */
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveRecordScope);
        static::addGlobalScope(new ActiveTracksScope);
    }

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHiddenCustomer([ 'pipeline_id', 'job_id', 'job_status', 'fine_uploader_uuid', 'fine_uploader_name', 'transcoding_percentage', 'creator_id', 'updator_id', 'is_archived', 'archived_on', 'updated_at']);
    }


    /**
     * Method to establish relationship between track and its respective album
     * 
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function album(){
        return $this->belongsTo(Albums::class,'album_id');
    }
    /**
     * Method to establish relationship between track and its respective artist
     * 
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function audioTrackArtist(){
        return $this->hasOne(Artist::class, 'id','audio_artist_id');
    }
    /**
     * Method to get the favourite tracks of a customer
     * 
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function customerFavouriteTracks(){
        return $this->hasMany(FavouriteAudio::class,'audio_id','id');
    }
    /**
     * Method to set favourite flag for audio list
     * 
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Database\Eloquent\Builder    
     */
    public function getIsFavouriteAttribute(){
        $info = $this->customerFavouriteTracks()->where('customer_id', !empty(authUser()->id) ? authUser()->id : 0)->count();
        return ($info > 0) ? 1 : 0;
    }
    /**
     * Method to get the audio artist name
     * 
     * @vendor Contus
     * @package Audio
     * @return string  
     */
    public function getArtistNameAttribute(){
        $artistData = $this->audioTrackArtist()->first();
        return (!empty($artistData))?$artistData->artist_name:'';
    }
    /**
     * Method to get the album artist slug
     * 
     * @vendor Contus
     * @package Audio
     * @return string  
     */
    public function getArtistSlugAttribute(){
        $artistData = $this->audioTrackArtist()->first();
        return (!empty($artistData))?$artistData->slug:'';
    }
    /**
     * Method to get the album slug
     * 
     * @vendor Contus
     * @package Audio
     * @return string  
     */
    public function getAlbumSlugAttribute(){
        $albumData = $this->album()->first();
        return (!empty($albumData))?$albumData->slug:'';
    }
    /**
     * Method to set favourite flag for audio list
     * 
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Database\Eloquent\Builder    
     */
    public function getAlbumNameAttribute(){
        $albumData = $this->album()->first();
        return (!empty($albumData))?$albumData->album_name:'';
    }
    /**
     * Method to get the audio thumbnail full URL
     * 
     * @vendor contus
     * @package Audio
     * @return string
     */
    public function getAudioThumbnailAttribute($value){
        return $this->getAudiosPkgThumbnailImageAttributes($value, 'track');
    }
    /**
     * Method to establish relationship between track and its customer played history
     * 
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function customerPlayedAudioHistory(){
        return $this->hasMany(AudioPlayHistory::class, 'audio_id', 'id');
    }
    /**
     * Method to get the audio duration skipping the zero in hours
     * 
     * @vendor contus
     * @package Audio
     * @return string
     */
    public function getAudioDurationAttribute($value){
        $duration = explode(':', $value);
        $value = ($duration[0] === '00')?$duration[1].':'.$duration[2]:$value;
        return (!empty($value))? $value:'';
    }
}
?>