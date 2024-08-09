<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Maxdev\Tickets\Dto\TicketService\TicketCreateDto;
use Maxdev\Tickets\Traits\AttachmentRuleTrait;

class CreateTicketRequest extends FormRequest
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
			'subject' => ['required', 'string', 'max:255'],
			'message' => ['required', 'string'],
			...$this->attachmentsRules()
		];
	}


	public function getDto(): TicketCreateDto
	{
		return TicketCreateDto::from([
			...$this->validated(),
			'user_id' => auth()->id()
		]);
	}
}