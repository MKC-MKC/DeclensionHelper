<?php

namespace Haikiri\DeclensionHelper;

use PHPUnit\Framework\TestCase;

/** @see Declension */
class DeclensionTest extends TestCase
{
	/**
	 * Вариант 1. Получение ключа.
	 */
	public function test_1($number = 1, $expected = "доллар"): void
	{
		Declension::set("usd", ["доллар", "доллара", "долларов"]);
		$data = Declension::get($number, "usd");
		$this->assertEquals($expected, $data);
	}

	/**
	 * Вариант 2. Получение текста по стандартному шаблону.
	 */
	public function test_2($number = "2", $expected = "2 гривні"): void
	{
		Declension::set("грн", ["гривня", "гривні", "гривень"]);
		$data = Declension::format($number, "грн");
		$this->assertEquals($expected, $data);
	}

	/**
	 * Вариант 3. Получение текста по пользовательскому шаблону с положительным числом.
	 */
	public function test_3($number = "5.25", $expected = "На Вашем счету 5 рублей!"): void
	{
		Declension::set("руб", ["рубль", "рубля", "рублей"]);
		$mask = "На Вашем счету {item} {form}!";
		$data = Declension::format($number, "руб", $mask);
		$this->assertEquals($expected, $data);
	}

	/**
	 * Вариант 4. Получение текста по пользовательскому шаблону с отрицательным числом.
	 */
	public function test_4($number = -5.25, $expected = "На Вашем счету -6 рублей!"): void
	{
		Declension::set("руб", ["рубль", "рубля", "рублей"]);
		$mask = "На Вашем счету {item} {form}!";
		$data = Declension::format(number: $number, key: "руб", template: $mask);
		$this->assertEquals(expected: $expected, actual: $data);
	}

	public function test_5($number = 22, $expected = "Интернет отключится через 22 дня"): void
	{
		Declension::set("дн.", ["день", "дня", "дней"]);
		$mask = "Интернет отключится через {item} {form}";
		$data = Declension::format(number: $number, key: "дн", template: $mask);
		$this->assertEquals(expected: $expected, actual: $data);
	}

}
