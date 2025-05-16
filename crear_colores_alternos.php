<?php

$datos = file_get_contents("2025 0513 1344 - Cimsur-Rojo3.json");
$json_data = json_decode('$datos');

//echo "<br><br>";
//var_dump(json_decode($datos));

$nombreIni = "";
$nombreFin = "";

$coloresTxt = '';
$coloresBg = '';

echo "<br><br>";

foreach (json_decode($datos, true) as $key => $value) {
    if ($nombreIni != substr($key, 6)) {
        $coloresTxt .= "<br/><br/>";
        $coloresBg .= "<br/><br/>";
    }

    $nombreIni = substr($key, 6);
    $nombreFin = substr($key, -3);

    $coloresTxt .= ".text-$nombreIni {color:$value;}<br/>";
    $coloresBg .= ".bg-$nombreIni {background-color:$value;}<br/>";
}


echo $coloresTxt . "<br/><br/>";
echo $coloresBg;
