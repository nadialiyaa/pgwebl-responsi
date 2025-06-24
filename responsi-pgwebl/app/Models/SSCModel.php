<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SSCModel extends Model
{
    protected $table = 'student_service_centers';
    protected $guarded = ['id'];

    public function geojson_ssc()
    {
        $ssc = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) AS geom, name, description, image, created_at, updated_at'))
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($ssc as $s) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($s->geom),
                'properties' => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'description' => $s->description,
                    'image' => $s->image,
                    'created_at' => $s->created_at,
                    'updated_at' => $s->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
