<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;


class CheckUserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if ($token) {
            $token = decodeToken($token);
            if (isset($token['id']) && isset($token['email'])) {
                $user = User::where('email', $token['email'])->where('id', $token['id'])->first();
                if ($user) {
                    $user = $user->only(['id']);
                    $request->merge(['userId' => $user['id']]);
                    return $next($request);
                }
            }
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
