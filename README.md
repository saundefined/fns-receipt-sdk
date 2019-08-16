# Проверка чека ФНС

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

## Восстановление пароля
```php
<?php

$client = new FNS\Receipt\Client('79991234567');
$client->authorization()->restore();
```

## Авторизация
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
