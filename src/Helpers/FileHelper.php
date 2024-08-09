<?php
declare(strict_types=1);

namespace Maxdev\Tickets\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Maxdev\Tickets\Exceptions\InvalidUrlException;

class FileHelper
{
	public static function uploadFromStringUrl(?string $urls, string $separator = ', '): array
	{
		if (empty($urls)) {
			return [];
		}

		$files = explode($separator, $urls);

		return self::uploadFromArrayOfUrl($files);
	}

	public static function uploadFromArrayByKey(array $urls, string $key = 'url'): array
	{
		$files = array_map(
			fn(array $row) => $row[$key],
			$urls
		);

		return self::uploadFromArrayOfUrl($files);
	}

	public static function uploadFromArrayOfUrl(array $files): array
	{
		$attachments = [];

		foreach ($files as $file) {
			try {
				$attachments[] = self::uploadFromUrl($file);
			} catch (\Throwable $throwable) {

			}
		}

		return $attachments;
	}

	/**
	 * @throws InvalidUrlException
	 * @throws \Exception
	 */
	public static function uploadFromUrl(string $url): UploadedFile
	{
		if (!Str::startsWith($url, ['http://', 'https://'])) {
			throw InvalidUrlException::doesNotStartWithProtocol($url);
		}

		$temporaryFile = self::getTempFile($url);


		$filename = basename(parse_url($url, PHP_URL_PATH));
		$filename = urldecode($filename);

		if ($filename === '') {
			$filename = 'file';
		}

		$mimeType = mime_content_type($temporaryFile);

		if (!Str::contains($filename, '.')) {
			$mediaExtension = explode('/', $mimeType);
			$filename       = "{$filename}.{$mediaExtension[1]}";
		}

		return new UploadedFile($temporaryFile, $filename, $mimeType);
	}

	public static function getTempFile(string $url): string
	{
		$context = stream_context_create();

		if (!$stream = @fopen($url, 'r', false, $context)) {
			throw new \Exception("Url `{$url}` cannot be reached");
		}

		$temporaryFile = tempnam(sys_get_temp_dir(), 'max-tickets');

		file_put_contents($temporaryFile, $stream);

		fclose($stream);

		return $temporaryFile;
	}
}