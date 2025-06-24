<?php

namespace App\Http\Controllers;

use App\Models\UtilityFacilityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UtilityFacilityController extends Controller
{
    public function index()
    {
        $zones  = (new \App\Models\AcademicZoneModel)->geojson_zones();
        $gos    = (new \App\Models\GOSModel)->geojson_gos();
        $ssc    = (new \App\Models\SSCModel)->geojson_ssc();
        $health = (new \App\Models\HealthFacilityModel)->geojson_health();
        $tayo   = (new \App\Models\TayogamaRoutesModel)->geojson_tayo();
        $utility = (new UtilityFacilityModel())->geojson_utility();

        return view('map', compact('zones', 'gos', 'ssc', 'health', 'tayo', 'utility'));
    }

    public function table()
    {
        $utilityPoint = UtilityFacilityModel::all();
        return view('table', compact('utilityPoint'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'geom'        => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('images', 'public');
            $filename = basename($filename);
        }

        UtilityFacilityModel::create([
            'name'        => $request->name,
            'description' => $request->description,
            'geom'        => $request->geom,
            'image'       => $filename,
        ]);

        return redirect()->route('map')->with('success', 'Titik utilitas berhasil disimpan!');
    }

    public function destroy($id)
    {
        $zone = UtilityFacilityModel::findOrFail($id);

        if ($zone->image && Storage::exists('public/images/' . $zone->image)) {
            Storage::delete('public/images/' . $zone->image);
        }

        $zone->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $zone = UtilityFacilityModel::findOrFail($id);
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
