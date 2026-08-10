<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    public function run(): void
    {
        $mesas = [
            ['numero' => '1',  'x' => 63.5, 'y' => 53.0],
            ['numero' => '2',  'x' => 60.0, 'y' => 61.0],
            ['numero' => '3',  'x' => 56.5, 'y' => 68.5],
            ['numero' => '4',  'x' => 68.5, 'y' => 63.5],
            ['numero' => '5',  'x' => 64.5, 'y' => 71.5],
            ['numero' => '6',  'x' => 60.5, 'y' => 80.0],
            ['numero' => '7',  'x' => 50.5, 'y' => 76.0],
            ['numero' => '8',  'x' => 44.5, 'y' => 76.5],
            ['numero' => '9',  'x' => 38.5, 'y' => 76.5],
            ['numero' => '10', 'x' => 32.5, 'y' => 74.0],
            ['numero' => '11', 'x' => 28.0, 'y' => 66.5],
            ['numero' => '12', 'x' => 24.5, 'y' => 81.5],
            ['numero' => '13', 'x' => 20.5, 'y' => 73.0],
            ['numero' => '14', 'x' => 24.0, 'y' => 58.5],
            ['numero' => '15', 'x' => 18.0, 'y' => 63.5],
            ['numero' => '16', 'x' => 23.5, 'y' => 48.0],
            ['numero' => '17', 'x' => 16.5, 'y' => 52.5],
            ['numero' => '18', 'x' => 23.5, 'y' => 38.5],
            ['numero' => '19', 'x' => 15.5, 'y' => 41.5],
            ['numero' => '20', 'x' => 16.5, 'y' => 31.0],
            ['numero' => '21', 'x' => 24.5, 'y' => 26.5],
            ['numero' => '22', 'x' => 18.5, 'y' => 21.0],
            ['numero' => '23', 'x' => 29.0, 'y' => 18.5],
            ['numero' => '24', 'x' => 21.5, 'y' => 12.0],
            ['numero' => '25', 'x' => 33.0, 'y' => 12.5],
        ];

        foreach ($mesas as $mesa) {
            Mesa::updateOrCreate(
                ['numero' => $mesa['numero']],
                [
                    'estado'   => 'disponible',
                    'x'        => $mesa['x'],
                    'y'        => $mesa['y'],
                    'rotacion' => 0,
                ]
            );
        }
    }
}
