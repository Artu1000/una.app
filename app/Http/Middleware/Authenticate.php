<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Sentinel;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if the user is not logged
        if (!Sentinel::check()) {
            // if the request is not ajax
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                // we store the requested url into the session
                \Session::set('previous_url', $request->url());
                // we redirect toward the login form
                return redirect(route('login'));
            }
        }
        return $next($request);
    }
}
