<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UtilityFacilityModel extends Model
{
    protected $table = 'utility_facilities';
    protected $guarded = ['id'];

    public function geojson_utility()
    {
        $utility = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) AS geom, name, description, image, created_at, updated_at'))
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($utility as $u) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($u->geom),
                'properties' => [
                    'id'          => $u->id,
                    'name'        => $u->name,
                    'description' => $u->description,
                    'image'       => $u->image,
                    'created_at'  => $u->created_at,
                    'updated_at'  => $u->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
