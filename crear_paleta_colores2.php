 <?php

function hexToHsl($hex)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $h = $s = $l = ($max + $min) / 2;

        if ($max === $min) {
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

        return [
            'h' => round($h * 360),
            's' => round($s * 100),
            'l' => round($l * 100)
        ];
    }

    function hslToHex($h, $s, $l)
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;

        $r = $g = $b = 0;

        if ($s === 0) {
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

        return sprintf("#%02x%02x%02x", round($r * 255), round($g * 255), round($b * 255));
    }

    function generateHslPalette($baseHex, $type)
    {
        $base = hexToHsl($baseHex);
        $palette = [];

        switch (strtolower($type)) {
            case 'analogos':
                $shifts = [-30, 0, 30];
                break;
            case 'complementarios':
                $shifts = [0, 180];
                break;
            case 'triada':
                $shifts = [0, 120, 240];
                break;
            case 'tetrada':
                $shifts = [0, 90, 180, 270];
                break;
            case 'monocromatico':
                return generateMonochromePalette($baseHex); // función aparte
            default:
                return ["#000000"];
        }

        foreach ($shifts as $shift) {
            $h = ($base['h'] + $shift) % 360;
            if ($h < 0) $h += 360;
            $palette[] = hslToHex($h, $base['s'], $base['l']);
        }

        return $palette;
    }

    function generateMonochromePalette($baseHex)
    {
        $base = hexToHsl($baseHex);
        $palette = [];

        $lightnessLevels = [20, 35, 50, 65, 80];
        foreach ($lightnessLevels as $l) {
            $palette[] = hslToHex($base['h'], $base['s'], $l);
        }

        return $palette;
    }

    // USO
    $baseColor = '#3498db';
    $tipos = ['Analogos', 'Complementarios', 'Triada', 'Tetrada', 'Monocromatico'];

    foreach ($tipos as $tipo) {
        echo "<h3>$tipo</h3>";
        $palette = generateHslPalette($baseColor, $tipo);
        foreach ($palette as $color) {
            echo "<div style='display:inline-block; width:100px; height:50px; background-color:$color; color:#fff; text-align:center;'>$color</div>";
        }
    }
