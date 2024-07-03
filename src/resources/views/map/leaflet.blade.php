<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        .text-center {
            text-align: center;
        }

        #map {
            width: 100%;
            height: 400px;
        }
    </style>
    <link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
</head>

<body>
<div id='map'></div>

<script src='https://unpkg.com/leaflet@1.8.0/dist/leaflet.js' crossorigin=''></script>
<script>
    let map, markers = [];

    /* ----------------------------- Initialize Map ----------------------------- */
    function initMap() {
        map = L.map('map', {
            center: [0,0],
            zoom: 1
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        map.on('click', mapClicked);
        initMarkers();
        initArea();
    }
    initMap();

    /* --------------------------- Initialize Markers --------------------------- */
    function initMarkers() {
        const initialMarkers = <?php echo json_encode($initialMarkers); ?>;

        initialMarkers.forEach((data, index) => {
            const marker = generateMarker(data, index);
            marker.addTo(map).bindPopup(`<b>${data.lat}, ${data.lng}</b>`);
            markers.push(marker);
        });
    }

    function generateMarker(data, index) {
        return L.marker([data.lat, data.lng], {
            draggable: true
        })
            .on('click', (event) => markerClicked(event, index))
            .on('dragend', (event) => markerDragEnd(event, index));
    }

    /* --------------------------- Initialize Area --------------------------- */
    function initArea() {
        const initialArea = <?php echo json_encode($initialArea); ?>;

        initialArea.forEach(polygonCoords => {
            polygonCoords.forEach(coords => {
                coords = sortByDistance(coords);
                const latLngs = coords.map(coord => [coord.lat, coord.long]);
                const polygon = L.polygon(latLngs, { color: 'green' }).addTo(map);
            })
        });
    }
    /* ------------------------- Handle Map Click Event ------------------------- */
    function mapClicked(event) {
        console.log(map);
        console.log(event.latlng.lat, event.latlng.lng);
    }

    /* ------------------------ Handle Marker Click Event ----------------------- */
    function markerClicked(event, index) {
        console.log(map);
        console.log(event.latlng.lat, event.latlng.lng);
    }

    /* ----------------------- Handle Marker DragEnd Event ---------------------- */
    function markerDragEnd(event, index) {
        console.log(map);
        console.log(event.target.getLatLng());
    }
    /* ----------------------- Sort list of coordinates ---------------------- */
    function deg2rad(deg) {
        return deg * (Math.PI / 180)
    }
    function distance(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2 - lat1);  // deg2rad below
        var dLon = deg2rad(lon2 - lon1);
        var a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2)
            ;
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c; // Distance in km
        return d;
    }
    function sortByDistance($coordinates){
        return $coordinates.sort((a, b) => distance(a.lat, a.long, 0, 0) - distance(b.lat, b.long, 0, 0));
    }

</script>
</body>

</html>
