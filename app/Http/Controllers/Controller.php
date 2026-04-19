<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function meta(string $title, ?string $description = null): array
    {
        return [
            'title' => $title,
            'description' => $description ?? mb_substr(strip_tags($title), 0, 160),
        ];
    }
}
