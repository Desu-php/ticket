<?php

namespace Maxdev\Tickets\Helpers;

class TextHelper
{
	public static function clearText(string $text): string
	{
		return strip_tags(
			str_ireplace(['<br>', '<br >', '<br />', '<br/>'], "\r\n", $text)
		);
	}
}