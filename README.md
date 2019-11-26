# Проверка чека ФНС

![GitHub Actions](https://github.com/saundefined/fns-receipt-sdk/workflows/Testing%20with%20PHPUnit/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/saundefined/fns-receipt/v/stable)](https://packagist.org/packages/saundefined/fns-receipt)
[![Latest Unstable Version](https://poser.pugx.org/saundefined/fns-receipt/v/unstable)](https://packagist.org/packages/saundefined/fns-receipt)
[![codecov](https://codecov.io/gh/saundefined/fns-receipt-sdk/branch/master/graph/badge.svg)](https://codecov.io/gh/saundefined/fns-receipt-sdk)
[![Total Downloads](https://poser.pugx.org/saundefined/fns-receipt/downloads)](https://packagist.org/packages/saundefined/fns-receipt)
[![License](https://poser.pugx.org/saundefined/fns-receipt/license)](https://packagist.org/packages/saundefined/fns-receipt)

## Авторизация

### Регистрация
```php
<?php

$user = new FNS\Receipt\Model\User();
$user->setEmail('email@example.com');
$user->setName('John Doe');

$client = new FNS\Receipt\Client('79991234567');
$client->authorization()->signUp($user);
```

### Восстановление пароля
```php
<?php

$client = new FNS\Receipt\Client('79991234567');
$client->authorization()->restore();
```

### Авторизация
```php
<?php

$client = new FNS\Receipt\Client('79991234567');
$client->authorization()->withCode(111111);
```

## Чек

### Проверка чек
```php
<?php

$receipt = new FNS\Receipt\Model\Receipt();
$receipt->setNumber('1112222333444444');
$receipt->setDocument('112233');
$receipt->setTag('1112223333');
$receipt->setType(FNS\Receipt\Model\Receipt::FNS_RECEIPT_TYPE_INCOMING);
$receipt->setDate(new DateTime('01.01.2019 00:00'));
$receipt->setPrice(1000.00);

$client = new FNS\Receipt\Client('79991234567');
if ($client->authorization()->withCode(111111)->isSuccess()) {
    $data = $client->receipt($receipt)->exists();
}
``` 

### Детальная информация о чеке
```php
<?php

$receipt = new FNS\Receipt\Model\Receipt();
$receipt->setNumber('1112222333444444');
$receipt->setDocument('112233');
$receipt->setTag('1112223333');
$receipt->setType(FNS\Receipt\Model\Receipt::FNS_RECEIPT_TYPE_INCOMING);
$receipt->setDate(new DateTime('01.01.2019 00:00'));
$receipt->setPrice(1000.00);

$client = new FNS\Receipt\Client('79991234567');
if ($client->authorization()->withCode(111111)->isSuccess()) {
    $data = $client->receipt($receipt)->detail();
}
``` 
