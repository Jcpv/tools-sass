<?php

function hexToRgb($hex) {
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
        $r = hexdec(str_repeat($hex[0], 2));
        $g = hexdec(str_repeat($hex[1], 2));
        $b = hexdec(str_repeat($hex[2], 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return [$r, $g, $b];
}

function rgbToHex($r, $g, $b) {
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

function adjustColor($r, $g, $b, $factor) {
    // factor > 1 = más claro, factor < 1 = más oscuro
    $r = max(0, min(255, round($r * $factor)));
    $g = max(0, min(255, round($g * $factor)));
    $b = max(0, min(255, round($b * $factor)));
    return [$r, $g, $b];
}

function generateColorScale($baseHex) {
    list($r, $g, $b) = hexToRgb($baseHex);

    // Factores inspirados en Material Design (aproximados)
    $factors = [
        100 => 1.9,
        200 => 1.6,
        300 => 1.3,
        400 => 1.1,
        500 => 1.0,
        600 => 0.9,
        700 => 0.75,
        800 => 0.6,
        900 => 0.4,
    ];

    $result = [];

    foreach ($factors as $level => $factor) {
        list($newR, $newG, $newB) = adjustColor($r, $g, $b, $factor);
        $result["color-primary-$level"] = rgbToHex($newR, $newG, $newB);
    }

    return $result;
}

// Uso
$baseColor = "#2e7d32";
$scale = generateColorScale($baseColor);

echo json_encode($scale, JSON_PRETTY_PRINT);

echo "<h3>Paleta tonal para $baseColor</h3>";
foreach ($scale as $name => $hex) {
    echo "<div style='background:$hex; color:#fff; padding:8px; margin:2px;'>$name: $hex</div>";
}