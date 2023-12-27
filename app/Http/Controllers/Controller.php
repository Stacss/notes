<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="Notes - тестовый проект",
 *      version="1.0.0",
 *      description="API управления заметками",
 *
 *      @OA\Contact(
 *          email="postnikov.sa@ya.ru",
 *          name="Stanislav"
 *      )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
