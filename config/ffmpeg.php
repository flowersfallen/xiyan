<?php

return [
    'ffmpeg' => env('FFMPEG_PATH', '/usr/local/bin/ffmpeg'),
    'ffprobe' => env('FFPROBE_PATH', '/usr/local/bin/ffprobe'),
    'timeout' => env('FFMPEG_TIMEOUT', 3600),
    'threads' => env('FFMPEG_THREADS', 12)
];
