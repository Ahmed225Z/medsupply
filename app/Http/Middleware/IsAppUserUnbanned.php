<?php
namespace App\Http\Middleware;

use Closure;
use Laravel\Sanctum\PersonalAccessToken;

class IsAppUserUnbanned
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        
        if ($user && $user->banned == 1) {
            $token = $user->currentAccessToken();

            if ($token instanceof PersonalAccessToken) {
                $token->delete();
            } else {
                // This means it's a Transient Token, handle accordingly
                return response()->json([
                    'result' => false,
                    'status' => 'banned',
                    'message' => translate('This token cannot be invalidated')
                ], 400);
            }

            return response()->json([
                'result' => false,
                'status' => 'banned',
                'message' => translate('user is banned')
            ]);
        }

        return $next($request);
    }
}
