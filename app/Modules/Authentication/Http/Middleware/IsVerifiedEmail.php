<?php

namespace App\Modules\Authentication\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Extends\JsonResourceResponse;

class IsVerifiedEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->hasVerifiedEmail()) {
            return (new JsonResourceResponse(
                [],
                401,
                'Email is not verified, please verify your email'
            ))->response();
        }
        return $next($request);
    }
}
