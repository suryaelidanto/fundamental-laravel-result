<?php

namespace App\DTO\Response\Result;

class ErrorResponse
{
    public int $code;
    public mixed $message;

    public function __construct(int $code, mixed $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}
