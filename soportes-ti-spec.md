# Sistema de Registro y Reporte de Soportes — TI

## Contexto

El área de TI atiende solicitudes de soporte por toda la universidad (aulas, áreas, equipos). El personal que da soporte: asistente de soporte al usuario, jefe de TI, y 3 becarios. No existe registro diario de esos soportes, así que no hay forma de ver quién pide más ayuda, qué problemas son más frecuentes, ni cuánto tiempo consume el soporte.

Ejemplos típicos:
- El encargado docente reporta un problema de proyección en el aula 409 (HDMI / cambio de entrada). TI va y lo resuelve.
- Piden instalar una impresora en una computadora del área de Postgrado.

## Objetivo

Sistema web **interno de registro y reporte** de soportes, usado solo por TI. Dashboard con métricas y gráficas para analizar la carga de soporte.

## Alcance

- **Un solo usuario compartido** (los dos administrativos: asistente de soporte y jefe de TI).
- Los becarios **no** son usuarios del sistema; se registran como "quién atendió".
- Registro manual.
- Dashboard con métricas filtrables por rango de fechas.

## Modelo de datos

### Soporte (registro principal)
- **Fecha** + **hora inicio** + **hora fin** → el sistema calcula la **duración**.
- **Solicitante** → persona que pide la ayuda (administrativo / docente). Catálogo que se alimenta al vuelo al registrar.
- **Ubicación** → aula o área donde se dio el soporte. **Separada del solicitante** (a veces coinciden, a veces no). Catálogo editable.
- **Pares categoría + problema** → uno o varios por soporte. Cada par es una unidad (ej. "Proyección: HDMI", "Red: sin internet").
- **Personas de TI que atendieron** → una o varias (admin y/o becarios). Un caso puede atenderlo becario + asistente, dos becarios, jefe + becario, etc.
- **Descripción** → texto libre, opcional (el detalle ya está en categoría + problema).

### Catálogos editables desde la app
- **Categorías** (padre) y sus **problemas** (hijos). Los problemas se agregan bajo cada categoría conforme aparecen casos nuevos.
- **Ubicaciones** (aulas / áreas).
- **Solicitantes** (se alimenta al vuelo al registrar un soporte y reaparece después).
- **Personal de TI** (fijo por semestre, editable; los becarios pueden cambiar cada semestre).

### Categorías de arranque sugeridas (editables)
Proyección / pantalla · Impresoras · Red / internet · Software · Cuentas / accesos · Hardware / equipos · Audio / sonido · Otros.
(Cada una acumula sus problemas específicos sobre la marcha, ej. Proyección → HDMI, cambio de entrada, cable dañado, proyector no enciende.)

## Reglas de conteo (a tener presente)

- Un soporte con **varios pares** suma en varias categorías → **nº de problemas ≥ nº de soportes**. Se reportan ambos por separado.
- Un soporte atendido por **varias personas** cuenta para cada una de ellas.

## Dashboard

- **Filtro por rango de fechas libre** (semanal, mensual, semestral).
- Métricas:
  - Nº de soportes.
  - Nº de problemas atendidos.
  - Tiempo total invertido en soporte.
  - Solicitantes que más ayuda piden.
  - Ubicaciones con más incidencias.
  - Categorías y problemas más frecuentes.
  - Soportes por persona de TI.
- Gráficas de apoyo, solo en pantalla.

## Decisiones explícitas (para no reabrir)

- Solicitante y ubicación son **campos separados** (un aula no es un área).
- Categoría y problema son un **catálogo de dos niveles**, ambos editables; los pares se registran como unidad.
- **Sin** horario laboral ni cálculo de horas fuera de horario.
- Un solo usuario compartido; becarios solo como "quién atendió".

## Stack

- **Laravel** + **FilamentPHP**.
- Panel admin con autenticación de un solo usuario.
- Catálogos y soportes como recursos CRUD.
- Dashboard con widgets de Filament.
- Solicitante con creación al vuelo (`createOptionForm`).

## Orden sugerido de implementación

1. Modelos y migraciones (soporte, catálogos, tablas pivote para pares y para personas que atienden).
2. Catálogos (categorías + problemas, ubicaciones, solicitantes, personal de TI).
3. Recurso de Soporte con el cálculo de duración y el registro de pares categoría+problema.
4. Widgets del dashboard con filtro de rango de fechas.
