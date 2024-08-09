<?php

namespace Maxdev\Tickets\Dto\TicketMessage;

use Maxdev\Tickets\Dto\PaginationDto;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class TicketMessagesResponseDto extends Data
{
	public function __construct(
		#[DataCollectionOf(TicketMessageResponseDto::class)]
		public DataCollection $data,
		public PaginationDto $pagination
	)
	{
	}
}