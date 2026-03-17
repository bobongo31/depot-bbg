<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class SecureCookies
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === 'XSRF-TOKEN') {
                $response->headers->setCookie(
                    new Cookie(
                        'XSRF-TOKEN',
                        $cookie->getValue(),
                        $cookie->getExpiresTime(),
                        '/',
                        null,
                        true,
                        true,
                        false,
                        Cookie::SAMESITE_LAX
                    )
                );
            }
        }

        return $response;
    }
}
