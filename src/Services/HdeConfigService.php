<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Services;

use Maxdev\Tickets\Contracts\HdeConfigServiceContract;

class HdeConfigService implements HdeConfigServiceContract
{
	public function getProjectFieldId(): int
	{
		return (int)config('max_tickets.hde.custom_fields.project_field.key');
	}

	public function getProjectFieldValue(): string
	{
		return config('max_tickets.hde.custom_fields.project_field.value');
	}

	public function getProductFieldId(): int
	{
		return (int)config('max_tickets.hde.custom_fields.product_field.key');
	}

	public function getProductFieldValue(): string
	{
		return config('max_tickets.hde.custom_fields.product_field.value');
	}

	public function toCustomFields(): array
	{
		return [
			$this->getProductFieldId() => $this->getProductFieldValue(),
			$this->getProjectFieldId() => $this->getProjectFieldValue()
		];
	}
}