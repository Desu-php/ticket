<?php

namespace Maxdev\Tickets\Traits;

trait AttachmentRuleTrait
{
	public function attachmentsRules(): array
	{
		return [
			'attachments'   => ['nullable', 'array'],
			'attachments.*' => ['required', 'file', ...$this->fileRules()],
		];
	}

	private function fileRules(): array
	{
		return ['max:' . config('max_tickets.attachment.max_size'), 'mimes:' . (implode(',', config('max_tickets.attachment.allowed_mimes')))];
	}
}