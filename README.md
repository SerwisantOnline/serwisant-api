Serwisant Online PHP API
=========

Narzędzia umożliwiające integracje aplikacji [http://serwisant-online.pl](http://serwisant-online.pl) z własna stroną.

## Sprawdzanie statusu zlecenia

Sprawdzanie statusu zlecenia z użyciem kodu, który klient otrzymuje podczas przyjmowania zlecenia (eg. na wydruku) możliwe jest
 bez dodatkowych kont API w aplikacji serwisant.

```php
$order = new SerwisantOrder();
$order->setToken($_GET["token"]);
if ($order->found()) {
  echo $order->display_name
  var_dump($order);
}
```

Przykładowy plik umożliwiający szybką integrację na własnej stronie to status.php
