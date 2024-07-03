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
        initPolygon();
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

    /* --------------------------- Initialize Polygon --------------------------- */
    function initPolygon() {
        const initialArea = <?php echo json_encode($initialArea); ?>;

        initialArea.forEach(polygonCoords => {
            polygonCoords.forEach(coords => {
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
</script>
</body>

</html>
