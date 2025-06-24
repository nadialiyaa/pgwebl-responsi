<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GOSModel extends Model
{
    protected $table = 'green_open_spaces';
    protected $guarded = ['id'];

    public function geojson_gos()
    {
        $gos = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) AS geom, name, description, image, created_at, updated_at'))
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($gos as $g) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($g->geom),
                'properties' => [
                    'id' => $g->id,
                    'name' => $g->name,
                    'description' => $g->description,
                    'image' => $g->image,
                    'created_at' => $g->created_at,
                    'updated_at' => $g->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
