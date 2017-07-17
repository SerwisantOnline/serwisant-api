<?php include 'src/load.php' ?>
<html>
<head>
  <meta charset="utf-8">
  <title>Sprawdź stan zlecenia</title>
</head>
<body>

<form>
  <p>
    Podaj kod zlecenia
    <input type='text' name='token' value='<?php echo @$_GET['token'] ?>' />
    <input type='submit' />
  </p>
</form>

<?php
if (array_key_exists("token", $_GET)):
$order = new SerwisantOrder();
$order->setToken($_GET["token"]);
if ($order->found()):
?>

<table>
<tr>
  <td>RMA</td>
  <td><?php echo $order->rma ?></td>
</tr>
<tr>
  <td>Przedmiot zlecenia</td>
  <td><?php echo $order->display_name ?> <?php echo $order->order_detail->model ?></td>
</tr>
<tr>
  <td>Opis problemu</td>
  <td><?php echo $order->order_detail->issue ?></td>
</tr>
<tr>
  <td>Diagnoza zlecenia</td>
  <td><?php echo $order->order_diagnosis ? $order->order_diagnosis->customer_visible_remarks : 'Jeszcze nie ustalono' ?></td>
</tr>
<tr>
  <td>Przewidywany koszt naprawy</td>
  <td><?php echo ($order->order_diagnosis && $order->order_diagnosis->price_estimated) ? $order->order_diagnosis->price_estimated : 'Jeszcze nie ustalono' ?></td>
</tr>
<tr>
  <td>Planowana data zakończenia</td>
  <td><?php echo $order->order_detail->finish_date_estimated ?></td>
</tr>
<tr>
  <td>Stan zlecenia</td>
  <td><?php echo $order->status_display_name ?></td>
</tr>
</table>

<?php
else:
echo "Nie znaleziono takiego zlecenia serwisowego";
endif;
endif;
?>

</body>
