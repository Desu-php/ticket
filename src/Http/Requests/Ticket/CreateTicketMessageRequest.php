<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Maxdev\Tickets\Traits\AttachmentRuleTrait;

class CreateTicketMessageRequest extends FormRequest
{
	use AttachmentRuleTrait;

	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'message' => ['required', 'string'],
			...$this->attachmentsRules()
		];
	}
}