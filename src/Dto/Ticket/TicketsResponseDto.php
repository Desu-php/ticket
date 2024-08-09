<?php

namespace Maxdev\Tickets\Dto\Ticket;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Maxdev\Tickets\Dto\PaginationDto;
use Spatie\LaravelData\DataCollection;

class TicketsResponseDto extends Data
{
	public function __construct(
		#[DataCollectionOf(TicketResponseDto::class)]
		public DataCollection $data,
		public PaginationDto $pagination,
	)
	{
	}
}