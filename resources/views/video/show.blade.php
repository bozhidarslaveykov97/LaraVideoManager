

<video id="js-video-player" width="610" height="610" preload="metadata" autoplay controls>
    <source src="{{url(route('video.stream', $video->id))}}">
    Your browser does not support the video tag.
</video>
