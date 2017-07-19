<?php
include 'vendor/autoload.php';

use Serwisant\SerwisantApi;

$consumer = new SerwisantApi\ConsumerOauth('f073e5b4-e638-439a-8109-7da713cfd73e', '2bc1JjFYqGEqRVaK1RsHnbeiVtgnASHl');

$order = $consumer->get('/api/v1/orders/1425');
$orders = $consumer->get('/api/v1/orders', ['filter' => 'open']);

var_dump($order);
var_dump($orders);
