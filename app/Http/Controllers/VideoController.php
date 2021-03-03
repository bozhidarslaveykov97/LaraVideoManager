<?php

namespace App\Http\Controllers;

use App\LaraVideoStream;
use App\Models\Video;
use App\VideoStream;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {

    }

    public function show(int $id)
    {
        $video = Video::where('id', $id)->first();

        return view('video.show', compact('video'));
    }

    public function stream(int $id)
    {
        $video = Video::where('id', $id)->first();
        $path = storage_path($video->file()->file_path);

        $stream = new LaraVideoStream($path);
        $stream->start();

    }
}
