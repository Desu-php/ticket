<?php

namespace Maxdev\Tickets\Http\Responses;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SuccessResponse extends Response
{
    public function __construct($status = ResponseAlias::HTTP_OK, $message = null, array $headers = [], array $additional = [])
    {
        $content = array_merge(['success' => true], $additional);

        if ($message) {
            $content += is_array($message) ? $message : ['message' => $message];
        }

        parent::__construct($content, $status, $headers);
    }
}
