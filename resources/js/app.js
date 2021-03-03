import $ from "jquery";

require('bootstrap')

$(document).ready(function () {

    $('.js-on-hover-gif').mouseenter(function () {
        $(this).attr('src', $(this).data('gif'));
    });

    $('.js-on-hover-gif').mouseleave(function () {
        $(this).attr('src', $(this).data('original'));
    });
});


const LaraVideoUploader = require('./lara-video-uploader');
const uploader = new LaraVideoUploader();
uploader.setUploadUrl('/upload-chunk');
uploader.setFileSelector('#js-upload-video-file');
uploader.setCsrfToken($('meta[name="csrf-token"]').attr('content'));
uploader.run();
