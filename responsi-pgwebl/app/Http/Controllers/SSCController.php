<?php

namespace App\Http\Controllers;

use App\Models\AcademicZoneModel;
use App\Models\GOSModel;
use App\Models\SSCModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SSCController extends Controller
{
    public function index()
    {
        $zones = (new AcademicZoneModel())->geojson_academic_zone();
        $gos   = (new GOSModel())->geojson_gos();
        $ssc   = (new SSCModel())->geojson_ssc();

        return view('map', compact('zones', 'gos', 'ssc'));
    }

    public function table()
    {
        $data = SSCModel::all();
        $title = 'Layanan Mahasiswa';
        return view('table', compact('data', 'title'));
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

        SSCModel::create([
            'name'        => $request->name,
            'description' => $request->description,
            'geom'        => $request->geom,
            'image'       => $filename,
        ]);

        return redirect()->route('map')->with('success', 'Layanan Mahasiswa berhasil disimpan!');
    }

    public function destroy($id)
    {
        $zone = SSCModel::findOrFail($id);

        if ($zone->image && Storage::exists('public/images/' . $zone->image)) {
            Storage::delete('public/images/' . $zone->image);
        }

        $zone->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $zone = SSCModel::findOrFail($id);
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
