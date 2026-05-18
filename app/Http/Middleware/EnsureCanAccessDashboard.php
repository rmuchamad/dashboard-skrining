<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanAccessDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = str_replace([' ', '-'], '_', strtolower((string) $request->user()?->role));
        $isDefaultSuperAdminEmail = strtolower((string) $request->user()?->email) === 'admin@admin.com';
        if (in_array($role, ['admin', 'super_admin'], true) || $isDefaultSuperAdminEmail) {
            return $next($request);
        }

        return redirect()
            ->route('individu.index')
            ->withErrors(['akses' => 'Menu Dashboard analitik hanya untuk peran Admin atau Super Admin.']);
    }
}
