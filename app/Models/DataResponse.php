<?php

namespace App\Models;

/**
 * Success reponse model with some data.
 */
class DataResponse extends SuccessResponse {
    /**
     * Response data
     * @var mixed
     */
    public mixed $data;

    /**
     * @param mixed $data
     */
    public function __construct(mixed $data)
    {
        parent::__construct();
        $this->data = $data;
    }
}
