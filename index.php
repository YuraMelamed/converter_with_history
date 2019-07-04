<?php
require_once 'db_connect.php';
require_once 'application.php';

    if (isset($_POST['UAH'])){
        switch ($_POST['currency']){
            case 'USD':
                $uahToUsd = $_POST['UAH'] / $usd_sale;
                break;
            case 'EUR':
                $uahToEur = $_POST['UAH'] / $eur_sale;
                break;
            case 'RUR':
                $uahToRur = $_POST['UAH'] / $rur_sale;
                break;
        }
    }
    try{
        $sql = 'CREATE TABLE exchange(
          id int not null auto_increment,
          uah varchar (255),
          uahToUsd varchar (255),
          uahToEur varchar (255),
          uahToRur varchar (255),
          primary key (id)
        );';

        $pdo -> exec($sql);

        $sql = 'INSERT INTO exchange set 
          uah = :uah,
          uahToUsd = :uahToUsd,
          uahToEur = :uahToEur,
          uahToRur = :uahToRur;
        ';

        $x = $pdo->prepare($sql);
        $x->bindValue(':uah', $_POST['UAH']);
        $x->bindValue(':uahToUsd', $uahToUsd);
        $x->bindValue(':uahToEur', $uahToEur);
        $x->bindValue(':uahToRur', $uahToRur);

        $x->execute();

    }catch(Exception $e){
        echo'Cannot insert record';
        die;
    }

?>

<html>
<head>
    <meta charset="utf-8">
    <title>Converter for UAH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 align="center">Курс валют</h1>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Currency</th>
                    <th scope="col">Buy</th>
                    <th scope="col">Sale</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>USD</strong></td>
                    <td> <?=round($usd_buy,3) . ' uah'?> </td>
                    <td> <?=round($usd_sale,3) . ' uah'?> </td>
                </tr>
                <tr>
                    <td><strong>EUR</strong></td>
                    <td> <?=round($eur_buy,3) . ' uah'?> </td>
                    <td> <?=round($eur_sale,3) . ' uah'?> </td>
                </tr>
                <tr>
                    <td><strong>RUR</strong></td>
                    <td> <?=round($rur_buy,3) . ' uah'?> </td>
                    <td> <?=round($rur_sale,3) . ' uah'?> </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class="container">
        <h1 align="center">Конвертер</h1>
        <?php
            $last = $pdo->query('SELECT * FROM exchange ORDER BY id DESC LIMIT 1');
                $lastConvert = $last->fetchAll();
        ?>
        <form method="post">
            <label><strong> Введите сумму для конвертации </strong>
                <input type="number" name="UAH" value = <?=$lastConvert[0]['uah']?>>
            </label>
            <label><strong> Вы получите </strong>
                <input type="number" value =
                <?php
                    if($lastConvert[0]['uahToUsd'] > 0){
                        echo round($lastConvert[0]['uahToUsd'], 3);
                    }
                    elseif ($lastConvert[0]['uahToEur'] > 0){
                        echo round($lastConvert[0]['uahToEur'], 3);
                    }
                    elseif ($lastConvert[0]['uahToRur'] > 0){
                        echo round($lastConvert[0]['uahToRur'], 3);
                    }
                ?>
                >
                <select name="currency">
                    <option> USD </option>
                    <option> EUR </option>
                    <option> RUR </option>
                </select>
            </label>
            <button type="submit" class="btn btn-primary">Посчитать</button>
        </form>
    </div>
<h1 align="center">История 10 последних конвертаций</h1>
    <div class="container">
        <ul>
            <?php
            $history = $pdo->query('SELECT * FROM exchange ORDER BY id DESC LIMIT 10');
            $historyArray = $history->fetchAll();

                foreach ($historyArray as $value){
                    if(isset($value['uahToUsd']) && $value['uahToUsd'] > 0){
                        echo '<li>' . 'За ' . $value['uah'] . ' UAH вы получили ' . round($value['uahToUsd'], 3) . ' USD' . '</li>';
                    }
                    elseif (isset($value['uahToEur']) && $value['uahToEur'] > 0){
                        echo '<li>' . 'За ' . $value['uah'] . ' UAH вы получили ' . round($value['uahToEur'], 3) . ' EUR' . '</li>';
                    }
                    elseif (isset($value['uahToRur']) && $value['uahToRur'] > 0){
                        echo '<li>' . 'За ' . $value['uah'] . ' UAH вы получили ' . round($value['uahToRur'], 3) . ' RUR' . '</li>';
                    }
                }
            ?>
        </ul>
    </div>
</body>
</html>