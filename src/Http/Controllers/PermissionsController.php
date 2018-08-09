<?php

namespace Optimus\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();

        return response()->json([
            'data' => $permissions->pluck('name')
        ]);
    }
}
