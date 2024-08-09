<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Contracts;

interface HdeConfigServiceContract
{
	public function getProjectFieldId(): int;

	public function getProjectFieldValue(): string;

	public function getProductFieldId(): int;

	public function getProductFieldValue(): string;

	public function toCustomFields(): array;
}