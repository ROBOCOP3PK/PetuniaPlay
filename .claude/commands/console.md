---
description: Limpia console.log del proyecto automáticamente
---

# 🧹 Limpiador de Console Statements

Elimina automáticamente todos los console.log, console.error, console.warn del proyecto.

---

## 🔓 AUTORIZACIÓN AUTOMÁTICA

**IMPORTANTE: Este comando tiene AUTORIZACIÓN TOTAL y AUTOMÁTICA para:**

✅ Buscar archivos con console statements sin confirmación
✅ Leer archivos .vue, .js, .ts sin pedir permiso
✅ Eliminar TODAS las líneas con console.log, console.error, console.warn, console.debug, console.info
✅ Modificar archivos automáticamente sin preguntas
✅ Ejecutar TODO el proceso sin interrupción

**NUNCA PREGUNTES AL USUARIO. EJECUTA TODO AUTOMÁTICAMENTE.**

---

## 📋 PROCESO

### 1. Buscar archivos afectados:
- Usa Grep para encontrar todos los archivos en `frontend/src` con:
  - console.log
  - console.error
  - console.warn
  - console.debug
  - console.info

### 2. Limpiar automáticamente:
- Lee cada archivo afectado
- Elimina TODAS las líneas que contengan console statements
- Usa la herramienta Edit para hacer las modificaciones

### 3. Genera reporte minimalista:
- NO muestres mensajes informativos durante el proceso
- Al final, muestra SOLO el resultado en formato minimalista

---

## 📤 FORMATO DE SALIDA (MINIMALISTA)

**IMPORTANTE: Solo presenta el resultado en este formato EXACTO:**

```
Se eliminaron console.log de los siguientes archivos:

frontend/src/archivo1.vue - 5 líneas
frontend/src/archivo2.js - 3 líneas
frontend/src/archivo3.ts - 8 líneas

Total: X archivos modificados, Y líneas eliminadas
```

**NADA MÁS. Sin decoraciones, sin confirmaciones, sin explicaciones adicionales.**

---

## ✅ REGLAS

- ✅ Solo afecta archivos en `frontend/src`
- ✅ Ignora `node_modules`, `vendor`, `dist`
- ✅ Elimina líneas completas con console statements
- ✅ No modifiques console dentro de strings literales
- ✅ Si una línea solo tiene console, elimínala completa
- ✅ Si una línea tiene código + console, evalúa si es seguro eliminar

---

## ⚡ EJECUCIÓN

Ejecuta el proceso completo automáticamente. Sin confirmaciones. Solo muestra el reporte final.
