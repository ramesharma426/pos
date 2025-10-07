<?php

namespace App\Http\Middleware;

use App\Traits\Messages;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    use Messages;
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (Auth::check()) {
            if (Gate::allows('authorized', $role)) {
                return $next($request);
            } else {
                if ($request->wantsJson()) {
                    return response()->json($this->getErrorMessage('Access Restricted'), Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    return redirect()->back()->withErrors(['msg' => 'Access Restricted']);
                }
            }
        } else {
            return redirect()->back();
        }
    }
}
