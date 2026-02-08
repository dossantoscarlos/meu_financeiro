<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PDOException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $throwable): void {
            //
        });
    }

    public function render($request, Throwable $exception) {
        $error = str_contains($exception->getMessage(), 'could not find driver'); 
        if ($exception instanceof PDOException && $error) { 
            return response()->view('errors.database_error_driver', ['message' => 'Error ao conectar ao servidor.'], 500); 
        } return parent::render($request, $exception); 
    }
}
