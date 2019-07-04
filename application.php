<?php

    define ("Link",  'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5');
    $data = file_get_contents(Link);
    $courses = json_decode($data, true);


    $usd_buy = $courses[0]['buy'];
    $usd_sale = $courses[0]['sale'];

    $eur_buy = $courses[1]['buy'];
    $eur_sale = $courses[1]['sale'];

    $rur_buy = $courses[2]['buy'];
    $rur_sale = $courses[2]['sale'];
