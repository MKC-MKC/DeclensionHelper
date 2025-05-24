<?php

declare(strict_types=1);

namespace Haikiri\DeclensionHelper;

use InvalidArgumentException;

final class Declension
{

	private static array $registry = [];

	private static function normalizeKey(string $key): string
	{
		return rtrim($key, ".");
	}

	public static function has(string $key): bool
	{
		return isset(self::$registry[self::normalizeKey($key)]);
	}

	/** @throws InvalidArgumentException */
	public static function set(string $key, array $forms): void
	{
		if (count($forms) !== 3) {
			throw new InvalidArgumentException("3 forms are expected");
		}

		self::$registry[self::normalizeKey($key)] = array_values($forms);
	}

	/** @throws InvalidArgumentException */
	public static function get($number, string $key): string
	{
		if (!self::has($key)) {
			throw new InvalidArgumentException("unknown declension key");
		}

		$n = (int)floor((float)$number);
		[$one, $few, $many] = self::$registry[self::normalizeKey($key)];

		if ($n % 100 >= 11 && $n % 100 <= 20) {
			return $many;
		}

		return match ($n % 10) {
			1 => $one,
			2, 3, 4 => $few,
			default => $many,
		};
	}

	public static function format($number, string $key, string $template = "{item} {form}"): string
	{
		$form = self::get($number, $key);
		return str_replace(
			["{item}", "{form}"],
			[(int)floor((float)$number), $form],
			$template
		);
	}

}
