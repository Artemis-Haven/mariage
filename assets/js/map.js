import '../css/map.css';
const $ = require('jquery');
require('leaflet');
require('leaflet-polylinedecorator');
require('leaflet.polyline.snakeanim');
require('overlapping-marker-spiderfier-leaflet');
require('mobile-detect');

$(function () {
    // Création du fond de la carte
    var myMap = L.map('mapid').setView([-17.0373835, -150.4659964], 9);
    var background_watercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
        attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: 'abcd',
        minZoom: 1,
        maxZoom: 16,
        ext: 'png'
    });
    var background_labels = L.tileLayer('https://{s}.tile.openstreetmap.se/hydda/roads_and_labels/{z}/{x}/{y}.{ext}', {
        attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: 'abcd',
        minZoom: 0,
        maxZoom: 20,
        ext: 'png'
    });
    background_watercolor.addTo(myMap);
    background_labels.addTo(myMap);
    // Gestion des marqueurs trop rapprochés
    var oms = new OverlappingMarkerSpiderfier(myMap, {keepSpiderfied: true});
    oms.legColors.usual = '#705745';
    oms.legColors.highlighted = '#d82308';
    // Création des marqueurs
    var markerIcon = L.icon({
        iconUrl: '/images/map/red_flag.png',
        iconSize: [32, 32],
        iconAnchor: [16, 16]
    });
    var markers = markerList;
    for (var i = 0; i < markers.length; i++) {
        var data = markers[i];
        var marker = L.marker([data.latitude, data.longitude], {icon: markerIcon, zIndexOffset: 100}).addTo(myMap);
        //marker.bindPopup("<b>"+data.title+"</b><br>"+data.desc)
        marker.desc = generatePostcardContent(data);
        oms.addMarker(marker);
    }
    // Créationd de la popup "Carte postale"
    var popup = new L.Popup({
        closeButton: false, 
        offset: new L.Point(0.5, -24),
        minWidth: 500,
        maxWidth: 500,
    });
    oms.addListener('click', function(marker) {
        popup.setContent(marker.desc);
        popup.setLatLng(marker.getLatLng());
        myMap.openPopup(popup);
    });
    // Création de la polyline "traces de pas"
    var polyline = L.polyline(polylines, {weight: 0}).addTo(myMap);
    var decorator = L.polylineDecorator(polyline, {
        patterns: [
            /*{ offset: 0, repeat: 25, symbol: L.Symbol.dash({pixelSize: 10, pathOptions: {color: '#000', weight: 3, opacity: 0.2}}) },*/
            { offset: 20, endOffset: 20, repeat: '50px', symbol: L.Symbol.marker({rotate: true, markerOptions: {
                icon: L.icon({
                    iconUrl: '/images/map/footprints.png',
                    iconAnchor: [10, 16]
                }), zIndexOffset: -100
            }})}
        ]
    });
    setTimeout(function(){
        decorator.addTo(myMap).snakeIn();
    }, 2000);
});
function generatePostcardContent(data) {
    var descriptionSize = (data.description.length < 300 ? 'lg' : (data.description.length > 400 ? 'sm' : ''));
    var firstLine = 'Coût total : '+data.price+' €';
    var secondLine = data.alreadyFundedAmount+' € déjà financé';
    var thirdLine = '<a href="'+data.contribute_url+'" class="btn">Cliquez ici <br /> pour participer</a>';
    if (data.price == data.alreadyFundedAmount) {
        secondLine = 'Déjà financé !';
        thirdLine = '';
    }
    return ''+
    '<div class="flip-container" onclick="this.classList.toggle(\'flipped\')">'+
        '<div class="flipper">'+
            '<div class="postcard flip-front" style="background-image: url(\''+data.image+'\');" title="Cliquez sur la photo pour afficher plus d\'informations">'+
                '<b>Étape '+data.ordering+' : '+data.title+'</b>'+
            '</div>'+
            '<div class="postcard flip-back">'+
                '<div class="postcard-content">'+
                    '<div class="postcard-left-side '+descriptionSize+'">'+
                        '<b>Étape '+data.ordering+' : '+data.title+'</b><br />'+data.description+
                    '</div>'+
                    '<div class="postcard-right-side">'+
                        '<div class="postcard-stamp"><img src="/images/map/stamp.png" alt="Timbre" /></div>'+
                        '<div class="postcard-address">'+firstLine+'<hr />'+secondLine+'&nbsp;<hr />'+thirdLine+'</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>';
}