<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Enums;

enum TicketNotificationTypeEnum: string
{
	case QuarterHourlyAlert = 'QuarterHourlyAlert';
	case HalfHourlyAlert = 'HalfHourlyAlert';
	case HourlyAlert = 'HourlyAlert';

	public function getMinutes(): int
	{
		return (int)match ($this) {
			self::QuarterHourlyAlert => config('max_tickets.notifications.quarter_hourly.minutes'),
			self::HalfHourlyAlert => config('max_tickets.notifications.half_hourly.minutes'),
			self::HourlyAlert => config('max_tickets.notifications.hourly.minutes'),
		};
	}

	public function getTelegramToken(): string
	{
		return match ($this) {
			self::QuarterHourlyAlert => config('max_tickets.notifications.quarter_hourly.token'),
			self::HalfHourlyAlert => config('max_tickets.notifications.half_hourly.token'),
			self::HourlyAlert => config('max_tickets.notifications.hourly.token'),
		};
	}

	public function getChatId(): int
	{
		return (int)match ($this) {
			self::QuarterHourlyAlert => config('max_tickets.notifications.quarter_hourly.chat_id'),
			self::HalfHourlyAlert => config('max_tickets.notifications.half_hourly.chat_id'),
			self::HourlyAlert => config('max_tickets.notifications.hourly.chat_id'),
		};
	}
}
