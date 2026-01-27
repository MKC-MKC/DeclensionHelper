<?php

declare(strict_types=1);

namespace Haikiri\DeclensionHelper;

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

}
