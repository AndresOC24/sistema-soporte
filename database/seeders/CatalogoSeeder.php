<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

/**
 * Categorias y problemas de arranque. Son solo un punto de partida: se editan
 * desde la app y los problemas se van agregando bajo cada categoria conforme
 * aparecen casos nuevos.
 *
 * Los demas catalogos (solicitantes, ubicaciones, personal de TI) se llenan con
 * datos reales desde la app, no se siembran.
 */
class CatalogoSeeder extends Seeder
{
    /**
     * @var array<string, list<string>>
     */
    private const CATEGORIAS = [
        'Proyección / pantalla' => ['HDMI', 'Cambio de entrada', 'Cable dañado', 'Proyector no enciende'],
        'Impresoras' => ['Instalar impresora', 'Atasco de papel', 'No imprime', 'Sin tóner'],
        'Red / internet' => ['Sin internet', 'Wi-Fi intermitente', 'Cable de red dañado'],
        'Software' => ['Instalación de programa', 'Actualización', 'Programa no abre'],
        'Cuentas / accesos' => ['Restablecer contraseña', 'Cuenta bloqueada', 'Alta de usuario'],
        'Hardware / equipos' => ['Equipo no enciende', 'Teclado o mouse', 'Monitor sin señal'],
        'Audio / sonido' => ['Sin audio', 'Micrófono no funciona', 'Bocinas desconectadas'],
        'Otros' => ['Otro'],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIAS as $nombreCategoria => $problemas) {
            $categoria = Categoria::firstOrCreate(['nombre' => $nombreCategoria]);

            foreach ($problemas as $nombreProblema) {
                $categoria->problemas()->firstOrCreate(['nombre' => $nombreProblema]);
            }
        }
    }
}
