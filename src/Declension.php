<?php

declare(strict_types=1);

namespace Haikiri\DeclensionHelper;

use InvalidArgumentException;

final class Declension
{

	private static array $registry = [];

	/** @throws InvalidArgumentException */
	public static function prepare($number, array $forms): string
	{
		$n = abs(self::normalizeNumber($number));
		[$one, $few, $many] = self::normalizeForms($forms);
		if ($n % 100 >= 11 && $n % 100 <= 20) return $many;

		return match ($n % 10) {
			1 => $one,
			2, 3, 4 => $few,
			default => $many,
		};
	}

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
		self::$registry[self::normalizeKey($key)] = self::normalizeForms($forms);
	}

	/** @throws InvalidArgumentException */
	public static function get($number, string $key): string
	{
		if (!self::has($key)) {
			throw new InvalidArgumentException("unknown declension key");
		}

		return self::prepare($number, self::$registry[self::normalizeKey($key)]);
	}

	public static function format($number, string $key, string $template = "{item} {form}"): string
	{
		$form = self::get($number, $key);
		$intNumber = self::normalizeNumber($number);
		return str_replace(
			["{item}", "{form}"],
			[$intNumber, $form],
			$template,
		);
	}

	private static function normalizeNumber($number): int
	{
		if (is_int($number)) return $number;

		if (is_float($number)) {
			if (!is_finite($number)) throw new InvalidArgumentException("number must be finite");
			return (int)floor($number);
		}

		if (is_string($number)) {
			$value = trim($number);
			if ($value === "" || !is_numeric($value)) throw new InvalidArgumentException("number must be numeric");
			return (int)floor((float)$value);
		}

		throw new InvalidArgumentException("number must be numeric");
	}

	private static function normalizeForms(array $forms): array
	{
		if (count($forms) !== 3) throw new InvalidArgumentException("3 forms are expected");

		$values = array_values($forms);
		foreach ($values as $form) if (!is_string($form)) {
			throw new InvalidArgumentException("forms must contain only strings");
		}

		return $values;
	}

}
