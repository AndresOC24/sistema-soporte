---
paths:
  - 'database/migrations/**'
---

# Migrations

## duracion_minutos es columna generada, no se escribe
`soportes.duracion_minutos` es una columna STORED generada por MySQL: `(TIME_TO_SEC(hora_fin) - TIME_TO_SEC(hora_inicio)) DIV 60`. No la pongas en $fillable, ni la asignes, ni la calcules en PHP: no puede desincronizarse de las horas y el dashboard la suma directo en SQL. Si cambias a un motor sin columnas generadas hay que reemplazarla por un hook de modelo.
