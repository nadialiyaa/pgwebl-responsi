<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class HealthFacilityModel extends Model
{
    protected $table = 'health_facilities';
    protected $guarded = ['id'];

    public function geojson_health()
    {
        $health = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) AS geom, name, description, image, created_at, updated_at'))
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($health as $h) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($h->geom),
                'properties' => [
                    'id' => $h->id,
                    'name' => $h->name,
                    'description' => $h->description,
                    'image' => $h->image,
                    'created_at' => $h->created_at,
                    'updated_at' => $h->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
