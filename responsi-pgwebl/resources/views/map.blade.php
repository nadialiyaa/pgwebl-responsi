@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-minimap@3.6.1/dist/Control.MiniMap.min.css" />

    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
    <div class="d-flex" style="height: calc(100vh - 56px); overflow: hidden;">
        @include('components.sidebar') {{-- Sidebar hanya muncul di halaman ini --}}
        <div class="flex-grow-1 position-relative">

            <div id="editForm" class="card p-3 m-3 shadow d-none"
                style="z-index: 999; position: absolute; top: 60px; right: 20px; width: 300px;">
                <h5>Edit Zona</h5>
                <form id="formEditZone">
                    <input type="hidden" id="editId">
                    <input type="hidden" id="editPrefix">
                    <div class="mb-2">
                        <label>Nama</label>
                        <input type="text" id="editName" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <textarea id="editDescription" class="form-control"></textarea>
                    </div>
                    <div class="mb-2">
                        <label>Gambar Baru (opsional)</label>
                        <input type="file" id="editImage" class="form-control">
                    </div>
                    <input type="hidden" id="editGeoJson">
                    <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                    <button type="button" class="btn btn-secondary mt-2" onclick="cancelEdit()">Batal</button>
                </form>
            </div>


            <div id="map" style="height: 100%; width: 100%;"></div>

            <!-- Academic Zone Modal -->
            <div class="modal fade" id="createAcademicZoneModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action=" {{ route('academicZone.store') }}" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Zona Akademik</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Geometry</label>
                                <textarea name="geom" id="geom_academic_zone" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('preview-image-academic-zone').src = window.URL.createObjectURL(this.files[0])">
                                <img id="preview-image-academic-zone" class="img-thumbnail mt-2" width="400">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- GOS Modal -->
            <div class="modal fade" id="createGOSModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action=" {{ route('gos.store') }}" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Ruang Terbuka Hijau</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Geometry</label>
                                <textarea name="geom" id="geom_gos" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('preview-image-gos').src = window.URL.createObjectURL(this.files[0])">
                                <img id="preview-image-gos" class="img-thumbnail mt-2" width="400">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SSC Modal -->
            <div class="modal fade" id="createSSCModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action=" {{ route('ssc.store') }}" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Layanan Mahasiswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Geometry</label>
                                <textarea name="geom" id="geom_ssc" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('preview-image-ssc').src = window.URL.createObjectURL(this.files[0])">
                                <img id="preview-image-ssc" class="img-thumbnail mt-2" width="400">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Health Modal -->
            <div class="modal fade" id="createHealthFacilityModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action=" {{ route('health.store') }}" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Fasilitas Kesehatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Geometry</label>
                                <textarea name="geom" id="geom_health" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('preview-image-health').src = window.URL.createObjectURL(this.files[0])">
                                <img id="preview-image-health" class="img-thumbnail mt-2" width="400">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tayogama Modal -->
            <div class="modal fade" id="createTayoModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action=" {{ route('tayo.store') }}" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Rute Jalur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Geometry</label>
                                <textarea name="geom" id="geom_tayo" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('preview-image-tayo').src = window.URL.createObjectURL(this.files[0])">
                                <img id="preview-image-tayo" class="img-thumbnail mt-2" width="400">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Utilitas Modal -->
            <div class="modal fade" id="createUtilityModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action=" {{ route('utility.store') }}" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Titik Utilitas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Geometry</label>
                                <textarea name="geom" id="geom_utility" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('preview-image-utility').src = window.URL.createObjectURL(this.files[0])">
                                <img id="preview-image-utility" class="img-thumbnail mt-2" width="400">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet-minimap@3.6.1/dist/Control.MiniMap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        const map = L.map('map').setView([-7.7710, 110.3775], 15);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '© Esri',
            maxZoom: 19
        }).addTo(map);

        const miniMapLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            minZoom: 0,
            maxZoom: 13
        });

        new L.Control.MiniMap(miniMapLayer, {
            toggleDisplay: true,
            minimized: false,
            position: 'bottomright'
        }).addTo(map);

        //Digitize Function
        let drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        let drawControl; // deklarasi global

        window.startAcademicDigitize = function() {
            // Hilangkan draw control sebelumnya jika ada
            if (drawControl) {
                map.removeControl(drawControl);
            }

            // Hapus layer sebelumnya (opsional)
            drawnItems.clearLayers();

            // Buat draw control baru
            drawControl = new L.Control.Draw({
                draw: {
                    position: 'topleft',
                    polyline: false,
                    polygon: true,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: false
            });

            map.addControl(drawControl);

            // Pasang event listener sekali agar tidak dobel-dobel
            map.once('draw:created', function(e) {
                const type = e.layerType;
                const layer = e.layer;
                const drawnJSONObject = layer.toGeoJSON();
                const objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

                if (type === 'polygon') {
                    document.getElementById('geom_academic_zone').value = objectGeometry;

                    // Modal Bootstrap 5
                    const modal = new bootstrap.Modal(document.getElementById('createAcademicZoneModal'));
                    modal.show();

                    drawnItems.addLayer(layer); // tampilkan hasil gambar di peta
                }
            });
        }

        window.startGOSDigitize = function() {
            // Hilangkan draw control sebelumnya jika ada
            if (drawControl) {
                map.removeControl(drawControl);
            }

            // Hapus layer sebelumnya (opsional)
            drawnItems.clearLayers();

            // Buat draw control baru
            drawControl = new L.Control.Draw({
                draw: {
                    position: 'topleft',
                    polyline: false,
                    polygon: true,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: false
            });

            map.addControl(drawControl);

            // Pasang event listener sekali agar tidak dobel-dobel
            map.once('draw:created', function(e) {
                const type = e.layerType;
                const layer = e.layer;
                const drawnJSONObject = layer.toGeoJSON();
                const objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

                if (type === 'polygon') {
                    document.getElementById('geom_gos').value = objectGeometry;

                    // Modal Bootstrap 5
                    const modal = new bootstrap.Modal(document.getElementById('createGOSModal'));
                    modal.show();

                    drawnItems.addLayer(layer); // tampilkan hasil gambar di peta
                }
            });
        }

        window.startSSCDigitize = function() {
            // Hilangkan draw control sebelumnya jika ada
            if (drawControl) {
                map.removeControl(drawControl);
            }

            // Hapus layer sebelumnya (opsional)
            drawnItems.clearLayers();

            // Buat draw control baru
            drawControl = new L.Control.Draw({
                draw: {
                    position: 'topleft',
                    polyline: false,
                    polygon: true,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: false
            });

            map.addControl(drawControl);

            // Pasang event listener sekali agar tidak dobel-dobel
            map.once('draw:created', function(e) {
                const type = e.layerType;
                const layer = e.layer;
                const drawnJSONObject = layer.toGeoJSON();
                const objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

                if (type === 'polygon') {
                    document.getElementById('geom_ssc').value = objectGeometry;

                    // Modal Bootstrap 5
                    const modal = new bootstrap.Modal(document.getElementById('createSSCModal'));
                    modal.show();

                    drawnItems.addLayer(layer); // tampilkan hasil gambar di peta
                }
            });
        }

        function startHealthDigitize() {
            // Hilangkan draw control sebelumnya jika ada
            if (drawControl) {
                map.removeControl(drawControl);
            }

            // Hapus layer sebelumnya (opsional)
            drawnItems.clearLayers();

            // Buat draw control baru
            drawControl = new L.Control.Draw({
                draw: {
                    position: 'topleft',
                    polyline: false,
                    polygon: true,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: false
            });

            map.addControl(drawControl);

            // Pasang event listener sekali agar tidak dobel-dobel
            map.once('draw:created', function(e) {
                const type = e.layerType;
                const layer = e.layer;
                const drawnJSONObject = layer.toGeoJSON();
                const objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

                if (type === 'polygon') {
                    document.getElementById('geom_health').value = objectGeometry;

                    // Modal Bootstrap 5
                    const modal = new bootstrap.Modal(document.getElementById('createHealthFacilityModal'));
                    modal.show();

                    drawnItems.addLayer(layer); // tampilkan hasil gambar di peta
                }
            });
        }

        function startTayogamaDigitize() {
            // Hilangkan draw control sebelumnya jika ada
            if (drawControl) {
                map.removeControl(drawControl);
            }

            // Hapus layer sebelumnya (opsional)
            drawnItems.clearLayers();

            // Buat draw control baru
            drawControl = new L.Control.Draw({
                draw: {
                    position: 'topleft',
                    polyline: true,
                    polygon: false,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: false
            });

            map.addControl(drawControl);

            // Pasang event listener sekali agar tidak dobel-dobel
            map.once('draw:created', function(e) {
                const type = e.layerType;
                const layer = e.layer;
                const drawnJSONObject = layer.toGeoJSON();
                const objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

                if (type === 'polyline') {
                    document.getElementById('geom_tayo').value = objectGeometry;

                    // Modal Bootstrap 5
                    const modal = new bootstrap.Modal(document.getElementById('createTayoModal'));
                    modal.show();

                    drawnItems.addLayer(layer); // tampilkan hasil gambar di peta
                }
            });
        }

        function startUtilityDigitize() {
            // Hilangkan draw control sebelumnya jika ada
            if (drawControl) {
                map.removeControl(drawControl);
            }

            // Hapus layer sebelumnya (opsional)
            drawnItems.clearLayers();

            // Buat draw control baru
            drawControl = new L.Control.Draw({
                draw: {
                    position: 'topleft',
                    polyline: false,
                    polygon: false,
                    rectangle: false,
                    circle: false,
                    marker: true,
                    circlemarker: false
                },
                edit: false
            });

            map.addControl(drawControl);

            // Pasang event listener sekali agar tidak dobel-dobel
            map.once('draw:created', function(e) {
                const type = e.layerType;
                const layer = e.layer;
                const drawnJSONObject = layer.toGeoJSON();
                const objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

                if (type === 'marker') {
                    document.getElementById('geom_utility').value = objectGeometry;

                    // Modal Bootstrap 5
                    const modal = new bootstrap.Modal(document.getElementById('createUtilityModal'));
                    modal.show();

                    drawnItems.addLayer(layer); // tampilkan hasil gambar di peta
                }
            });
        }

        //Zona Akademik
        const academicLayer = L.geoJSON(@json($zones['features']), {
            style: {
                color: '#82CAFF',
                weight: 2,
                fillOpacity: 0.4
            },
            onEachFeature: function(feature, layer) {
                bindPopupWithActions(layer, feature, 'academic-zone');
            }
        }).addTo(map);

        //Green Open Spaces
        const gosLayer = L.geoJSON(@json($gos['features']), {
            style: {
                color: '#91C788',
                weight: 2,
                fillOpacity: 0.4
            },
            onEachFeature: function(feature, layer) {
                bindPopupWithActions(layer, feature, 'gos');
            }
        }).addTo(map);


        //Student Service Center
        const sscLayer = L.geoJSON(@json($ssc['features']), {
            style: {
                color: '#FFFF00',
                weight: 2,
                fillOpacity: 0.4
            },
            onEachFeature: function(feature, layer) {
                bindPopupWithActions(layer, feature, 'ssc');
            }
        }).addTo(map);

        //Health Facility
        const healthLayer = L.geoJSON(@json($health['features']), {
            style: {
                color: '#40E0D0',
                weight: 2,
                fillOpacity: 0.4
            },
            onEachFeature: function(feature, layer) {
                bindPopupWithActions(layer, feature, 'health');
            }
        }).addTo(map);

        //Rute Tayogama
        const tayoLayer = L.geoJSON(@json($tayo['features']), {
            style: {
                color: '#FF0000',
                weight: 2,
                fillOpacity: 0.4
            },
            onEachFeature: function(feature, layer) {
                bindPopupWithActions(layer, feature, 'tayo');
            }
        }).addTo(map);

        //Titik Utilitas
        const utilityLayer = L.geoJSON(@json($utility['features']), {
            pointToLayer: function(feature, latlng) {
                return L.marker(latlng, {
                    icon: L.icon({
                        iconUrl: '/icon/marker-cute.png', // ← Ganti ini ke path ikon lucu kamu
                        iconSize: [32, 32], // ukuran marker
                        iconAnchor: [16, 32], // titik tumpu marker (biasanya bawah tengah)
                        popupAnchor: [0, -32] // posisi popup relatif terhadap ikon
                    })
                });
            },
            onEachFeature: function(feature, layer) {
                bindPopupWithActions(layer, feature, 'utility');
            }
        }).addTo(map);


        //Function Pop Up
        function bindPopupWithActions(layer, feature, routePrefix) {
            const props = feature.properties;
            const imageUrl = props.image ?
                `/storage/images/${props.image}` :
                'https://via.placeholder.com/150x100?text=No+Image';

            const popupContent = `
        <div style="max-width:250px;">
            <strong>Nama:</strong> ${props.name}<br>
            <strong>Deskripsi:</strong> ${props.description || '-'}<br>
            <strong>Dibuat:</strong> ${new Date(props.created_at).toLocaleString()}<br>
            <img src="${imageUrl}" width="100%" class="my-2 rounded shadow-sm"/>

            <div class="d-flex justify-content-between">
                <button class="btn btn-sm btn-warning me-1" onclick="editFeature(${props.id}, '${routePrefix}')">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteFeature(${props.id}, '${routePrefix}')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </div>
    `;
            layer.bindPopup(popupContent);
        }

        //Function Delete dan Edit
        function editFeature(id, prefix) {
            window.location.href = `/${prefix}/${id}/edit`;
        }

        function deleteFeature(id, prefix) {
            if (confirm("Yakin ingin menghapus data ini?")) {
                fetch(`/${prefix}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            alert("Data berhasil dihapus.");
                            location.reload();
                        } else {
                            alert("Gagal menghapus data.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan saat menghapus.");
                    });
            }
        }

        let editableLayer = null;
        let drawEditControl = null;

        function editFeature(id, prefix) {
            document.getElementById('editForm').classList.remove('d-none');
            document.getElementById('editId').value = id;
            document.getElementById('editPrefix').value = prefix;

            // cari feature yg sedang diklik
            let layer = null;
            map.eachLayer(function(l) {
                if (l.feature?.properties?.id === id) {
                    layer = l;
                }
            });

            if (!layer) {
                alert("Feature tidak ditemukan");
                return;
            }

            const props = layer.feature.properties;
            const geometry = layer.feature.geometry;

            document.getElementById('editName').value = props.name;
            document.getElementById('editDescription').value = props.description || '';
            document.getElementById('editGeoJson').value = JSON.stringify(geometry);

            if (editableLayer) map.removeLayer(editableLayer);
            editableLayer = L.geoJSON({
                type: 'Feature',
                geometry: geometry
            }).getLayers()[0];
            editableLayer.addTo(map);

            if (drawEditControl) map.removeControl(drawEditControl);
            drawEditControl = new L.Control.Draw({
                edit: {
                    featureGroup: L.featureGroup([editableLayer]),
                    edit: true,
                    remove: false
                },
                draw: false
            });
            map.addControl(drawEditControl);

            map.on('draw:edited', () => {
                const newGeometry = editableLayer.toGeoJSON().geometry;
                document.getElementById('editGeoJson').value = JSON.stringify(newGeometry);
            });
        }

        function cancelEdit() {
            document.getElementById('editForm').classList.add('d-none');
            if (editableLayer) {
                map.removeLayer(editableLayer);
                editableLayer = null;
            }
            if (drawEditControl) {
                map.removeControl(drawEditControl);
                drawEditControl = null;
            }
        }

        document.getElementById('formEditZone').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('editId').value;
            const prefix = document.getElementById('editPrefix').value;
            const name = document.getElementById('editName').value;
            const desc = document.getElementById('editDescription').value;
            const geoJson = document.getElementById('editGeoJson').value;
            const image = document.getElementById('editImage').files[0];

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', name);
            formData.append('description', desc);
            formData.append('geometry', geoJson);
            if (image) formData.append('image', image);

            fetch(`/${prefix}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    alert('Berhasil disimpan');
                    location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal menyimpan');
                });
        });

        //Untuk mempercantik toogle
        function setActiveDigitize(btn) {
            document.querySelectorAll('.btn-digitize').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        document.getElementById('toggleAcademic').addEventListener('change', function() {
            this.checked ? map.addLayer(academicLayer) : map.removeLayer(academicLayer);
        });
        document.getElementById('toggleGOS').addEventListener('change', function() {
            this.checked ? map.addLayer(gosLayer) : map.removeLayer(gosLayer);
        });
        document.getElementById('toggleSSC').addEventListener('change', function() {
            this.checked ? map.addLayer(sscLayer) : map.removeLayer(sscLayer);
        });
        document.getElementById('toggleHealth').addEventListener('change', function() {
            this.checked ? map.addLayer(healthLayer) : map.removeLayer(healthLayer);
        });
        document.getElementById('toggleTayo').addEventListener('change', function() {
            this.checked ? map.addLayer(tayoLayer) : map.removeLayer(tayoLayer);
        });
        document.getElementById('toggleUtility').addEventListener('change', function() {
            this.checked ? map.addLayer(utilityLayer) : map.removeLayer(utilityLayer);
        });
    </script>
    </script>
@endsection
