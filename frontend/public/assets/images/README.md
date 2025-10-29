# Estructura de Imágenes - Petunia Play

## Logos Disponibles

### 1. **logo_full_sin_fondo.jpeg** ✅ (EN USO)
- **Tamaño:** 63KB
- **Descripción:** Logo completo (imagen + texto) sin fondo
- **Uso actual:**
  - Header principal del sitio
  - Panel de administración (sidebar)
  - Footer
- **Recomendado para:** Fondos oscuros o claros, máxima versatilidad

### 2. **logo_full.jpeg**
- **Tamaño:** 43KB
- **Descripción:** Logo completo con fondo
- **Uso recomendado:** Documentos, presentaciones, redes sociales

### 3. **logo_picture.jpeg**
- **Tamaño:** 27KB
- **Descripción:** Solo el ícono/símbolo del logo (sin texto)
- **Uso recomendado:** Favicon, íconos pequeños, apps móviles

### 4. **logo_text.jpeg**
- **Tamaño:** 31KB
- **Descripción:** Solo el texto "Petunia Play"
- **Uso recomendado:** Encabezados alternativos, firmas de email

## Estructura de Carpetas

```
/public
  /assets
    /images
      - logo_full_sin_fondo.jpeg  (Logo principal - sin fondo)
      - logo_full.jpeg            (Logo con fondo)
      - logo_picture.jpeg         (Solo ícono)
      - logo_text.jpeg            (Solo texto)
      - README.md                 (Este archivo)
```

## Ubicaciones del Logo en el Código

1. **TheHeader.vue** - `/src/components/layout/TheHeader.vue`
   - Línea: ~83
   - Ruta: `/assets/images/logo_full_sin_fondo.jpeg`

2. **AdminLayout.vue** - `/src/layouts/AdminLayout.vue`
   - Línea: ~25
   - Ruta: `/assets/images/logo_full_sin_fondo.jpeg`

3. **TheFooter.vue** - `/src/components/layout/TheFooter.vue`
   - Línea: ~8
   - Ruta: `/assets/images/logo_full_sin_fondo.jpeg`

## Notas de Implementación

- Todas las imágenes usan rutas absolutas desde `/public` (ej: `/assets/images/logo.jpeg`)
- Las clases CSS aplican: `h-10 w-auto object-contain` o `h-12 w-auto object-contain`
- El atributo `alt` está configurado como "Petunia Play Logo"
- Las imágenes son responsivas y mantienen su proporción

## Cambios Futuros

Si necesitas cambiar el logo:
1. Reemplaza el archivo en `/public/assets/images/`
2. Mantén el mismo nombre o actualiza las rutas en los componentes mencionados
3. Reinicia el servidor de desarrollo si es necesario

---

**Última actualización:** 29 de Octubre, 2025
