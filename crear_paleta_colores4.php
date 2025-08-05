<?php

function hexToHsl($hex)
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    $r /= 255;
    $g /= 255;
    $b /= 255;

    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $h;
    $s;
    $l = ($max + $min) / 2;

    if ($max == $min) {
        $h = $s = 0;
    } else {
        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
        switch ($max) {
            case $r:
                $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                break;
            case $g:
                $h = ($b - $r) / $d + 2;
                break;
            case $b:
                $h = ($r - $g) / $d + 4;
                break;
        }
        $h /= 6;
    }

    return [$h * 360, $s * 100, $l * 100];
}

function hslToHex($h, $s, $l)
{
    $h /= 360;
    $s /= 100;
    $l /= 100;

    $r;
    $g;
    $b;

    if ($s == 0) {
        $r = $g = $b = $l;
    } else {
        $hue2rgb = function ($p, $q, $t) {
            if ($t < 0) $t += 1;
            if ($t > 1) $t -= 1;
            if ($t < 1 / 6) return $p + ($q - $p) * 6 * $t;
            if ($t < 1 / 2) return $q;
            if ($t < 2 / 3) return $p + ($q - $p) * (2 / 3 - $t) * 6;
            return $p;
        };

        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;

        $r = $hue2rgb($p, $q, $h + 1 / 3);
        $g = $hue2rgb($p, $q, $h);
        $b = $hue2rgb($p, $q, $h - 1 / 3);
    }

    $toHex = function ($x) {
        $hex = dechex(round($x * 255));
        return strlen($hex) == 1 ? "0$hex" : $hex;
    };

    return '#' . $toHex($r) . $toHex($g) . $toHex($b);
}

// Generar la escala
$baseHex = "#2e7d32";
list($h, $s, $l) = hexToHsl($baseHex);

// Definir los niveles de luminosidad manualmente
$tones = [
    100 => 90,
    200 => 75,
    300 => 60,
    400 => 50,
    500 => $l,
    600 => 35,
    700 => 29,
    800 => 20,
    900 => 10,
];

$colors = [];

foreach ($tones as $key => $lightness) {
    $lightness = max(0, min(100, $lightness)); // Limitar entre 0 y 100
    $hex = hslToHex($h, $s, $lightness);
    $colors["color-primary-$key"] = $hex;
}

echo json_encode($colors, JSON_PRETTY_PRINT);

echo "<h3>Paleta tonal para $baseColor</h3>";
foreach ($colors as $name => $hex) {
    echo "<div style='background:$hex; color:#fff; padding:8px; margin:2px;'>$name: $hex</div>";
}