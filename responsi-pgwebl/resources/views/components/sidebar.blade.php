<head>
    <style>
        .legend-box {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 6px;
            border: 1px solid #999;
        }

        .legend-line {
            display: inline-block;
            width: 20px;
            height: 3px;
            margin-right: 6px;
            background-color: black;
            vertical-align: middle;
        }

        .legend-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 6px;
            border-radius: 50%;
            background-color: red;
            border: 1px solid #999;
            vertical-align: middle;
        }

        .btn-digitize.active {
            background-color: #cce5ff !important;
            border-color: #66b0ff !important;
        }
    </style>
</head>

<div class="bg-light p-3 border-end" style="width: 300px; overflow-y: auto;">
    @auth
        <h5>Fitur Area</h5>
        <div class="d-grid gap-2 mb-4" style="grid-template-columns: 1fr 1fr;">
            <button onclick="setActiveDigitize(this); startAcademicDigitize()"
                class="btn btn-light border text-center btn-digitize" id="btnAcademic">
                <i class="fa fa-building"></i><br>Zona Akademik
            </button>
            <button onclick="setActiveDigitize(this); startGOSDigitize()"
                class="btn btn-light border text-center btn-digitize" id="btnGOS">
                <i class="fa fa-tree"></i><br>Ruang Terbuka Hijau
            </button>
            <button onclick="setActiveDigitize(this); startSSCDigitize()"
                class="btn btn-light border text-center btn-digitize" id="btnSSC">
                <i class="fa fa-user-graduate"></i><br>Layanan Mahasiswa
            </button>
            <button onclick="setActiveDigitize(this); startHealthDigitize()"
                class="btn btn-light border text-center btn-digitize" id="btnHealth">
                <i class="fa fa-hospital"></i><br>Fasilitas Kesehatan
            </button>
        </div>

        <div class="d-flex gap-5 mb-3">
            <h5 class="mb-0">Fitur Garis</h5>
            <h5 class="mb-0">Fitur Titik</h5>
        </div>
        <div class="d-grid gap-2 mb-4" style="grid-template-columns: 1fr 1fr;">
            <button onclick="setActiveDigitize(this); startTayogamaDigitize()"
                class="btn btn-light border text-center btn-digitize" id="btnTayo">
                <i class="fa fa-road"></i><br>Jalur Tayogama
            </button>
            <button onclick="setActiveDigitize(this); startUtilityDigitize()"
                class="btn btn-light border text-center btn-digitize" id="btnUtility">
                <i class="fa fa-bolt"></i><br>Utilitas
            </button>
        </div>
    @endauth

    <hr>
    <h6 class="text-muted mt-3 ms-2">Legenda</h6>
    <ul class="list-unstyled ms-3 me-3">
        <li><span class="legend-box" style="background-color: #82CAFF;"></span> Zona Akademik</li>
        <li><span class="legend-box" style="background-color: #91C788;"></span> Ruang Terbuka Hijau</li>
        <li><span class="legend-box" style="background-color: yellow;"></span> Layanan Mahasiswa</li>
        <li><span class="legend-box" style="background-color: #30D5C8;"></span> Fasilitas Kesehatan</li>
        <li><span class="legend-line" style="background-color: red;"></span> Jalur Tayogama</li>
        <li>
            <img src="/icon/marker-cute.png" width="20" style="vertical-align: middle; margin-right: 6px;">
            Fasilitas Utilitas
        </li>
    </ul>

    <hr>
    <h6 class="text-muted mt-3 ms-2">Tampilkan Layer</h6>
    <div class="form-check ms-3">
        <input class="form-check-input layer-toggle" type="checkbox" id="toggleAcademic" checked>
        <label class="form-check-label" for="toggleAcademic">Zona Akademik</label>
    </div>
    <div class="form-check ms-3">
        <input class="form-check-input layer-toggle" type="checkbox" id="toggleGOS" checked>
        <label class="form-check-label" for="toggleGOS">RTH</label>
    </div>
    <div class="form-check ms-3">
        <input class="form-check-input layer-toggle" type="checkbox" id="toggleSSC" checked>
        <label class="form-check-label" for="toggleSSC">Layanan Mahasiswa</label>
    </div>
    <div class="form-check ms-3">
        <input class="form-check-input layer-toggle" type="checkbox" id="toggleHealth" checked>
        <label class="form-check-label" for="toggleHealth">Faskes</label>
    </div>
    <div class="form-check ms-3">
        <input class="form-check-input layer-toggle" type="checkbox" id="toggleTayo" checked>
        <label class="form-check-label" for="toggleTayo">Tayogama</label>
    </div>
    <div class="form-check ms-3 mb-4">
        <input class="form-check-input layer-toggle" type="checkbox" id="toggleUtility" checked>
        <label class="form-check-label" for="toggleUtility">Utilitas</label>
    </div>
</div>
