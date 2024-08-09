<?php
return [
	'driver'                      => 'hde',
	'hde'                         => [
		'service'                => \Maxdev\Tickets\Services\HdeService::class,
		'validator'              => [
			'new_message'          => \Maxdev\Tickets\Validators\HdeNewMessageWebhookValidator::class,
			'new_ticket'           => \Maxdev\Tickets\Validators\HdeNewTicketWebhookValidator::class,
			'change_status_ticket' => \Maxdev\Tickets\Validators\HdeChangeStatusTicketWebhookValidator::class,
			'change_owner_ticket'  => \Maxdev\Tickets\Validators\HdeChangeOwnerTicketWebhookValidator::class,
		],
		'username'               => env('HDE_USERNAME'),
		'password'               => env('HDE_PASSWORD'),
		'base_url'               => env('HDE_BASE_URL'),
		'custom_fields'          => [
			'product_field' => [
				'key'   => env('HDE_CUSTOM_FIELD_PRODUCT_KEY'),
				'value' => env('HDE_CUSTOM_FIELD_PRODUCT_VALUE'),
			],
			'project_field' => [
				'key'   => env('HDE_CUSTOM_FIELD_PROJECT_KEY'),
				'value' => env('HDE_CUSTOM_FIELD_PROJECT_VALUE')
			]
		],
		'block_duration'         => env('HDE_BLOCK_DURATION', 5),
		'safe_limit'             => env('HDE_SAFE_LIMIT', 10),
		'contains_clients_words' => ['клиент']
	],
	'storage_disk'                => 'public',
	'user'                        => '\\App\\Models\\User',
	'per_page'                    => 50,
	'admin_per_page'              => 50,
	'webhook_access_key'          => env('TICKET_WEBHOOK_ACCESS_KEY'),
	'failed_request_number_tries' => env('TICKET_FILED_REQUEST_NUMBER_TRIES', 5),
	'request_ban_minutes'         => env('TICKET_REQUEST_BAN_MINUTES', 20),
	'notifications'               => [
		'quarter_hourly' => [
			'minutes' => env('TICKET_NOTIFICATION_QUARTER_HOURLY_MINUTES', 15),
			'token'   => env('TICKET_NOTIFICATION_QUARTER_HOURLY_TOKEN'),
			'chat_id' => env('TICKET_NOTIFICATION_QUARTER_HOURLY_CHAT_ID')
		],
		'half_hourly'    => [
			'minutes' => env('TICKET_NOTIFICATION_HALF_HOURLY_MINUTES', 30),
			'token'   => env('TICKET_NOTIFICATION_HALF_HOURLY_TOKEN'),
			'chat_id' => env('TICKET_NOTIFICATION_HALF_HOURLY_CHAT_ID')
		],
		'hourly'         => [
			'minutes' => env('TICKET_NOTIFICATION_HOURLY_MINUTES', 60),
			'token'   => env('TICKET_NOTIFICATION_HOURLY_TOKEN'),
			'chat_id' => env('TICKET_NOTIFICATION_HOURLY_CHAT_ID')
		],
	],
	'attachment'                  => [
		// laravel file validation mimes
		'allowed_mimes'  => ['png', 'jpg', 'gif'],
		// max size in kb
		'max_size'       => 2048,
		// allowed image optimizer
		'image_optimize' => true
	],

	'support' => [
		'default_slug' => 'Support team'
	]
];