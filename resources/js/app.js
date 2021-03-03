import $ from "jquery";
require('bootstrap')

const LaraVideoUploader = require('./lara-video-uploader');
const uploader = new LaraVideoUploader();
uploader.setUploadUrl('/upload-chunk');
uploader.setFileSelector('#js-upload-video-file');
uploader.setCsrfToken($('meta[name="csrf-token"]').attr('content'));
uploader.run();
