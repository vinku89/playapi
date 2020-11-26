<?php

/**
 * AudioTrait
 *
 * To manage the functionalities related to the Videos module from Video Controller
 *
 * @vendor Contus
 *
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Traits;

use Contus\Audio\Models\Artist;
use Contus\Audio\Models\Albums;

trait AudioTrait {
    /**
     * Method to get artist name
     * 
     * @vendor Contus
     * @package Audio
     * @return string
     */
    function getArtistName() {
        $artistName = '';
        $artist = app()->request->audio_artist_id;
        if(!empty($artist)) {
            $artistInfo = Artist::find($artist);
            $artistName = (!empty($artistInfo)) ? $artistInfo->artist_name : '';
        } else {
            $artist = $this->artist()->first();
            $artistName = (!empty($artist)) ? $artist->artist_name : '';
        }
        return $artistName;
    }
    /**
     * Method to get album name
     * 
     * @vendor Contus
     * @package Audio
     * @return string
     */
    function getAlbumName() {
        $albumName = '';
        $album = app()->request->album_id;
        if(!empty($album)) {
            $albumInfo = Albums::find($album);
            $albumName = (!empty($albumInfo)) ? $albumInfo->album_name : '';
        } else {
            $album = $this->artist()->first();
            $albumName = (!empty($album)) ? $album->album_name : '';
        }
        return $albumName;
    }
}
