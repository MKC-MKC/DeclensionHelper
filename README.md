# DeclensionHelper

Этот пакет предназначен для работы со склонениями местоимений в таких языках, как русский и украинский.

---

# Требования

* Composer
* PHP 8+

---

# Установка

```bash
composer req haikiri/declension-helper
```

---

# Описание

Вся работа с библиотекой сводится к двум основным операциям:

- Запись
- Чтение

### Запись

Для записи используется метод `set`:

- `key:` Это идентификатор для получения форм.
- `forms:` Это формы склонения для языка в зависимости от значения.

```php
Declension::set("роутер", ["роутер", "роутера", "роутеров"]);
```

### Чтение

Для чтения используются два метода:

- `::get` – Для получения формы склонения.
- `::format` – Для получения подготовленного текста (из шаблона).

То есть, при использовании `Declension::get` метод вернёт `роутер`, `роутера` или `роутеров`, в зависимости от числа.
А при использовании `Declension::format` метод вернёт текст по вашему шаблону, или по шаблону по умолчанию.

> Обрати внимание, что значение параметра `number` будет преобразовано и обрезано до минимального `int` остатка.

> Например: `(string) "-5.25"` будет преобразовано в `(intval) -6`

> Например: `(float) 5.25` будет преобразовано в `(intval) 5`

---

# Пример использования

Более подробные примеры использования можно найти в тестах: `\Haikiri\DeclensionHelper\Declension\DeclensionTest`

```php
<?php

require "vendor/autoload.php";

use Haikiri\DeclensionHelper\Declension;
```

### Получаем только форму склонения:

```php
Declension::set(key: "usd", forms: ["доллар", "доллара", "долларов"]);

$var = Declension::get(number: 0, key: "usd"); # Вывод: `долларов` от `0 долларов`
$var = Declension::get(number: 1, key: "usd"); # Вывод: `доллар` от `1 доллар`
$var = Declension::get(number: 2, key: "usd"); # Вывод: `доллара` от `2 доллара`
$var = Declension::get(number: "5", key: "usd"); # Вывод: `долларов` от `5 долларов`
```

### Пример вывода с шаблоном по умолчанию `{item} {form}`:

```php
Declension::set("руб", ["рубль", "рубля", "рублей"]);

$var = Declension::format(number: "-10", key: "руб"); # Вывод: `-10 рублей`
$var = Declension::format(number: 0, key: "руб"); # Вывод: `0 рублей`
$var = Declension::format(number: "1.99", key: "руб"); # Вывод: `1 рубль`
$var = Declension::format(number: 2, key: "руб"); # Вывод: `2 рубля`
$var = Declension::format(number: 5, key: "руб"); # Вывод: `5 рублей`
```

### Пример вывода с пользовательским шаблоном:

```php
$currency = "грн";
$mask = "На вашому рахунку: {item} {form}";
Declension::set($currency, ["гривня", "гривні", "гривень"]);

$var = Declension::format(number: -11.29, key: $currency, template: $mask); # Вывод: `На вашому рахунку: -12 гривень`
$var = Declension::format(number: 0, key: $currency, template: $mask); # Вывод: `На вашому рахунку: 0 гривень`
$var = Declension::format(number: 1, key: $currency, template: $mask); # Вывод: `На вашому рахунку: 1 гривня`
$var = Declension::format(number: "2.99", key: $currency, template: $mask); # Вывод: `На вашому рахунку: 2 гривні`
$var = Declension::format(number: 10.01, key: $currency, template: $mask); # Вывод: `На вашому рахунку: 10 гривень`
```
