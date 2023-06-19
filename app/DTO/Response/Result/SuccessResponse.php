<?php

namespace App\DTO\Response\Result;

class SuccessResponse
{
    public int $code;
    public mixed $data;

    public function __construct(int $code, mixed $data)
    {
        $this->code = $code;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'data' => $this->data,
        ];
    }
}
