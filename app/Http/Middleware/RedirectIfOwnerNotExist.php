<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Queries\Owner;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class RedirectIfOwnerNotExist
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ownerIdentifier = $request->header('X-OwnerIdentifier');

        if (empty($ownerIdentifier)) {
            return response()->json(ApiResponse::UNAUTHORIZED, 403);
        }
        $owner = Owner::where(['identifier' => $request->header('X-OwnerIdentifier')])->first();
        $request->request->add(['owner' => $owner]);
        return $next($request);
    }
}
