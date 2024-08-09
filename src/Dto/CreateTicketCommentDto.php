<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Dto;

use Spatie\LaravelData\Data;

class CreateTicketCommentDto extends Data
{
    public function __construct(
        public string $text,
        public ?int $user_id = null,
        public array $files = []
    )
    {
    }
}
