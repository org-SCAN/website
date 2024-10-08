
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<body>
<div id='map'></div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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
            if (!data.lat || !data.lng) return;

            const marker = generateMarker(data, index);
            marker.addTo(map).bindPopup(`<b>${data.lat}, ${data.lng}</b>`);
            markers.push(marker);
            map.fitBounds(markers.map(marker => marker.getLatLng()), {padding: [50, 50]
            });
        });
    }

    function generateMarker(data, index) {
        return L.marker([data.lat, data.lng], {
            draggable: false
        })
            .on('click', (event) => markerClicked(event, index))
            .on('dragend', (event) => markerDragEnd(event, index));
    }


    /* --------------------------- Initialize Area --------------------------- */
    function initArea() {
        const initialArea = <?php echo json_encode($initialArea); ?>;

        initialArea.forEach(polygonCoords => {
            polygonCoords.forEach(coords => {
                const validCoords = coords.filter(coord => coord.lat !== null && coord.long !== null);

                if (validCoords.length > 0) {
                    const sortedCoords = sortClockwise(validCoords);
                    const latLngs = sortedCoords.map(coord => [coord.lat, coord.long]);
                    const polygon = L.polygon(latLngs, { color: 'green' }).addTo(map);
                    map.fitBounds(polygon.getBounds(), { padding: [50, 50] });
                }
            });
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
    function findMeanPoint(coordinates){
        let sumLat = 0;
        let sumLong = 0;
        coordinates.forEach(coord => {
            sumLat += parseFloat(coord.lat);
            sumLong += parseFloat(coord.long);
        });
        return {lat: sumLat / coordinates.length, long: sumLong / coordinates.length};
    }

    function sortClockwise(coordinates){
        const meanPoint = findMeanPoint(coordinates);
        return coordinates.sort((a, b) => {
            const angleA = Math.atan2(a.lat - meanPoint.lat, a.long - meanPoint.long);
            const angleB = Math.atan2(b.lat - meanPoint.lat, b.long - meanPoint.long);
            return angleA - angleB;
        });
    }

</script>
</body>

</html>
