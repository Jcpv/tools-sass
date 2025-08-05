<?php

function getMaterialColorScale($baseHex) {
    //$url = "https://colormagic.vercel.app/api/material?hex=" . ltrim($baseHex, "#");
    $url = "http://localhost:3000/api/material?hex=" . ltrim($baseHex, "#");
    
    
    var_dump($url);

    $json = file_get_contents($url);
    if ($json === false) {
        return null;
    }

    $data = json_decode($json, true);

    if (!isset($data['primary'])) {
        return null;
    }

    // Mapea los valores que nos interesan a nombres tipo color-primary-100, etc.
    $levels = [
        "90" => "color-primary-100",
        "80" => "color-primary-200",
        "70" => "color-primary-300",
        "60" => "color-primary-400",
        "50" => "color-primary-500",
        "40" => "color-primary-600",
        "30" => "color-primary-700",
        "20" => "color-primary-800",
        "10" => "color-primary-900",
    ];

    $result = [];

    foreach ($levels as $key => $label) {
        if (isset($data['primary'][$key])) {
            $result[$label] = $data['primary'][$key];
        }
    }

    return $result;
}

// Uso
$scale = getMaterialColorScale("#2e7d32");
echo json_encode($scale, JSON_PRETTY_PRINT);

echo "<h3>Paleta tonal para $baseColor</h3>";
foreach ($scale as $name => $hex) {
    echo "<div style='background:$hex; color:#fff; padding:8px; margin:2px;'>$name: $hex</div>";
}