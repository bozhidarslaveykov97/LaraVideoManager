
<x-app-layout>

    <div class="row">
        <div class="col-md-12">
            <h2>{{$video->name}}</h2>
            <video id="js-video-player" style="width: 100%" autoplay controls>
                <source src="{{url(route('video.stream', $video->id))}}">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="col-md-6">
            <a href="{{route('video.index')}}" class="btn btn-outline-primary"><i class="fa fa-list"></i> Back to videos</a>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{route('video.download', $video->id)}}" class="btn btn-outline-success"><i class="fa fa-download"></i> Download Video</a>
            <a href="{{route('video.delete', $video->id)}}" class="btn btn-outline-danger"><i class="fa fa-times"></i> Delete Video</a>
        </div>
    </div>

</x-app-layout>
