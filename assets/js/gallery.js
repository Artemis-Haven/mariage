import '../css/gallery.css';

const $ = require('jquery');
window.Dropzone = require('dropzone/dist/min/dropzone.min');

Dropzone.autoDiscover = false;
$(function () {
    var responses = [];
    var dropzone = new Dropzone(".gallery_upload_dropzone", {
        url: uploadUrl,
        paramName: "file",
        maxFilesize: 4,
        maxFiles: 6,
        parallelUploads: 6,
        acceptedFiles: 'image/*',
        autoProcessQueue: false,
        uploadMultiple: true,
        dictDefaultMessage: "Cliquez ici pour ajouter des photos depuis votre ordinateur",
        init: function () {
            this.on("success", function (file, response) {
                dropzone.options.autoProcessQueue = true;
                responses.push(response);
            });
            this.on("queuecomplete", function () {
                dropzone.options.autoProcessQueue = false;
            });
            this.on("sending", function (file, xhr, data) {
                //data.append("name", $('.gallery-upload__gallery-name').val());
            });
            this.on("completemultiple", function () {
                // Pretty naive appreach but will do the job for this example app
                if (responses[0]) {
                    window.location = responses[0].redirectUrl;
                }
            });
        }
    });
    var galleryDropzone = Dropzone.forElement(".gallery_upload_dropzone");
    var $uploadBtn = $('.gallery_upload_btn');
    $uploadBtn.on('click', function () {
        if (galleryDropzone.getQueuedFiles() < 1) {
            alert('Vous devez sélectionner au moins une photo.');
            return;
        }
        galleryDropzone.processQueue();
    });
});

$('.delete-photo-btn').click(function(e) {
    if (!confirm('Etes-vous sûr de vouloir supprimer cette photo ?')) {
        e.preventDefault();
    }
});