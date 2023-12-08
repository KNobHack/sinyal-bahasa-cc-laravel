<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Response;

class DefaultAcceptForAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $preferred = $request->prefers(['application/json']);

        if (($preferred) === null) {
            return response('Supported Format : application/json', 406);
        }

        $request->headers->set('Accept', $preferred);

        return $next($request->duplicate());
    }
}
