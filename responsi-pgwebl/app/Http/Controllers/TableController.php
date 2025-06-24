<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicZoneModel;
use App\Models\GOSModel;
use App\Models\SSCModel;
use App\Models\HealthFacilityModel;
use App\Models\UtilityFacilityModel;
use App\Models\TayogamaRoutesModel;

class TableController extends Controller
{
    public function index()
    {
        $academic = AcademicZoneModel::all();
        $gos = GOSModel::all();
        $ssc = SSCModel::all();
        $health = HealthFacilityModel::all();
        $utility = UtilityFacilityModel::all();
        $tayogama = TayogamaRoutesModel::all();

        return view('table', compact('academic', 'gos', 'ssc', 'health', 'utility', 'tayogama'));
    }
}
