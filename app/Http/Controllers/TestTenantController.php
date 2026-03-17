<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class TestTenantController extends Controller
{
    public function index()
    {
        // Simuler un utilisateur connecté avec un tenant_id (à adapter selon ton app)
        $tenantId = auth()->user()->tenant_id ?? 1; // Valeur par défaut : 1

        // Récupérer les infos du tenant depuis la base centrale
        $tenant = Tenant::on('mysql_main')->find($tenantId);
            
        if (!$tenant) {
            return response()->json(['error' => 'Aucun tenant trouvé'], 404);
        }

        // Config dynamique de la connexion tenant
        Config::set('database.connections.tenant', [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => $tenant->database_name,
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);

        // (Re)connexion
        DB::purge('tenant');
        DB::reconnect('tenant');

        try {
            // Lire une table dans la base du tenant
            $data = DB::connection('tenant')->table('users')->get();

            return response()->json([
                'status' => 'Connexion réussie à la base tenant',
                'users' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Connexion échouée à la base tenant',
                'message' => $e->getMessage()
            ], 500);
        }
        
    }
}
