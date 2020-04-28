# Redis
Брокер для [memcached(https://php.net/memcached).


## Конфигурация
|Имя|     Тип|       Описание| Значение по-умолчанию|
|:-------:|:---:|:--------------:|:---------------------:|
|servers|array| Адреса серверов |127.0.0.1|

## Пример использования:

```php
$memcached = $app->factory('Memcached');
$redis->set('foo', 'bar');
```
