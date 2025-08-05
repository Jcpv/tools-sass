const express = require('express');
const cors = require('cors');
const { argbFromHex, CorePalette } = require('@material/material-color-utilities');

const app = express();
app.use(cors());

// ARGB a HEX
function argbToHex(argb) {
  return `#${(argb & 0xffffff).toString(16).padStart(6, '0')}`;
}

// Mapas de tonos Material Design
const toneMap = {
  100: 90,
  200: 80,
  300: 70,
  400: 60,
  500: 50,
  600: 40,
  700: 30,
  800: 20,
  900: 10,
};

// Función para generar escala por tipo (a1, a2, a3)
function generateScale(palettePart) {
  const result = {};
  for (const [label, tone] of Object.entries(toneMap)) {
    const value = palettePart.tone(tone);
    result[label] = argbToHex(value);
  }
  return result;
}

app.get('/api/material', (req, res) => {
  const hex = req.query.hex;

  if (!hex || !/^#?[0-9A-Fa-f]{6}$/.test(hex)) {
    return res.status(400).json({ error: 'Invalid or missing hex parameter' });
  }

  const cleanHex = hex.startsWith('#') ? hex : `#${hex}`;
  const argb = argbFromHex(cleanHex);
  const palette = CorePalette.of(argb);

  const primary = generateScale(palette.a1, 'color-primary');
  const secondary = generateScale(palette.a2, 'color-secondary');
  const tertiary = generateScale(palette.a3, 'color-tertiary');
  const neutral = generateScale(palette.n1, 'color-neutral');
  const neutralVariant = generateScale(palette.n2, 'color-neutral-variant');
  const error = generateScale(palette.error, 'color-error');


  // Opcional: Forzar color base original como primary-500
  primary[500] = cleanHex.toLowerCase();

  // console.log(primary, secondary, tertiary);
  res.json({
    // base: cleanHex.toLowerCase(),
    primary,
    secondary,
    tertiary,
    neutral,
    neutralVariant, 
    error
  });

});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`✅ Material color API running at http://localhost:${PORT}`);
});
