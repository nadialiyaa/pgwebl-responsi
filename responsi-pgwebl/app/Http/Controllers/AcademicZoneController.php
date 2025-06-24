<?php

namespace App\Http\Controllers;

use App\Models\AcademicZoneModel;
use App\Models\GOSModel; // ← Tambahkan ini
use App\Models\HealthFacilityModel;
use App\Models\SSCModel;
use App\Models\TayogamaRoutesModel;
use App\Models\UtilityFacilityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AcademicZoneController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        $academicZoneModel = new AcademicZoneModel();
        $zones = $academicZoneModel->geojson_academicZone();

        $gosModel = new GOSModel();
        $gos = $gosModel->geojson_gos(); // ← Ambil data Ruang Terbuka Hijau

        $sscModel = new SSCModel();
        $ssc = $sscModel->geojson_ssc();

        $healthModel = new HealthFacilityModel();
        $health = $healthModel->geojson_health();

        $tayogamaRoutes = new TayogamaRoutesModel();
        $tayo = $tayogamaRoutes->geojson_tayo();

        $utilityPoint = new UtilityFacilityModel();
        $utility = $utilityPoint->geojson_utility();

        return view('map', compact('zones', 'gos', 'ssc', 'health', 'tayo', 'utility'));
    }
    public function create()
    {
        //
    }

    public function table()
    {
        $academicZones = AcademicZoneModel::all();
        return view('table', compact('academicZones'));
    }
    public function store(Request $request)
    {
        // Validasi data dari form
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'geom'        => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        // Inisialisasi nama file gambar jika ada
        $filename = null;

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('images', 'public');
            $filename = basename($filename); // simpan hanya nama filenya
        }

        // Simpan ke database
        AcademicZoneModel::create([
            'name'        => $request->name,
            'description' => $request->description,
            'geom' => DB::raw("ST_GeomFromText('{$request->input('geom')}', 4326)"),
            'image'       => $filename,
        ]);

        return redirect()->route('map')->with('success', 'Zona Akademik berhasil disimpan!');
    }

    public function destroy($id)
    {
        $zone = AcademicZoneModel::findOrFail($id);

        if ($zone->image && Storage::exists('public/images/' . $zone->image)) {
            Storage::delete('public/images/' . $zone->image);
        }

        $zone->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $zone = AcademicZoneModel::findOrFail($id);
        $zone->name = $request->name;
        $zone->description = $request->description;

        if ($request->has('geometry')) {
            $zone->geom = DB::raw("ST_GeomFromGeoJSON('" . $request->geometry . "')");
        }

        if ($request->hasFile('image')) {
            if ($zone->image && Storage::exists('public/images/' . $zone->image)) {
                Storage::delete('public/images/' . $zone->image);
            }
            $zone->image = $request->file('image')->store('images', 'public');
        }

        $zone->save();

        return response()->json(['success' => true]);
    }
}
