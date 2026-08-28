<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GrabacionCanal;

class GrabacionCanalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $canales = [
            ['nombre' => 'Cámara 1 - SDI 1', 'canal_index' => 0, 'estado' => 'detenido', 'resolucion' => '1080p5994'],
            ['nombre' => 'Cámara 2 - SDI 2', 'canal_index' => 1, 'estado' => 'detenido', 'resolucion' => '1080p5994'],
            ['nombre' => 'Cámara 3 - SDI 3', 'canal_index' => 2, 'estado' => 'detenido', 'resolucion' => '1080p5994'],
            ['nombre' => 'Cámara 4 - SDI 4', 'canal_index' => 3, 'estado' => 'detenido', 'resolucion' => '1080p5994'],
        ];

        foreach ($canales as $canal) {
            GrabacionCanal::updateOrCreate(
                ['canal_index' => $canal['canal_index']],
                $canal
            );
        }
    }
}
