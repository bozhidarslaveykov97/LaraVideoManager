require('bootstrap');
import $ from "jquery";

class LaraVideoUploader {

    constructor() {

        this.chunkStart = 0;
        this.chunkEnd = 0;
        this.chunkCounter = 0;

        //break into 1 MB chunks for demo purposes
        this.chunkSize = 1000000;
    }

    setFileSelector(selector) {
        this.fileSelector = selector;
    }

    run() {
        this.fileInput = document.querySelector(this.fileSelector);
        this.fileInput.addEventListener('change', () => {

            this.selectedFile = this.fileInput.files[0];

            // Calculate the num of chunks for selected file
            this.numberOfChunks = Math.ceil(this.selectedFile.size / this.chunkSize);

            // Reset the chunk start
            this.chunkStart = 0;

            // Calculate the cunk end
            this.chunkEnd = this.chunkStart + this.chunkSize;

            // Create the chunk
            this.createChunk();

            document.getElementById("video-information").innerHTML = "There will be " + this.numberOfChunks + " chunks uploaded.";

        });
    }

    createChunk() {
        this.chunkCounter++;
        console.log("created chunk: ", this.chunkCounter);

        this.chunkEnd = Math.min(this.chunkStart + this.chunkSize, this.selectedFile.size);
        this.chunk = this.selectedFile.slice(this.chunkStart, this.chunkEnd);

        console.log("i created a chunk of video" + this.chunkStart + "-" + this.chunkEnd + " minus 1");

        this.chunkForm = new FormData();
        this.chunkForm.append('file', this.chunk);

        console.log("added file");

        this.uploadChunk();

    }

    uploadChunk() {

        var oReq = new XMLHttpRequest();
        oReq.upload.addEventListener("progress", this.updateProgress);
        oReq.open("POST", '/upload-chunk', true);
        var blobEnd = this.chunkEnd - 1;
        var contentRange = "bytes " + this.chunkStart + "-" + blobEnd + "/" + this.selectedFile.size;
        oReq.setRequestHeader("Content-Range", contentRange);
        console.log("Content-Range", contentRange);

        oReq.onload = function (oEvent) {
            // Uploaded.
            console.log("uploaded chunk");
            console.log("oReq.response", oReq.response);
            // var resp = JSON.parse(oReq.response)

            //we start one chunk in, as we have uploaded the first one.
            //next chunk starts at + chunkSize from start
            this.chunkStart += this.chunkSize;
            //if start is smaller than file size - we have more to still upload
            if (this.chunkStart < this.selectedFile.size) {
                //create the new chunk
                this.createChunk();
            } else {
                console.log("all uploaded!");
                document.getElementById("video-information").innerHTML = "all uploaded! Watch the video";
            }

        };
        oReq.send(this.chunkForm);
    }

    updateProgress(oEvent) {
        if (oEvent.lengthComputable) {

            var percentComplete = Math.round(oEvent.loaded / oEvent.total * 100);
            var totalPercentComplete = Math.round((this.chunkCounter - 1) / this.numberOfChunks * 100 + percentComplete / this.numberOfChunks);

            document.getElementById("chunk-information").innerHTML = "Chunk # " + this.chunkCounter + " is " + percentComplete + "% uploaded. Total uploaded: " + totalPercentComplete + "%";
            //  console.log (percentComplete);
            // ...
        } else {
            console.log("not computable");
            // Unable to compute progress information since the total size is unknown
        }
    }

}

var uploader = new LaraVideoUploader();
uploader.setFileSelector('#video-url-example');
uploader.run();

/*

const fileSelector = document.getElementById('file-selector');
fileSelector.addEventListener('change', (event) => {
    const file = event.target.files[0];
    readFile(file);
});


function changeTheProgress(progress) {
    const bootstrapProgressBar = $('.js-upload-file-progress').find('.progress-bar');
    bootstrapProgressBar.attr('aria-valuenow', progress);
    bootstrapProgressBar.width(progress + '%');
    bootstrapProgressBar.html(progress);
}

function readFile(file) {

    $('.js-upload-file-progress').fadeIn();

    var reader = new FileReader();
    reader.addEventListener('load', (event) => {
        const result = event.target.result;
        // Do something with result
    });

    reader.addEventListener('progress', (event) => {
        if (event.loaded && event.total) {
            var percent = (event.loaded / event.total) * 100;
            var progress = Math.round(percent);

          /!*  changeTheProgress(progress);

            if (progress == 100) {
                changeTheProgress(0);
                $('.js-upload-file-progress').fadeOut();
            }*!/

            console.log("Progress: " + progress);
        }
    });
    reader.readAsDataURL(file);
}
*/

