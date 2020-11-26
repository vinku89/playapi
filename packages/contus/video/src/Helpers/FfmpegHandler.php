<?php

namespace Contus\Video\Helpers;

class FfmpegHandler
{
    private $filePath;
    private $folderPath;
    private $keyFile;
    private $keyFilePath;
    private $m3u8FilePath;
    public $destinationFolder;
    private $hlsQualities = ['426x240', '640x360', '960x540', '1280x720', '1920x1080'];
    private $oldName;
    private $newName;
    private $uuid;

    public function __construct($oldName, $uuid, $awsPrefix)
    {
        $this->oldName = $oldName;
        $this->uuid = $uuid;
        $this->keyFile = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'enc.keyinfo');
        $this->keyFilePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'enc.key');
        $this->m3u8FilePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'playlist.m3u8');
        $this->filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $this->uuid . DIRECTORY_SEPARATOR . $this->oldName);
        $this->folderPath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $this->uuid);
        $this->checkAndCreateDir(base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'convert'));
        $this->awsPrefix = $awsPrefix;
    }

    public function clearFolders($destinationFolder = null, $folderPath = null)
    {
        if ($destinationFolder) {
            $this->destinationFolder = $destinationFolder;
        }
        if ($folderPath) {
            $this->folderPath = $folderPath;
        }
        if (is_dir($this->destinationFolder)) {
            shell_exec("rm -rf " . $this->destinationFolder);
        }
        if (is_dir($this->folderPath)) {
            shell_exec("rm -rf " . $this->folderPath);
        }
    }

    public function generateNewName()
    {
        $pathinfo = pathinfo($this->oldName);
        $extension = $pathinfo['extension'];
        $this->newName = $pathinfo['filename'];
        $current_time = \Carbon\Carbon::now()->timestamp;
        $this->newName = str_replace(' ', '_', $this->newName);
        $this->newName = preg_replace('/[^A-Za-z0-9\-]/', '', $this->newName) . $current_time . '.' . $extension;
        return $this->newName;
    }

    public function prepareTranscode()
    {
        rename($this->filePath, $this->folderPath . DIRECTORY_SEPARATOR . $this->newName);
        $this->filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $this->uuid . DIRECTORY_SEPARATOR . $this->newName);
        $this->destinationFolder = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'convert' . DIRECTORY_SEPARATOR . $this->uuid . DIRECTORY_SEPARATOR);
        $this->checkAndCreateDir($this->destinationFolder);
        $content = "enc.key\r\n" . $this->destinationFolder . "/enc.key\r\nad62700d4e74263579281a3762f8b724";
        file_put_contents($this->keyFile, $content, LOCK_EX);
        \File::copy($this->keyFilePath, $this->destinationFolder . 'enc.key');
        \File::copy($this->m3u8FilePath, $this->destinationFolder . '/playlist.m3u8');
    }

    public function transcode()
    {
        $this->validateFilesFolders();
        $hlsParams = $this->generatePramsHLS();
        $comment = 'nice ffmpeg -i ' . $this->filePath . $hlsParams;
        $output = shell_exec($comment . "  2>&1; echo $?");
        $output = explode(PHP_EOL, $output);
        return $output[count($output) - 2] === '0';
    }

    private function changeUrlForKey()
    {
        $folderURI = $this->destinationFolder;
        $file = "$folderURI/playlist.m3u8";
        $playlistFile = (string) file_get_contents($file);
        $playlistExplode = explode("\n", $playlistFile);
        $formats = [];
        foreach ($playlistExplode as $playlistLine) {
            if (strpos($playlistLine, '.m3u8') !== false) {
                $formats[] = $playlistLine;
            }
        }
        foreach ($formats as $format) {
            $formatResult = $folderURI . '/' . $format;
            $formatResultBody = (string) file_get_contents($formatResult);
            $replaceableText = "enc.key";
            $formatResultBody = str_replace($replaceableText, env('TRANSCODER_KEY_VALIDATE_DOMAIN') . '/api/v1/key?key=FFMPEG/' . $this->awsPrefix . '/' . $format, $formatResultBody);
            $updateFile = fopen($formatResult, "w");
            fwrite($updateFile, $formatResultBody);
            fclose($updateFile);
        }
    }

    private function generatePramsHLS()
    {
        $params = '';
        foreach ($this->hlsQualities as $quality) {
            $params .= ' -ac 2 -threads 3 -profile:v main -s ' . $quality . ' -q:v 1 -hls_list_size 0 -strict -2 -hls_time 5 -hls_key_info_file ' . $this->keyFile . ' -hls_segment_filename "' . $this->destinationFolder . $quality . 'fileSequences%d.ts" ' . $this->destinationFolder . $quality . 'upload_prog_indexes.m3u8';
        }
        return $params;
    }

    private function validateFilesFolders()
    {
        if (count($this->hlsQualities) === 0) {
            abort(500, 'ffmpeg quality list not found');
        }
        if (!is_dir($this->destinationFolder)) {
            abort(500, 'Destination folder not found');
        }
        if (!file_exists($this->keyFilePath)) {
            abort(500, 'keyFile not found');
        }
        if (!file_exists($this->keyFile)) {
            abort(500, 'keyFile not found');
        }
        if (!file_exists($this->m3u8FilePath)) {
            abort(500, 'm3u8 file not found');
        }
        if (!file_exists($this->filePath)) {
            abort(500, 'File not found');
        }
    }

    private function checkAndCreateDir($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
            chmod($folder, 0777);
        }
    }

}
