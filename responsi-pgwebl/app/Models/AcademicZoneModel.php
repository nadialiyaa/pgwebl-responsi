<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AcademicZoneModel extends Model
{
    protected $table = 'academic_zones';
    protected $guarded = ['id'];
    public function geojson_academicZone()
    {
        $zones = $this
            ->select(DB::raw('academic_zones.id, ST_AsGeoJSON(geom) AS geom, academic_zones.name, academic_zones.description, academic_zones.image, academic_zones.created_at, academic_zones.updated_at'))
            //->leftJoin('users', 'academic_zones.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($zones as $z) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($z->geom),
                'properties' => [
                    'id' => $z->id,
                    'name' => $z->name,
                    'description' => $z->description,
                    'created_at' => $z->created_at,
                    'updated_at' => $z->updated_at,
                    'image' => $z->image,
                    //'user_created' => $z->user_created,
                ],
            ];
        }

        return $geojson;
    }
}
