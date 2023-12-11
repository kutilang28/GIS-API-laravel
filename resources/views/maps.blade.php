<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Location Example</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        #map { height: 725px; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Map Sidebar -->
            <div class="col-md-6">
                <div id="map"></div>
            </div>
            
            <!-- Content Area -->
            <div class="col-md-6">
                <div class="container">
                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('search') }}" method="GET" id="search-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="searchInput" class="form-label">Search Location:</label>
                                    <input type="text" class="form-control" id="searchInput" name="q" placeholder="Enter location">
                                </div>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>

                    <!-- Location Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive table-hover table-striped-columns" id="location-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Tipe Tempat</th>
                                        <th>Alamat</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($maps !== null)
                                        @foreach ($maps as $item)
                                        <tr onclick="showLocationOnMap({{ $item->lat }}, {{ $item->lon }}, '{{ $item->name }}')">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->display_name }}</td>
                                            <td>{{ $item->lat }}</td>
                                            <td>{{ $item->lon }}</td>
                                            <td>
                                                <form action="{{ route('insert') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="name" value="{{ $item->name }}">
                                                    <input type="hidden" name="lat" value="{{ $item->lat }}">
                                                    <input type="hidden" name="long" value="{{ $item->lon }}">
                                                    <button type="submit" class="btn btn-primary">Insert</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No results found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Database Location Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive table-hover table-striped-columns" id="database-location-table">
                                <thead>
                                    <tr>
                                        <th>Saved Locations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($databaseLocations as $location)
                                        <tr onclick="showLocationOnMap({{ $location->lat }}, {{ $location->long }}, '{{ $location->name }}')">
                                            <td>{{ $location->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script> 
        var map = L.map('map').setView([0, 0], 2);
        var routingControl;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Access the current location using the Geolocation API
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                map.setView([lat, lon], 13);

                L.marker([lat, lon]).addTo(map)
                    .bindPopup('Your current location')
                    .openPopup();
            }, function(error) {
                console.error('Error getting current location:', error.message);
            });
        } else {
            console.error('Geolocation is not supported by your browser.');
        }

        function showLocationOnMap(lat, lon, name) {
            // Set the map view to the selected location
            map.setView([lat, lon], 17);
            // Add a marker for the selected location
            L.marker([lat, lon]).addTo(map)
                .bindPopup(name)
                .openPopup();

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var currentLat = position.coords.latitude;
                    var currentLon = position.coords.longitude;

                    // Remove the existing routing control if it exists
                    if (routingControl) { 
                        map.removeControl(routingControl);
                    }

                    // Create a new routing control
                    routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(currentLat, currentLon),
                            L.latLng(lat, lon)
                        ],
                        position: '' // Change this to 'topright', 'bottomleft', 'bottomright', etc.
                    }).addTo(map);
                });
            }
        }
    </script>
</body>
</html>
