<?php
/**
* namespace App\Http\Middleware;

* use Closure;
*use Illuminate\Http\Request;

* class DeveloperDebugMiddleware
* {
   * public function handle(Request $request, Closure $next)
   * {
   *     if ($request->ip() === env('DEVELOPER_IP')) {
 *           config(['app.debug' => true]);
  *      } else {
  *          config(['app.debug' => false]);
  *      }

  *      return $next($request);
 *   }
* }
*/