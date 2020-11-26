<?php

/**
 * VideoRelation Model for videos table relation in database
 *
 * @name VideoRelation
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2017 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\TranscodedVideo;
use Contus\Base\Contracts\AttachableModel;
use Symfony\Component\HttpFoundation\File\File;
use Contus\Video\Models\VideoCategory;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCountries;
use Contus\Video\Models\VideoPoster;
use Contus\Video\Models\Comment;
use Contus\Customer\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VideoRelation extends Model
{
 /**
  * Function to alter the mp3 attribute
  *
  * @param string $value
  * @return string
  */
 public function getMp3Attribute($value)
 {
  $prefix = 'https://s3.' . env('AWS_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
  if (strpos($value, $prefix) !== false) {
   $value = explode('/', $value);
   $value = $value[count($value) - 1];
  }
  if (! empty($value)) {
   if (app('request')->header('x-request-type') == 'mobile') {
    app('request')->session()->get('updated_version') ? $value = env('AWS_BUCKET_URL') . $value : $value = env('AWS_BUCKET') . $value;
   } else {
    $value = env('AWS_BUCKET_URL') . $value;
   }
  }
  if (config()->get('auth.table') === 'customers') {
   return (auth()->user() && auth()->user()->isExpires()) ? $value : '';
  } else {
   return $value;
  }
 }
 
 /**
  * Function to alter the pdf attribute
  *
  * @param string $value
  * @return string
  */
 public function getPdfAttribute($pdfUrl)
 {
  $prefixUrl = 'https://s3.' . env('AWS_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
  if (strpos($pdfUrl, $prefixUrl) !== false) {
   $pdfUrl = explode('/', $pdfUrl);
   $pdfUrl = $pdfUrl[count($pdfUrl) - 2] . '/' . $pdfUrl[count($pdfUrl) - 1];
  }
  if (! empty($pdfUrl)) {
   if (app('request')->header('x-request-type') == 'mobile') {
    app('request')->session()->get('updated_version') ? $pdfUrl = env('AWS_BUCKET_URL') . $pdfUrl : $pdfUrl = env('AWS_BUCKET_URL') . $pdfUrl;
   } else {
    $pdfUrl = env('AWS_BUCKET_URL') . $pdfUrl;
   }
  }
  if (config()->get('auth.table') === 'customers') {
   return (auth()->user() && auth()->user()->isExpires()) ? $pdfUrl : '';
  } else {
   return $pdfUrl;
  }
 }
 
 /**
  * Function to alter the word attribute
  *
  * @param string $value
  * @return string
  */
 public function getWordAttribute($wordUrl)
 {
  $prefix = 'https://s3.' . env('AWS_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
  if (strpos($wordUrl, $prefix) !== false) {
   $wordUrl = explode('/', $value);
   $wordUrl = $wordUrl[count($wordUrl) - 2] . '/' . $wordUrl[count($wordUrl) - 1];
  }
  if (! empty($wordUrl)) {
   if (app('request')->header('x-request-type') == 'mobile') {
    app('request')->session()->get('updated_version') ? $wordUrl = env('AWS_BUCKET_URL') . $wordUrl : $wordUrl = env('AWS_BUCKET_URL') . $wordUrl;
   } else {
    $wordUrl = env('AWS_BUCKET_URL') . $wordUrl;
   }
  }
  if (config()->get('auth.table') === 'customers') {
   return (auth()->user() && auth()->user()->isExpires()) ? $wordUrl : '';
  } else {
   return $wordUrl;
  }
 }
 
 /**
  * Function to alter the video url attribute
  *
  * @param string $value
  * @return string
  */
 public function getVideoUrlAttribute($videoUrl)
 {
  $s3Prefix = 'https://s3.' . env('AWS_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
  if (strpos($videoUrl, $s3Prefixs) === false) {
   if (app('request')->session()->get('updated_version')) {
    $videoUrl = env('AWS_BUCKET_URL') . $videoUrl;
   } else {
    $videoUrl = (app('request')->header('x-request-type') == 'mobile') ? env('AWS_BUCKET_URL') . $videoUrl : env('AWS_BUCKET_URL') . $videoUrl;
   }
  } else {
   if (app('request')->session()->get('updated_version')) {
    $videoUrl = env('AWS_BUCKET_URL') . substr($videoUrl, strlen($s3Prefixs));
   } else {
    $videoUrl = (app('request')->header('x-request-type') == 'mobile') ? env('AWS_BUCKET_URL') . substr($videoUrl, strlen($s3Prefixs)) : env('AWS_BUCKET_URL') . substr($videoUrl, strlen($s3Prefixs));
   }
  }
  return $videoUrl;
 }
 
 /**
  * Function to alter the playlisturl attribute
  *
  * @param string $value
  * @return string
  */
 public function getHlsPlaylistUrlAttribute($hlsUrl)
 {
  $s3Prefix = 'https://s3.' . env('AWS_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
  if (strpos($hlsUrl, $s3Prefix) === false) {
   if (app('request')->session()->get('updated_version')) {
    $hlsUrl = env('AWS_BUCKET_URL') . $hlsUrl;
   } else {
    $hlsUrl = (app('request')->header('x-request-type') == 'mobile') ? env('AWS_BUCKET_URL') . $hlsUrl : env('AWS_BUCKET_URL') . $hlsUrl;
   }
  } else {
   if (app('request')->session()->get('updated_version')) {
    $hlsUrl = env('AWS_BUCKET_URL') . substr($hlsUrl, strlen($s3Prefix));
   } else {
    $hlsUrl = (app('request')->header('x-request-type') == 'mobile') ? env('AWS_BUCKET_URL') . substr($hlsUrl, strlen($s3Prefix)) : env('AWS_BUCKET_URL') . substr($hlsUrl, strlen($s3Prefix));
   }
  }
  return $hlsUrl;
 }
}