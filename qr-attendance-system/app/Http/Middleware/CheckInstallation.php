<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if system is installed
        $installed = File::exists(base_path('installed')) || 
                    (File::exists(base_path('.env')) && env('INSTALLED') === 'true');

        // If not installed and not accessing install routes, redirect to installation
        if (!$installed && !$request->is('install*')) {
            return redirect()->route('install.index');
        }

        // If installed and accessing install routes, redirect to home
        if ($installed && $request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
}