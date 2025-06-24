<?php

namespace App\Http\Controllers;

use App\Models\GOSModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GOSController extends Controller
{
    public function index()
    {
        $gosModel = new GOSModel();
        $gos = $gosModel->geojson_gos();

        return view('map', compact('gos'));
    }

    public function table()
    {
        $greenOpenSpaces = GOSModel::all();
        return view('table', compact('greenOpenSpaces'));
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

        GOSModel::create([
            'name'        => $request->name,
            'description' => $request->description,
            'geom'        => $request->geom,
            'image'       => $filename,
        ]);

        return redirect()->route('map')->with('success', 'Ruang Terbuka Hijau berhasil disimpan!');
    }
    public function destroy($id)
    {
        $zone = GOSModel::findOrFail($id);

        if ($zone->image && Storage::exists('public/images/' . $zone->image)) {
            Storage::delete('public/images/' . $zone->image);
        }

        $zone->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $zone = GOSModel::findOrFail($id);
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
