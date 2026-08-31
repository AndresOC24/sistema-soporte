---
paths:
  - app/Models/User.php
  - 'app/Models/**'
---

# Models

## User debe implementar FilamentUser (APP_ENV no es "local")
Filament\Http\Middleware\Authenticate lanza 403 si el modelo autenticado no implementa FilamentUser y `config('app.env') !== 'local'`. Este proyecto usa APP_ENV=dev, asi que el panel daba 403 tras el login hasta implementar `canAccessPanel()` en User. No cambies APP_ENV a "local" para esquivarlo: en produccion volveria a fallar. Mantener la implementacion de FilamentUser.

## Modelos en español: fija $table y los catálogos no se borran
El pluralizador de Laravel es ingles y genera `ubicacions` / `personal_tis`. Todos los modelos declaran su tabla con el atributo #[Table('...')]; si agregas uno nuevo, hazlo tambien (y revisa el slug del recurso Filament, que sufre lo mismo).

Las FK de los catalogos son restrictOnDelete a proposito: un solicitante, ubicacion, problema o persona de TI ya usado en un soporte no se borra, para no perder historial. Los recursos ocultan el DeleteAction en ese caso. Al personal de TI se le baja el flag `activo` en vez de borrarlo (los becarios rotan cada semestre).

`SoporteProblema` deriva `categoria_id` del problema en un hook `saving`: esta desnormalizado a proposito para reportar por categoria sin joins, y por eso no puede desincronizarse.
