<?php

namespace App\Http\Controllers;

use App\LaraVideoStream;
use App\Models\Video;
use App\VideoStream;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::paginate();

        return view('video.index', compact('videos'));
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

    public function delete(int $id)
    {

        $video = Video::where('id', $id)->first();
        if ($video) {
            $video->file()->delete();
            $video->delete();
        }

        return redirect(route('video.index'));
    }

    public function download(int $id)
    {
        $video = Video::where('id', $id)->first();
        $file = storage_path($video->file()->file_path);

        header("Expires: 0");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $basename = pathinfo($file, PATHINFO_BASENAME);

        header("Content-type: application/" . $ext);
        header('Content-length: ' . filesize($file));
        header("Content-Disposition: attachment; filename=\"$basename\"");

        set_time_limit(0);
        $file = @fopen($file, "rb");
        while (!feof($file)) {
            print(@fread($file, 1024 * 8));
            ob_flush();
            flush();
        }
        exit;
    }
}
