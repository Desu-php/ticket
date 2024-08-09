<?php

namespace Maxdev\Tickets\Dto;

use Spatie\LaravelData\Data;

class PaginationDto extends Data
{
	public function __construct(
		public int $total,
		public int $per_page,
		public int $current_page,
		public int $total_pages
	)
	{
	}
}