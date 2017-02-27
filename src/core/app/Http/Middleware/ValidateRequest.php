<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Illuminate\Routing\Router;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response as HttpResponse;

class ValidateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ((App::environment() == 'testing') && $request->hasHeader('Authorization')) {
            JWTAuth::setRequest($request);
        }

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['success' => 0, 'error' => trans('validation_messages.token_validation.user_not_exist')], HttpResponse::HTTP_UNAUTHORIZED);
            }

        } catch (TokenExpiredException $e) {
            return response()->json(['success' => 0, 'error' => trans('validation_messages.token_validation.expired')], HttpResponse::HTTP_UNAUTHORIZED);

        } catch (TokenInvalidException $e) {

            return response()->json(['success' => 0, 'error' => trans('validation_messages.token_validation.invalid')], HttpResponse::HTTP_UNAUTHORIZED);

        } catch (JWTException $e) {

            return response()->json(['success' => 0, 'error' => trans('validation_messages.token_validation.not_exist')], HttpResponse::HTTP_UNAUTHORIZED);

        }

        $request->attributes->add(['user' => $user]);

        return $next($request);
    }
}
