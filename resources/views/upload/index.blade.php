<x-app-layout>
{{--
    <!-- The `multiple` attribute lets users select multiple files. -->
    <input type="file" id="file-selector">

    <div class="js-upload-file-progress" style="display: none">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
    </div>--}}


    Add a video here:
    <br>
    <input type="file" id="video-url-example">
    <br>



    <br>
    <div id="video-information" style="width: 50%"></div>
    <div id="chunk-information" style="width: 50%"></div>

</x-app-layout>
