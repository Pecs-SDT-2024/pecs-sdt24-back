<?php

namespace App\Models;

class ErrorResponse extends JsonResponse {
    public $message;

    public function __construct($message)
    {
        $this->status = ResponseType::Error;
        $this->message = $message;
    }
}
