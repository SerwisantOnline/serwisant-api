<?php
include 'vendor/autoload.php';

use Serwisant\SerwisantApi;

// utworzenie kliena API
$consumer = new SerwisantApi\ConsumerOauth('f073e5b4-e638-439a-8109-7da713cfd73e', '2bc1JjFYqGEqRVaK1RsHnbeiVtgnASHl');

echo "\n";
echo "Pobranie i wyświetlenie pierwszych 10 przeterminowanych napraw\n";

$orders = $consumer->getOrders(1, SerwisantApi\ConsumerOauth::FILTER_ORDER_EXPIRED);
foreach ($orders as $order) {
  echo "Klient: {$order->get('customer.display_name')}, naprawa: {$order->get('display_name')} w stanie: {$order->get('status_display_name')}\n";
}

echo "\n";
echo "Pobranie wszystkich otwartych napraw\n";

$orders = $consumer->getAllOrders(SerwisantApi\ConsumerOauth::FILTER_ORDER_OPEN);
$num = count($orders);
echo "Wszystkich napraw: {$num}\n";

echo "\n";
echo "pobranie informacji o pojedynczej naprawie\n";

$order = $consumer->getOrder(1425);
echo "Klient: {$order->get('customer.display_name')}, naprawa: {$order->get('display_name')} w stanie: {$order->get('status_display_name')}\n";

echo "\n";
echo "pobranie informacji uproszczonej używając kodu naprawy\n";

$consumer = new SerwisantApi\ConsumerAnonymous();
$order = $consumer->getOrder('63ngc2');
echo "Naprawa: {$order->get('display_name')} w stanie: {$order->get('status_display_name')}\n";
