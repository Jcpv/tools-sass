<?php

function hexToRgb($hex)
{
    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    return [
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2))
    ];
}

function rgbToHex($r, $g, $b)
{
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

function adjustBrightness($rgb, $percent)
{
    $adjust = function ($component) use ($percent) {
        $new = $component + ($component * $percent / 100);
        return max(0, min(255, round($new)));
    };

    return [
        'r' => $adjust($rgb['r']),
        'g' => $adjust($rgb['g']),
        'b' => $adjust($rgb['b'])
    ];
}

function generateColorPalette($baseHex, $steps = [-40, -30, -20, -10, 0, 10, 20, 30, 40])
{
    $baseRgb = hexToRgb($baseHex);
    $palette = [];

    foreach ($steps as $step) {
        $adjustedRgb = adjustBrightness($baseRgb, $step);
        $palette[] = rgbToHex($adjustedRgb['r'], $adjustedRgb['g'], $adjustedRgb['b']);
    }

    return $palette;
}

// USO
$baseColor = '#2e7d32'; // Color inicial
$palette = generateColorPalette($baseColor);

// Mostrar resultados
echo "Paleta basada en el color $baseColor:<br>";
foreach ($palette as $color) {
    echo "<div style='background-color:$color; color:#fff; padding:10px;'>$color</div>";
}


/************************** */
echo "<br>RESULTADO 2 <br>";
