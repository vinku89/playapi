<?php
/**
 * Ffmpeg Repository
 *
 * To manage the functionalities related to ffmpeg
 *
 * @name FfmpegVideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http: www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Ffmpegstatus;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Notification\Repositories\NotificationRepository;

class FfmpegVideoRepository extends BaseRepository
{

    /**
     * Function to update ffmpeg status
     *
     * @param integer $status
     * @return void
     */
    public function changeFfmpegStatus($status)
    {
        $ffmpegStatus = Ffmpegstatus::get()->first();
        $ffmpegStatus->status = $status;
        return $ffmpegStatus->save();
    }

    /**
     * Function to upload files after transcoder.
     *
     * @param string $destinationFolder
     * @param object $videoObject
     * @return void
     */
    public function uploadFilesToS3($destinationFolder, $videoObject, $randomFileDir)
    {
        $source = rtrim(trim($destinationFolder), "/");
        if ($handle = opendir($source)) {
            $awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
            while (false !== ($file = readdir($handle))) {
                if ($file !== "." && $file !== ".." && $file !== ".DS_Store") {
                    $awsRepository->uploadConvertedFiles($source, $file, $randomFileDir);
                    $region = env('AWS_REGION');
                    $awsS3Bucket = env('AWS_BUCKET');
                    $awsBaseUrl = env('AWS_BUCKET_URL');
                    $videoNewURL = 'https://s3.' . $region . '.amazonaws.com/' . $awsS3Bucket . '/FFMPEG/' . $randomFileDir . '/playlist.m3u8';
                    $videoNewURL = $awsBaseUrl . '/FFMPEG/' . $randomFileDir . '/playlist.m3u8';
                    $videoObject->hls_playlist_url = $videoNewURL;
                    $videoObject->aws_prefix = 'FFMPEG/' . $randomFileDir;
                    $videoObject->save();
                }
            }
            closedir($handle);
            $videoObject->job_status = 'Complete';
            NotificationRepository::notify('new_video', $videoObject->id);
            $videoObject->save();
        }
    }

    /**
     * Function to check if a file is valid video file or not.
     *
     * @param string $file
     * @return boolean
     */
    public function isValidFile($file)
    {
        $validFileTypes = ['video/mp4', 'video/quicktime', 'video/avi', 'video/x-ms-wmv', 'video/msvideo', 'video/x-msvideo'];
        return (file_exists($file) && in_array(mime_content_type($file), $validFileTypes)) ? 1 : 0;
    }

    /**
     * Function to delete invalid file and set the status of the video to error.
     *
     * @param object $videoObject
     * @return void
     */
    public function setErrorStatus($videoObject)
    {
        $videoObject->job_status = 'Error';
        $videoObject->save();
        $filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoObject->fine_uploader_uuid . DIRECTORY_SEPARATOR . $videoObject->fine_uploader_name);
        $folderPath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoObject->fine_uploader_uuid);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        if (file_exists($folderPath)) {
            rmdir($folderPath);
        }
    }
}
