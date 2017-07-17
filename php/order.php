<?php
include 'vendor/autoload.php';

use Serwisant\SerwisantApi;

$consumer = new SerwisantApi\ConsumerOauth('afb5f13f-3c06-4c82-81ff-2c523359039e', 'OQ5cfB2djltCbYwayqKyzlPvnmCD-QoP');
var_dump($consumer->getOrder(653848));

$consumer = new SerwisantApi\ConsumerAnonymous();
var_dump($consumer->getOrder('7yttt9'));

