---
paths:
  - phpunit.xml
  - phpstan.neon
---

# General

## Los tests corren sobre MySQL, no sqlite
Esta maquina no tiene la extension pdo_sqlite (solo pdo_mysql), asi que el `DB_CONNECTION=sqlite` / `:memory:` que trae Laravel por defecto falla con "could not find driver". phpunit.xml apunta a mysql con la base `db_sistema_soporte_test`, que debe existir. Si en otro entorno prefieres sqlite, instala pdo_sqlite primero.

## PHPStan: memoria y caché rancia de Larastan
Corre siempre `vendor/bin/phpstan analyse --memory-limit=1G`: con el limite por defecto de 128M revienta a mitad del analisis.

Si falla con `Undefined constant "Larastan\Larastan\LARAVEL_VERSION"`, es la cache de resultados rancia despues de editar archivos, no un error real: `vendor/bin/phpstan clear-result-cache` y repite.

Nota de tipos: `Builder<TModel>` es invariante en Larastan. Para helpers que filtran consultas de cualquier modelo (como el trait UsaRangoDeFechas), anota `@template TModel of Model` con `@param Builder<TModel>` y `@return Builder<TModel>`; no devuelvas Closure con el generico fijado a Model porque no encaja en `where()`.
