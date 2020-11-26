<?php

/**
 * AlbumTrait
 *
 * To manage the functionalities related to the Album
 *
 * @vendor Contus
 *
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Traits;

use Contus\Audio\Models\Artist;

trait AlbumTrait {
    /**
     * Method to get artist name
     * 
     * @vendor Contus
     * @package Audio
     * @return string
     */
    function getArtistName() {
        $artistName = '';
        $artist = app()->request->album_artist_id;
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
     * Method to get artist name on append variable
     * 
     * @vendor Contus
     * @package Audio
     * @return string
     */
    public function getArtistNameAttribute() {
        $artist = $this->albumArtist()->first();
        return (!empty($artist)) ? $artist->artist_name : '';
    }
}
