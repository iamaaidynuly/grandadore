<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    ];

    /**
     * VerifyCsrfToken constructor.
     * @param Application $app
     * @param Encrypter $encrypter
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        $adminDir = config('admin.prefix');

        $this->except = [
            $adminDir . '/logout',
            $adminDir . '/file_browser/connector',
            'webhooks/*',
        ];

        parent::__construct($app, $encrypter);
    }
}
