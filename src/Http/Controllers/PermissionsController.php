<?php

namespace Optimus\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('guard_name', 'admin')->get();

        return response()->json([
            'data' => $permissions->pluck('name')
        ]);
    }
}
