<?php

function getMaterialColorScale($baseHex)
{
    $url = "http://localhost:3000/api/material?hex=" . ltrim($baseHex, "#");


    $json = file_get_contents($url);
    if ($json === false) {
        return null;
    }

    $data = json_decode($json, true);
    echo "<br><br>";
    var_dump($data);
    echo "<br><br>";


    if (!isset($data['primary'])) {
        return null;
    }

    $result = [];

    foreach ($data['primary'] as $key => $hex) {
        $result["color-primary-$key"] = $hex;
    }

    if (isset($data['secondary'])) {
        foreach ($data['secondary'] as $key => $hex) {
            $result["color-secondary-$key"] = $hex;
        }
    }

    if (isset($data['tertiary'])) {
        foreach ($data['tertiary'] as $key => $hex) {
            $result["color-tertiary-$key"] = $hex;
        }
    }

    if (isset($data['neutral'])) {
        foreach ($data['neutral'] as $key => $hex) {
            $result["color-neutral-$key"] = $hex;
        }
    }

    if (isset($data['neutralVariant'])) {
        foreach ($data['neutralVariant'] as $key => $hex) {
            $result["color-neutralVariant-$key"] = $hex;
        }
    }

     if (isset($data['error'])) {
        foreach ($data['error'] as $key => $hex) {
            $result["color-error-$key"] = $hex;
        }
    }


    return $result;
}


// Uso
$baseColor = "#4AABF9";
$scale = getMaterialColorScale($baseColor);

echo "<br><br>";

var_dump($scale);
echo "<br><br>";

echo json_encode($scale, JSON_PRETTY_PRINT);
echo "<br><br>";

echo "<h3>Paleta tonal para [$baseColor]</h3>";
foreach ($scale as $name => $hex) {
    echo "<div style='background:$hex; color:#fff; padding:8px; margin:1px; border: 1px solid $baseColor;'>$name: $hex</div>";
}
