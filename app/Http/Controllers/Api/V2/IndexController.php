<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\FileRequest;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

class IndexController extends BaseController
{
    public function file(FileRequest $request)
    {
        $file = $request->file('file');

        $error = $file->getError();
        if ($error != 0) {
            return $this->formatReturn([
                'state' => false,
                'error' => '文件上传失败'
            ]);
        }

        $fileType = '';
        $mineType = $file->getMimeType();
        $mineArray = explode('/', $mineType);
        if ($mineArray[0] == 'image') {
            $fileType = 'image';
        }
        if ($mineArray[0] == 'video') {
            // $fileType = 'video'; // 先不加视频
        }
        if (!$fileType) {
            return $this->formatReturn([
                'state' => false,
                'error' => '文件类型有误'
            ]);
        }

        $path = $file->store('public/'.date('Ymd'));
        $data = [
            'fileType' => $fileType,
            'path' => $path,
            'url' => path_url($path)
        ];

        if ($fileType == 'image') {
            $image = Image::make(Input::file('file'));
            $data['height'] = $image->height();
            $data['width'] = $image->width();
            $data['filesize'] = $image->filesize();
            $normal = $data;
            // 小图
            $smallPath = $path.'.small.png';
            $smallWidth = config('thumbsize.small');
            $image->fit($smallWidth)->save(storage_path('app').'/'.$smallPath);
            $data['small_path'] = $smallPath;
            $data['small_url'] = path_url($smallPath);
            $data['small_width'] = $smallWidth;
            $data['small_height'] = $smallWidth;
            $data['small_filesize'] = $image->filesize();
            // 中等
            $check = floor($data['filesize'] / 1000000);
            if ($check > 0) {
                $image = Image::make(Input::file('file'));
                $middlePath = $path.'.middle.png';
                $middleWidth = config('thumbsize.middle');
                $image->fit($middleWidth)->save(storage_path('app').'/'.$middlePath);
                $data['middle_path'] = $middlePath;
                $data['middle_url'] = path_url($middlePath);
                $data['middle_width'] = $middleWidth;
                $data['middle_height'] = $middleWidth;
                $data['middle_filesize'] = $image->filesize();
            } else {
                $data['middle_path'] = $normal['path'];
                $data['middle_url'] = $normal['url'];
                $data['middle_width'] = $normal['width'];
                $data['middle_height'] = $normal['height'];
                $data['middle_filesize'] = $normal['filesize'];
            }
        }

        if ($fileType == 'video') {
            $ffmpeg = config('ffmpeg.ffmpeg');
            $ffprobe = config('ffmpeg.ffprobe');
            $timeout = config('ffmpeg.timeout');
            $threads = config('ffmpeg.threads');
            if (!$ffmpeg || !$ffprobe) {
                return $this->formatReturn([
                    'state' => false,
                    'data' => '截图服务ffmpeg未配置'
                ]);
            }
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => $ffmpeg,
                'ffprobe.binaries' => $ffprobe,
                'timeout' => $timeout,
                'ffmpeg.threads' => $threads,
            ]);
            $file = storage_path('app').'/'.$path;
            $thumbFile = $file.'.png';
            $thumbPath = $path.'.png';

            $video = $ffmpeg->open($file);
            $video->frame(TimeCode::fromSeconds(3))->save($thumbFile);

            $image = Image::make($thumbFile);
            $data['thumb'] = path_url($thumbPath);
            $data['height'] = $image->height();
            $data['width'] = $image->width();
            // 小图
            $smallPath = $thumbPath.'.small.png';
            $smallWidth = config('thumbsize.small');
            $image->fit($smallWidth)->save(storage_path('app').'/'.$smallPath);
            $data['small_path'] = $smallPath;
            $data['small_url'] = path_url($smallPath);
            $data['small_width'] = $smallWidth;
            $data['small_height'] = $smallWidth;
        }

        return $this->formatReturn([
            'state' => true,
            'data' => $data
        ]);
    }
}