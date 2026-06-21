<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasUniversity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $uniCount = \App\Models\University::where('user_id', $user->id)->count();
            $favCount = \App\Models\University::where('user_id', $user->id)->where('is_favorite', true)->count();

            $routeName = $request->route()->getName();

            // Rutas que siempre están permitidas (login/logout/perfil y guardar/crear info de universidad)
            $alwaysAllowed = ['logout', 'profile.edit', 'profile.update', 'profile.destroy', 'universities.store'];

            if (in_array($routeName, $alwaysAllowed)) {
                return $next($request);
            }

            if ($uniCount === 0) {
                // Bloqueado hasta que cree una universidad
                if ($routeName !== 'universities.create') {
                    return redirect()->route('universities.create')
                        ->with('warning', '¡Bienvenido! Para poder utilizar UniTask, primero debes registrar tu universidad o institución principal.');
                }
            } else {
                // Tiene universidad(es), verificamos la favorita
                if ($favCount === 0) {
                    $favResolutionRoutes = ['universities.index', 'universities.favorite', 'universities.create', 'universities.destroy', 'universities.edit', 'universities.update'];
                    if (!in_array($routeName, $favResolutionRoutes)) {
                        return redirect()->route('universities.index')
                            ->with('warning', '¡Atención! El sistema requiere que establezcas una universidad favorita obligatoriamente para continuar.');
                    }
                }
            }
        }

        return $next($request);
    }
}
