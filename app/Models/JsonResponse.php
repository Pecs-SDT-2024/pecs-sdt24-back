<?php

namespace App\Models;

abstract class JsonResponse
{
    public $status;
}

enum ResponseType: string {
    case Error = 'error';
    case Success = 'success';
}
