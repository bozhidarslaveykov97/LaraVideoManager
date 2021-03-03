

<video id="js-video-player" width="620" height="640" preload="metadata" controls>
    <source src="{{url(route('video.stream', $video->id))}}">
    Your browser does not support the video tag.
</video>
