<?php

namespace App\Models;

/**
 * Empty response with a success state.
 */
class SuccessResponse extends JsonResponse {
    public function __construct()
    {
        $this->status = ResponseType::Success;
    }
}
