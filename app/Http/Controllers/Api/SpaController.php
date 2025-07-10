<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spa;
use App\Models\SpaService;
use Illuminate\Http\Request;

class SpaController extends Controller
{
    /**
     * Get services for a specific spa.
     */
    public function getServices($spaId)
    {
        $services = SpaService::where('spa_id', $spaId)
                    ->where('is_active', true)
                    ->get();
                    
        return response()->json($services);
    }
}
