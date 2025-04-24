<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {


        // if( $request->is('api/*')) {
        //     return response()->json([
        //         'data' => [],
        //         'meta' => [
        //             'message' => 'Exception',
        //             'status' => 300,
        //             'errors' => $exception->getMessage()
        //         ]], 200);
        // }

        // if (!\Auth::check()) {
        //   return redirect()->route('/');
        // }

        // if ($exception instanceof ModelNotFoundException) {
        //     return response()->json(['error' => 'Data not found.'], 404);
        // }

        // if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
        //     return response()->json(['error' => 'User can\'t perform this action.'], 403);
        // }

        return parent::render($request, $exception);
    }
}
