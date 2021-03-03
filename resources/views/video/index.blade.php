<x-app-layout>

    <div class="row">
        <div class="col-md-12 pb-3 pt-3">
            <a href="{{route('upload.index')}}" class="btn btn-outline-primary"><i class="fa fa-upload"></i> Upload new video</a>
        </div>
        <div class="col-md-12">

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <th style="width:160px">#</th>
                    <th>Filename</th>
                    <th>Filesize</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th style="width:160px">Action</th>
                    </thead>
                    <tbody>
                    @php
                    foreach($videos as $video):
                    $videoFile = $video->file();
                    if (empty($videoFile)) {
                        continue;
                    }
                    @endphp
                    <tr>
                        <td>
                            <img src="{{url('storage/' . $videoFile->thumbnail_name)}}" data-original="{{url('storage/' . $videoFile->thumbnail_name)}}" data-gif="{{url('storage/' . $videoFile->thumbnail_gif_name)}}" class="js-on-hover-gif" width="100%" />
                        </td>
                        <td>{{$video->name}}</td>
                        <td>{{filesize_formatted($videoFile->file_size)}}</td>
                        <td>{{$video->created_at}}</td>
                        <td>{{$video->updated_at}}</td>
                        <td>
                            <a href="{{route('video.show', $video->id)}}" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>
                            <a href="{{route('video.download', $video->id)}}" class="btn btn-outline-success"><i class="fa fa-download"></i></a>
                            <a href="{{route('video.delete', $video->id)}}" class="btn btn-outline-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-app-layout>
