<?php

declare(strict_types=1);

namespace Haikiri\DeclensionHelper;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @see Declension */
class DeclensionTest extends TestCase
{
	/**
	 * Вариант 1. Получение ключа.
	 */
	public static function test_1($number = 1, $expected = "доллар"): void
	{
		Declension::set("usd", ["доллар", "доллара", "долларов"]);
		$data = Declension::get($number, "usd");
		self::assertSame($expected, $data);
	}

	/**
	 * Вариант 2. Получение текста по стандартному шаблону.
	 * @noinspection SpellCheckingInspection
	 */
	public static function test_2($number = "2", $expected = "2 гривні"): void
	{
		Declension::set("грн", ["гривня", "гривні", "гривень"]);
		$data = Declension::format($number, "грн");
		self::assertSame($expected, $data);
	}

	/**
	 * Вариант 3. Получение текста по пользовательскому шаблону с положительным числом.
	 */
	public static function test_3($number = "5.25", $expected = "На Вашем счету 5 рублей!"): void
	{
		Declension::set("руб", ["рубль", "рубля", "рублей"]);
		$mask = "На Вашем счету {item} {form}!";
		$data = Declension::format($number, "руб", $mask);
		self::assertSame($expected, $data);
	}

	/**
	 * Вариант 4. Получение текста по пользовательскому шаблону с отрицательным числом.
	 */
	public static function test_4($number = -5.25, $expected = "На Вашем счету -6 рублей!"): void
	{
		Declension::set("руб", ["рубль", "рубля", "рублей"]);
		$mask = "На Вашем счету {item} {form}!";
		$data = Declension::format(number: $number, key: "руб", template: $mask);
		self::assertSame(expected: $expected, actual: $data);
	}

	/**
	 * Пример с использованием шаблона дней.
	 */
	public static function test_5($number = 22, $expected = "Интернет отключится через 22 дня"): void
	{
		Declension::set("дн.", ["день", "дня", "дней"]);
		$mask = "Интернет отключится через {item} {form}";
		$data = Declension::format(number: $number, key: "дн", template: $mask);
		self::assertSame(expected: $expected, actual: $data);
	}

	/**
	 * Пример возврата строки соответствующего ключа формы, относительно переданного номера.
	 */
	public static function test_6(): void
	{
		# Формы года.
		$forms = ["год", "года", "лет"];

		# Как: "21 год"
		$return = "год";
		$result = Declension::prepare(number: 21, forms: $forms);
		self::assertSame(expected: $return, actual: $result);

		# Как: "22 года"
		$return = "года";
		$result = Declension::prepare(number: 22, forms: $forms);
		self::assertSame(expected: $return, actual: $result);

		# Как: "26 лет"
		$return = "лет";
		$result = Declension::prepare(number: 26, forms: $forms);
		self::assertSame(expected: $return, actual: $result);
	}

	/**
	 * Вариант 7. Отрицательное число должно склоняться по модулю.
	 */
	public static function test_7($number = -1, $expected = "-1 рубль"): void
	{
		Declension::set("руб", ["рубль", "рубля", "рублей"]);
		$data = Declension::format($number, "руб");
		self::assertSame($expected, $data);
	}

	/**
	 * Вариант 8. Проверка исключения для неизвестного ключа.
	 */
	public static function test_8(): void
	{
		try {
			Declension::get(1, "unknown");
			self::fail("Ожидалось исключение InvalidArgumentException");
		} catch (InvalidArgumentException $exception) {
			self::assertSame("unknown declension key", $exception->getMessage());
		}
	}

	/**
	 * Вариант 9. Проверка исключения для нечислового значения.
	 */
	public static function test_9(): void
	{
		Declension::set("руб", ["рубль", "рубля", "рублей"]);

		try {
			Declension::format("abc", "руб");
			self::fail("Ожидалось исключение InvalidArgumentException");
		} catch (InvalidArgumentException $exception) {
			self::assertSame("number must be numeric", $exception->getMessage());
		}
	}

	/**
	 * Вариант 10. Проверка исключения для некорректного списка форм.
	 */
	public static function test_10(): void
	{
		try {
			Declension::set("usd", ["доллар", "доллара"]);
			self::fail("Ожидалось исключение InvalidArgumentException");
		} catch (InvalidArgumentException $exception) {
			self::assertSame("3 forms are expected", $exception->getMessage());
		}
	}

	/**
	 * Вариант 11. Проверка исключения для non-string форм.
	 */
	public static function test_11(): void
	{
		try {
			Declension::prepare(1, ["доллар", "доллара", 123]);
			self::fail("Ожидалось исключение InvalidArgumentException");
		} catch (InvalidArgumentException $exception) {
			self::assertSame("forms must contain only strings", $exception->getMessage());
		}
	}

}
