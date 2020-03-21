import '../css/access_map.css';
const $ = require('jquery');
require('leaflet');

$(function () {
    // Création du fond de la carte
    var myMap = L.map('mapid').setView([47.05, 2.3], 12);
    var background = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        minZoom: 1,
        maxZoom: 18,
    });
    background.addTo(myMap);

    var marker1 = L.marker(
        [47.081, 2.3985], 
        { 
            icon: L.icon({
                iconUrl: '/images/map/map_pin_1.png',
                iconSize: [48, 48],
                iconAnchor: [24, 48]
            }), 
            zIndexOffset: 100 
        }
    ).addTo(myMap);

    var marker2 = L.marker(
        [47.0755, 2.3838],
        {
            icon: L.icon({
                iconUrl: '/images/map/map_pin_2.png',
                iconSize: [48, 48],
                iconAnchor: [24, 48]
            }),
            zIndexOffset: 100
        }
    ).addTo(myMap);

    var marker3 = L.marker(
        [47.0344973, 2.1969155],
        {
            icon: L.icon({
                iconUrl: '/images/map/map_pin_3.png',
                iconSize: [48, 48],
                iconAnchor: [24, 48]
            }),
            zIndexOffset: 100
        }
    ).addTo(myMap);

    if (!ceremonyOnly) {
        var marker4 = L.marker(
            [47.027581, 2.2194041],
            {
                icon: L.icon({
                    iconUrl: '/images/map/map_pin_4.png',
                    iconSize: [48, 48],
                    iconAnchor: [24, 48]
                }),
                zIndexOffset: 100
            }
        ).addTo(myMap);
    }

    marker1.bindPopup(generatePopup(
        'Mairie de Bourges', 
        '/images/map/place_1.png',
        '11 Rue Jacques Rimbault \n 18000 Bourges',
        'Rendez-vous à 14h30',
        'https://www.google.fr/maps/dir//Mairie+de+Bourges,+Rue+Jacques+Rimbault,+Bourges/@47.0800435,2.364041,13z/data=!3m1!4b1!4m9!4m8!1m0!1m5!1m1!1s0x47fa95d82897da95:0x3784279752819f70!2m2!1d2.3990603!2d47.0800488!3e0'
    ));
    marker2.bindPopup(generatePopup(
        'Eglise St-Henri', 
        '/images/map/place_2.png',
        '10 Avenue Marcel Haegelen \n 18000 Bourges',
        'Rendez-vous à 15h30',
        'https://www.google.fr/maps/dir//Paroisse+Saint-Henri,+10+Avenue+Marcel+Haegelen,+18000+Bourges/@47.0754826,2.3485957,13z/data=!4m9!4m8!1m0!1m5!1m1!1s0x47fa95d2896b89a5:0xa52de493658cdb0b!2m2!1d2.383615!2d47.0754879!3e0'
    ));
    marker3.bindPopup(generatePopup(
        'Salle des fêtes de Galifard', 
        '/images/map/place_3.png',
        'Coord. GPS : 47.034301, 2.197201 \n Via la route D27 \n 18400 Villeneuve-sur-Cher',
        '',
        'https://www.google.com/maps/dir//Villeneuve-sur-Cher,+18400/@47.0352519,2.1986276,17z/data=!4m9!4m8!1m0!1m5!1m1!1s0x47faeeca50e0145f:0x9e14b9984c96ae84!2m2!1d2.1969953!2d47.0344293!3e0'
    ));
    if (!ceremonyOnly) {
        marker4.bindPopup(generatePopup(
            'Camping La Noue de l’Anse  ',
            '/images/map/place_4.png',
            'Coord. GPS : 47.027581, 2.2194041 \n Via la route D16 \n 18400 Villeneuve-sur-Cher',
            '',
            "https://www.google.com/maps/dir//La+Noue+de+l'Anse,+18400+Villeneuve-sur-Cher"
        ));
    }

    function generatePopup(title, icon, address, rendezvous, link)
    {
        return L.popup({
            'offset': L.point(0, -48),
            'maxWidth': title.length*11+45,
            'minWidth': title.length*11+45
        }).setContent(' \
            <p> \
                <img src="' + icon + '" alt="icon" /> \
                <div class="h4">' + title + '</div> \
            </p> \
            <p>' + address.replace(/\n/g, '<br/>') + '</p> \
            ' + (rendezvous ? '<p>' + rendezvous.replace(/\n/g, '<br/>') + '</p>' : '') + ' \
            <p><a href="' + link + '" target="_blank">Calculer un itinéraire</a></p> \
        ');
    }
});
