<?php

namespace App;

/**
 * @author Bozhidar Slaveykov
 * @email selfworksbg@gmail.com
 * @package LaraVideoStream
 * @description Play big video files requested from the browser on parts
 */

class LaraVideoStream
{
    private $path = "";
    private $stream = "";
    private $buffer = 102400;
    private $start = -1;
    private $end = -1;
    private $size = 0;
    private $contentType = 'video/mp4';

    function __construct($filePath)
    {
        $this->path = $filePath;
    }

    public function setContentType($type)
    {
        $this->contentType = $type;
    }

    /**
     * Open stream
     */
    private function open()
    {
        if (!($this->stream = fopen($this->path, 'rb'))) {
            die('Could not open stream for reading');
        }
    }

    /**
     * Set proper header to serve the video content
     */
    private function setHeader()
    {
        ob_get_clean();

       // header("Content-Type: " . $this->contentType);
        header("Cache-Control: max-age=2592000, public");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT');
        header("Last-Modified: " . gmdate('D, d M Y H:i:s', @filemtime($this->path)) . ' GMT');

        $this->start = 0;
        $this->size = filesize($this->path);
        $this->end = $this->size - 1;

        header("Accept-Ranges: 0-" . $this->end);

        if (isset($_SERVER['HTTP_RANGE'])) {

            $currentStart = $this->start;
            $currentEnd = $this->end;

            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                exit;
            }
            if ($range == '-') {
                $currentStart = $this->size - substr($range, 1);
            } else {
                $range = explode('-', $range);
                $currentStart = $range[0];

                $currentEnd = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : ($currentStart + (1024000 * 3)); // If is not isset current range, we will output the 3MB of video
            }

            $currentEnd = ($currentEnd > $this->end) ? $this->end : $currentEnd;

            if ($currentStart > $currentEnd || $currentStart > $this->size - 1 || $currentEnd >= $this->size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                exit;
            }

            $this->start = $currentStart;
            $this->end = $currentEnd;
            $length = $this->end - $this->start + 1;

            fseek($this->stream, $this->start);

            header('HTTP/1.1 206 Partial Content');
            header("Content-Length: " . $length);
            header("Content-Range: bytes $this->start-$this->end/" . $this->size);
        } else {
            header("Content-Length: " . $this->size);
        }

    }

    /**
     * close curretly opened stream
     */
    private function end()
    {
        fclose($this->stream);
        exit;
    }

    /**
     * perform the streaming of calculated range
     */
    private function stream()
    {
        $i = $this->start;
        set_time_limit(0);
        while (!feof($this->stream) && $i <= $this->end) {
            $bytesToRead = $this->buffer;
            if (($i + $bytesToRead) > $this->end) {
                $bytesToRead = $this->end - $i + 1;
            }
            $data = fread($this->stream, $bytesToRead);
            echo $data;
            flush();
            $i += $bytesToRead;
        }
    }

    /**
     * Start streaming video content
     */
    function start()
    {
        $this->open();
        $this->setHeader();
        $this->stream();
        $this->end();
    }
}
