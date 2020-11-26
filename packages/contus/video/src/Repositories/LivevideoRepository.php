<?php

/**
 * Live Video Repository
 *
 * To manage the functionalities related to videos
 *
 * @name VideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http: www.gnu.org/copyleft/gpl.html
 *
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Contracts\IVideoRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Repositories\VideoCountriesRepository;
use Contus\Video\Repositories\VideoCastRepository;
use Contus\Video\Repositories\QuestionsRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\Tag;
use Contus\Video\Models\VideoTag;
use Contus\Video\Models\Collection;
use Contus\Video\Models\VideoPreset;
use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\VideoPoster;
use Contus\Video\Models\VideoCast;
use Contus\Notification\Traits\NotificationTrait;
use Contus\Video\Traits\CollectionTrait;
use Contus\Video\Models\Group;
use Illuminate\Support\Facades\Cache;

class LivevideoRepository extends BaseRepository implements IVideoRepository
{

    use NotificationTrait,CollectionTrait;
    /**
     * class property to hold the instance of Video Model
     *
     * @var \Contus\Video\Models\Video
     */
    public $liveVideo;
    /**
     * class property to hold the instance of AWSUploadRepository
     *
     * @var \Contus\Video\Repositories\AWSUploadRepository
     */
    public $awsRepository;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedCollection = 'q';
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $fileRepository;

    /**
     * Construct method initialization
     *
     * Validation rule for user verification code and forgot password.
     */
    public function __construct(AWSUploadRepository $awsRepository, UploadRepository $uploadRepository, VideoCountriesRepository $liveVideoCountriesRepository, VideoCastRepository $liveVideoCastRepository, CommentsRepository $commentRepository, QuestionsRepository $questionrepository)
    {
        parent::__construct();

        /**
         * Set other class objects to properties of this class.
         */
        $this->category = new Category ();
        $this->videoPreset = new VideoPreset ();
        $this->tag = new Tag ();
        $this->video = new Video ();
        $this->videoTag = new VideoTag ();
        $this->videoCategory = new VideoCategory ();
        $this->awsRepository = $awsRepository;
        $this->fileRepository = $uploadRepository;
        $this->videoCountriesRepository = $liveVideoCountriesRepository;
        $this->videoCastRepository = $liveVideoCastRepository;
        $this->commentRepository = $commentRepository;
        $this->questionRepository = $questionrepository;
        $this->categoryRepository = new CategoryRepository (new Category (), new UploadRepository ());

        $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, 'video_url' => StringLiterals::REQUIRED, 'is_featured_time' => StringLiterals::REQUIRED]);
    }

    /**
     * Function to add a video.
     *
     * @return boolean integer id if video is added successfully and False if not.
     */
    public function addVideo()
    {
        $typeId = null;
        $liveVideoDetails = $this->request->video_details;
        $liveVideo = new Video ();
        $liveVideo->creator_id = $this->authUser->id;
        $liveVideo->title = $liveVideoDetails ['name'];
        $liveVideo->job_status = 'Video Uploaded';
        $liveVideo->is_featured = 0;
        $liveVideo->is_active = 0;
        $liveVideo->updator_id = $this->authUser->id;
        $liveVideo->fine_uploader_uuid = $liveVideoDetails ['uuid'];
        $liveVideo->fine_uploader_name = $liveVideoDetails ['name'];

        /**
         * Save the video in the database.
         */
        if ($liveVideo->save()) {
            $typeId = $liveVideo->id;
            /**
             * Associate the newly added video with uncategorized category.
             */
            $return = $typeId;
        } else {
            $return = $typeId;
        }
        return $return;
    }

    /**
     * Function to update a video.
     *
     * @param integer $id
     * The id of the video.
     * @return boolean True if video updated successfully and False if not.
     */
    public function updateVideo($id)
    {
        /**
         * Check if the video id is not empty.
         */
        if (!empty ($id)) {
            /**
             * Set validation rules for edit functionality.
             */
            $liveVideo = $this->video->findorfail($id);
            if ($liveVideo->is_live) {
                $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, 'presenter' => 'required', 'description' => StringLiterals::REQUIRED, 'is_featured' => StringLiterals::REQUIREDINTEGER, StringLiterals::ISACTIVE => StringLiterals::REQUIREDINTEGER]);
            } else {
                $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, 'presenter' => 'required', StringLiterals::CATEGORYIDS => 'required|array', 'description' => StringLiterals::REQUIRED, 'is_featured' => StringLiterals::REQUIREDINTEGER, StringLiterals::ISACTIVE => StringLiterals::REQUIREDINTEGER, 'trailer_status' => 'required']);
            }
            $this->validate($this->request, $this->getRules());
            $liveVideo->title = $this->request->title;
            $liveVideo->description = $this->request->description;
            $liveVideo->trailer_status = ( int )$this->request->trailer_status;
            $liveVideo->is_active = ( int )$this->request->is_active;
            $liveVideo->published_on = Carbon::parse($this->request->published_on);
            $liveVideo->updator_id = $this->authUser->id;
            $liveVideo->video_order = ( int )$this->request->video_order;
            if ($this->request->has('presenter')) {
                $liveVideo->presenter = $this->request->presenter;
            }
            if ($this->request->has('tags')) {
                $this->videoTag->where(StringLiterals::VIDEOID, $id)->delete();
                foreach ($this->request->tags as $value) {
                    $tagInfo = $this->tag->where('name', $value)->first();
                    if (empty ($tagInfo)) {
                        $tagInfo = new Tag ();
                        $tagInfo->name = $value;
                        $tagInfo->save();
                    }
                    $tagInfo->videos()->attach($id);
                }
            }
            if ($this->request->has('mp3')) {
                $mp3Url = explode("/", $this->request->mp3);
                $liveVideo->mp3 = 'mp3/' . $mp3Url [count($mp3Url) - 1];
            }
            if ($this->request->has(StringLiterals::THUMBNAIL)) {
                $thumbUrl = explode("/", $this->request->thumbnail);
                $liveVideo->thumbnail_image = $thumbUrl [count($thumbUrl) - 1];
                $liveVideo->thumbnail_path = $thumbUrl [count($thumbUrl) - 1];
            }
            $isVideo = false;
            /**
             * Update the video details in the data base.
             */
            if ($liveVideo->save()) {
                $this->saveVideoCategories($id);
                $isVideo = true;
            }
            return $isVideo;
        } else {
            return false;
        }
    }

    /**
     * Function to get validation rules for video edit form.
     *
     * @return array The validation rules.
     */
    public function getVideoEditRules()
    {
        /**
         * Set rules for video edit feature.
         */
        $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, StringLiterals::CATEGORYID => StringLiterals::REQUIREDINTEGER, 'description' => StringLiterals::REQUIRED, 'is_featured' => StringLiterals::REQUIREDINTEGER, StringLiterals::ISACTIVE => StringLiterals::REQUIREDINTEGER, StringLiterals::TRAILER => 'url', 'presenter' => 'required']);

        return $this->getRules();
    }

    /**
     * Function to update image
     *
     * @param object $liveVideo
     * @return object
     */
    private function updateImage($liveVideo)
    {
        if ($this->request->has('pdf')) {
            $imageUrl = explode('/', $this->request->pdf);
            if ($imageUrl [0] != 'http:' && $imageUrl [0] != 'https:') {
                $liveVideo->pdf = $this->request->pdf;
            }
        }
        if ($this->request->has('word')) {
            $imageUrl = explode('/', $this->request->word);
            if ($imageUrl [0] != 'http:' && $imageUrl [0] != 'https:') {
                $liveVideo->word = $this->request->word;
            }
        }
        return $liveVideo;
    }

    /**
     * Function to get validation rules for video thumb upload form.
     *
     * @return array The validation rules.
     */
    public function getThumbUploadRules()
    {
        /**
         * Set rules for thumbnail upload feature.
         */
        $this->setRules([StringLiterals::THUMBNAIL => StringLiterals::REQUIRED]);
        return $this->getRules();
    }

    /**
     * Function to save categories of a live video in the db.
     *
     * @param integer $id
     * The id of the video whose categories are being saved.
     */
    public function saveVideoCategories($id)
    {
        $this->videoCategory = new VideoCategory ();
        $this->videoCategory->where(StringLiterals::VIDEOID, $id)->delete();
        if ($this->request->has(StringLiterals::CATEGORYIDS) && is_array($this->request->input(StringLiterals::CATEGORYIDS)) && count($this->request->input(StringLiterals::CATEGORYIDS)) > 0) {
            foreach ($this->request->input(StringLiterals::CATEGORYIDS) as $catId) {
                $this->videoCategory = new VideoCategory ();
                $this->videoCategory->video_id = $id;
                $this->videoCategory->category_id = $catId;
                $this->videoCategory->save();
                $liveVideo = Video::find($id);
                $categoryy = Category::where('id', $catId)->first();
                Cache::forget('relatedCategoryList' . $categoryy->slug);
                $liveVideo->collections()->detach();
            }
        }
        if ($this->request->has('exam_ids') && is_array($this->request->input('exam_ids')) && count($this->request->input('exam_ids')) > 0) {
            $liveVideo->collections()->attach(Group::whereIn('id', $this->request->exam_ids)->pluck('id')->toArray());
            $groups = Group::whereIn('id', $this->request->exam_ids)->get();
            foreach ($groups as $group) {
                Cache::forget('groupList' . $group->slug);
            }
        }
        $liveVideoClearCache = Video::where('id', $this->request->id);
        if ($liveVideoClearCache->has('playlists')->first() ['id']) {
            $play = $liveVideoClearCache->first()->playlists();
            $cslug = $play->get();
            foreach ($cslug as $playlist) {
                Cache::forget('playlistList' . $playlist->slug);
            }
        }
    }

    

    /**
     * Function to update thumbnail of a video.
     * @param integer $id
     * The id of the video
     * @return boolean True if uploaded successfully and false if not.
     */
    public function updateThumbnail($id)
    {
        /**
         * Check if the video id for the thumbnail is not empty.
         */
        if (!empty ($id)) {
            /**
             * Set the validation rules for the thumbnail.
             */
            $this->setRules([StringLiterals::THUMBNAIL => StringLiterals::REQUIRED]);
            /**
             * Perform validation for the thumbnail upload.
             */
            $this->validate($this->request, $this->getRules());

            $this->fileRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->setRequestParamKey(StringLiterals::THUMBNAIL)->setConfig();

            $liveVideo = $this->video->findorfail($id);
            if ($this->request->has(StringLiterals::THUMBNAIL)) {
                /**
                 * Upload the thumbnail for live videos.
                 */
                $this->fileRepository->handleUpload($liveVideo);
                $hasThumb = true;
            } else {
                $hasThumb = false;
            }
            return $hasThumb;
        } else {
            return false;
        }
    }

    /**
     * Function to archive live videos in the database.
     *
     * @param integer|array $ids - ids of the videos which are to be deleted.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function videoDelete($ids)
    {
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        return empty ($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::IS_ARCHIVED => 1, 'archived_on' => Carbon::now()]);
    }

    /**
     * Function to activate the videos
     *
     * @param integer|array $ids
     * The ids of the videos which are to be activated.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function videoActivateOrDeactivate($ids, $status)
    {
        /**
         * Delete the live video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1 else set is_active field to 0.
         */
        if ($status == 'activate') {
            return empty ($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
        } else if ($status == 'deactivate') {
            return empty ($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
        }
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded for live videos
     *
     * @vendor Contus
     *
     * @package Collection
     * @return Contus\Collection\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        /**
         * To load video data in the grid based on the request
         */
        $this->setGridModel($this->video)->setEagerLoadingModels(['videocategory.category', 'collections']);
        return $this;
    }

    /**
     * update grid records collection query
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        /**
         * updated the grid query by using this function and apply the video condition.
         */
        $filterArray = $this->request->input('filters');
        
        if (!empty ($filterArray)) {
            foreach ($filterArray as $value) {
             if($value == "live_videos") {
                return $builder->where(StringLiterals::IS_ARCHIVED, 0)->where('is_live', 1);
             }
            }
        }
       
    }

    /**
     * Function to apply filter for search of videos grid
     *
     * @param mixed $builderLiveVideos
     * @return \Illuminate\Database\Eloquent\Builder $builderVideos The builder object of videos grid.
     */
    protected function searchFilter($builderLiveVideos)
    {
        $searchLiveVideos = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $isActive = $type = null;
        extract($searchLiveVideos);
        /**
         * Check if the title of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderLiveVideos = $builderLiveVideos->where(StringLiterals::TITLE, 'like', '%' . $title . '%');
        }
        /**
         * Check if the status of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if (is_numeric($isActive)) {
            $builderLiveVideos = $builderLiveVideos->where(StringLiterals::ISACTIVE, $isActive);
        }
        /**
         * Check if the type of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if ($type == "wowza") {
            $builderLiveVideos = $builderLiveVideos->where(StringLiterals::USERNAME, $type);
        } else if ($type != null && $type != 'all') {
            $builderLiveVideos = $builderLiveVideos->where(StringLiterals::YOUTUBE_PRIVACY, $type);
        }

        return $builderLiveVideos;
    }

    /**
     * Fetch video to edit.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getVideo($videoId)
    {
        return $this->video->with('videocategory.category.videos.transcodedvideos', 'transcodedvideos.presets', 'tags', 'collections')->where('id', $videoId)->where(StringLiterals::IS_ARCHIVED, 0)->first();
    }

    /**
     * Function to fetch all the details of a live videos from the database.
     *
     * @param integer $id
     * The id of the video whose data are to be fetched.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|NULL The information of the video.
     */
    public function getCompleteVideoDetails($videoId)
    {
        $this->video = $this->video->with(['tags', 'categories.parent_category.parent_category', 'collections'])->where('id', $videoId)->where(StringLiterals::IS_ARCHIVED, 0)->first();
        $this->video->recent = $this->video->recent()->get()->count();
        $this->video->authfavourites = $this->video->authfavourites()->get()->count();
        return $this->video;
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Collection
     * @return array
     */
    public function getGridHeadings()
    {
        $filterArray = $this->request->input('filters');
        $isLive = false;
        if (!empty ($filterArray)) {
            foreach ($filterArray as $value) {
                if ($value == 'live_videos') {
                    $isLive = true;
                }
            }
        }
        if ($isLive) {
            return [StringLiterals::GRIDHEADING => [['name' => trans('video::videos.title'), StringLiterals::VALUE => StringLiterals::TITLE, 'sort' => true], ['name' => trans('video::videos.status'), StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false], ['name' => 'Type', StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false], ['name' => trans('video::videos.status'), StringLiterals::VALUE => 'liveStatus', 'sort' => false], ['name' => trans('video::videos.scheduled_on'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.added_on'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.action'), StringLiterals::VALUE => '', 'sort' => false]]];
        } else {
            return [StringLiterals::GRIDHEADING => [['name' => trans('video::videos.title'), StringLiterals::VALUE => StringLiterals::TITLE, 'sort' => true], ['name' => trans('video::videos.sections'), StringLiterals::VALUE => StringLiterals::CATEGORYID, 'sort' => false], ['name' => trans('video::videos.exams_groups'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.status'), StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false], ['name' => trans('video::videos.upload_status'), StringLiterals::VALUE => 'job_status', 'sort' => false], ['name' => trans('video::videos.added_on'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.action'), StringLiterals::VALUE => '', 'sort' => false]]];
        }
    }

    /**
     * Repository function to delete poster of a video.
     *
     * @param integer $id
     * The id of the poster.
     * @return boolean True if the poster is deleted and false if not.
     */
    public function deletePoster($id)
    {
        /**
         * Check if poster id exists.
         */
        if (!empty ($id)) {
            $liveVideoPoster = VideoPoster::findorfail($id);
            /**
             * Delete the poster image using the image path field from the database.
             */
            if (file_exists($liveVideoPoster->image_path) && unlink($liveVideoPoster->image_path)) {
                /**
                 * Delete the poster in the database.
                 */
                $liveVideoPoster->delete();
                $deleteStatus = true;
            } else {
                $deleteStatus = false;
            }
            return $deleteStatus;
        } else {
            return false;
        }
    }

    /**
     * Repository function to delete cast image of a video.
     *
     * @param integer $id
     * The id of the cast image.
     * @return boolean True if the cast image is deleted and false if not.
     */
    public function deleteCastImage($id)
    {
        /**
         * Check if cast id exists.
         */
        if (!empty ($id)) {
            $liveVideoCast = VideoCast::findorfail($id);
            /**
             * Delete the cast image using the image path field from the database.
             */
            if (file_exists($liveVideoCast->image_path) && unlink($liveVideoCast->image_path)) {
                /**
                 * Delete the cast image in the database.
                 */
                $liveVideoCast->image_url = null;
                $liveVideoCast->image_path = null;
                $liveVideoCast->save();
                $deleteStatus = true;
            } else {
                $deleteStatus = false;
            }
            return $deleteStatus;
        } else {
            return false;
        }
    }
}
