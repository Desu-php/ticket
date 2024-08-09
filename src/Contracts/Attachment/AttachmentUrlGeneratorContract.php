<?php

namespace Maxdev\Tickets\Contracts\Attachment;

use Maxdev\Tickets\Models\TicketAttachment;

interface AttachmentUrlGeneratorContract
{
	public function getUrl(TicketAttachment $attachment): string;
}