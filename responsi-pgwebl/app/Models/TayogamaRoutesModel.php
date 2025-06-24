<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TayogamaRoutesModel extends Model
{
    protected $table = 'tayogama_routes';
    protected $guarded = ['id'];

    public function geojson_tayo()
    {
        $routes = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) AS geom, name, description, image, created_at, updated_at'))
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($routes as $r) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($r->geom),
                'properties' => [
                    'id'          => $r->id,
                    'name'        => $r->name,
                    'description' => $r->description,
                    'image'       => $r->image,
                    'created_at'  => $r->created_at,
                    'updated_at'  => $r->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
