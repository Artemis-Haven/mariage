import '../css/gallery.css';

const $ = require('jquery');
window.Dropzone = require('dropzone/dist/min/dropzone.min');
require('magnific-popup');

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

$.extend(true, $.magnificPopup.defaults, {
    tClose: 'Fermer (Esc)', // Alt text on close button
    tLoading: 'Chargement des photos...', // Text that is displayed during loading. Can contain %curr% and %total% keys
    gallery: {
        tPrev: 'Précédent', // Alt text on left arrow
        tNext: 'Suivant', // Alt text on right arrow
        tCounter: '%curr% sur %total%' // Markup for "1 of 7" counter
    },
    image: {
        tError: '<a href="%url%">L\'image</a> n\'a pas pu être chargée.' // Error message when image could not be loaded
    },
    ajax: {
        tError: '<a href="%url%">Le contenu</a> n\'a pas pu être chargée.' // Error message when ajax request failed
    }
});

$('.gallery-container').magnificPopup({
    delegate: 'a', // child items selector, by clicking on it popup will open
    type: 'image',
    gallery: {
        enabled: true
    },
    disableOn: 600,
    image: {
        titleSrc: function (item) {
            return "<a href='" + item.el.prop('href') + "'>Lien permanent vers la photo</a>";
        }
    }
});