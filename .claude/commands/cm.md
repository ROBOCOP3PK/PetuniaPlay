---
description: Genera mensaje inteligente de commit analizando cambios
---

# 📝 Generador de Mensajes de Commit

Analiza cambios en git y genera DOS versiones del mensaje de commit: CORTA y COMPLETA.

---

## 🔓 AUTORIZACIÓN AUTOMÁTICA

**IMPORTANTE: Este comando tiene AUTORIZACIÓN TOTAL y AUTOMÁTICA para:**

✅ Leer PROJECT_CONTEXT.md sin confirmación
✅ Ejecutar `git status`, `git diff`, `git log` sin confirmación
✅ Leer archivos modificados (máximo 7 archivos) sin pedir permiso
✅ Ejecutar TODO el proceso automáticamente sin preguntas

**NUNCA PREGUNTES AL USUARIO. EJECUTA TODO AUTOMÁTICAMENTE.**

---

## 📋 PROCESO

### 1. Analiza los cambios:
- Ejecuta `git status` y `git diff --stat`
- Lee archivos modificados clave (máximo 7)
- Entiende QUÉ funcionalidad se implementó/modificó

### 2. Detecta el tipo de cambio:
- Nueva funcionalidad (ej: pasarela de pagos, modo oscuro)
- Optimización (ej: índices, queries, performance)
- Bug fix (ej: corrige error en checkout)
- Ajustes de UI/UX (ej: mejora estilos, responsive)
- Refactoring (ej: reorganiza código)
- Configuración (ej: ajusta variables de entorno)

### 3. Genera DOS versiones del mensaje:

**VERSIÓN CORTA:**
- 1 línea
- Máximo 72 caracteres
- Formato: "Se implementa/ajusta/optimiza/corrige [descripción concisa]"
- Sin detalles adicionales

**VERSIÓN COMPLETA:**
- Título (1 línea)
- 2-4 viñetas con detalles específicos
- Menciona archivos/tecnologías clave
- Máximo 8 líneas total

---

## 📤 FORMATO DE SALIDA (MINIMALISTA)

**IMPORTANTE: NO muestres mensajes informativos. Solo presenta el resultado en este formato EXACTO:**

```
corta: [Mensaje de 1 línea]

larga: [Título]
- [Detalle 1]
- [Detalle 2]
- [Detalle 3]
```

**NADA MÁS. Sin decoraciones, sin comandos git, sin explicaciones.**

---

## ✅ REGLAS DE GENERACIÓN

**Mensajes deben:**
- ✅ Usar formato: "Se implementa/ajusta/optimiza/corrige..."
- ✅ Ser específicos sobre la funcionalidad/problema
- ✅ Mencionar tecnologías relevantes (Stripe, Google Maps, etc.)
- ✅ Estar en español
- ✅ Ser técnicos pero claros

**Mensajes NO deben:**
- ❌ Usar frases genéricas ("Se implementan ajustes generales")
- ❌ Listar nombres de archivos sin contexto
- ❌ Superar 72 caracteres en el título
- ❌ Incluir información irrelevante

---

## 📋 EJEMPLOS

**VERSIÓN CORTA:**
```
Se implementa pasarela de pagos con Stripe
```

**VERSIÓN COMPLETA:**
```
Se implementa pasarela de pagos con Stripe

- Integración completa con Stripe Checkout
- Webhooks para confirmación de pagos
- Estados de pago en modelo Order
- Validación de pagos en OrderController
```

---

## ⚡ INSTRUCCIONES DE EJECUCIÓN

**Al ejecutar `/cm`:**

1. ✅ **SILENCIOSO** - NO muestres NINGÚN mensaje durante el proceso
2. ✅ **AUTOMÁTICO** - Ejecuta todo sin preguntar
3. ✅ **MINIMALISTA** - Máximo 6 líneas de salida total

**FORMATO EXACTO DE SALIDA:**
```
corta: [mensaje]

larga: [título]
- [detalle]
- [detalle]
```

**NUNCA INCLUYAS:**
- ❌ Mensajes informativos de ningún tipo
- ❌ Líneas decorativas o separadores
- ❌ Emojis o íconos
- ❌ Comandos git
- ❌ Explicaciones
- ❌ Más texto que el formato especificado

**SOLO 2 LÍNEAS DE TEXTO: "corta:" y "larga:"**

---

**Fin del comando /cm**
