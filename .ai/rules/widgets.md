---
paths:
  - 'app/Filament/Widgets/**'
---

# Widgets

## Reglas de conteo del dashboard
La unidad de conteo NO siempre es el soporte:
- "Problemas atendidos" cuenta filas de `soporte_problema` (un soporte con varios pares suma varias veces), asi que siempre es >= al numero de soportes.
- "Soportes por persona de TI" cuenta filas de `personal_ti_soporte`: un caso atendido entre dos suma para ambas, y la suma de la grafica supera el total de soportes. Eso es correcto, no lo "arregles".
- "Tiempo invertido" suma duracion_minutos UNA vez por soporte: no lo calcules sobre un join con pivotes o quedara inflado.
El rango de fechas vive solo en el trait UsaRangoDeFechas (acotarSoportes/acotarPares); no dupliques whereDate en los widgets.
