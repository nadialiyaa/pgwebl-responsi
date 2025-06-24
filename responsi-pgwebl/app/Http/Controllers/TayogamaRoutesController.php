<?php

namespace App\Http\Controllers;

use App\Models\TayogamaRoutesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class TayogamaRoutesController extends Controller
{
    public function index()
    {
        $zones  = (new \App\Models\AcademicZoneModel)->geojson_zones();
        $gos    = (new \App\Models\GOSModel)->geojson_gos();
        $ssc    = (new \App\Models\SSCModel)->geojson_ssc();
        $health = (new \App\Models\HealthFacilityModel)->geojson_health();
        $tayo   = (new \App\Models\TayogamaRoutesModel)->geojson_tayo();

        return view('map', compact('zones', 'gos', 'ssc', 'health', 'tayo'));
    }

    public function table()
    {
        $tayogamaRoutes = TayogamaRoutesModel::all();
        return view('table', compact('tayogamaRoutes'));
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

        TayogamaRoutesModel::create([
            'name'        => $request->name,
            'description' => $request->description,
            'geom'        => $request->geom,
            'image'       => $filename,
        ]);

        return redirect()->route('map')->with('success', 'Jalur Tayogama berhasil disimpan!');
    }

    public function destroy($id)
    {
        $zone = TayogamaRoutesModel::findOrFail($id);

        if ($zone->image && Storage::exists('public/images/' . $zone->image)) {
            Storage::delete('public/images/' . $zone->image);
        }

        $zone->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $zone = TayogamaRoutesModel::findOrFail($id);
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
