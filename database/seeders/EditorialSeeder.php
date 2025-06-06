<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EditorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $editorials = [
            ['name' => 'Alfaguara', 'country' => 'España', 'website' => 'https://www.alfaguara.com'],
            ['name' => 'Planeta', 'country' => 'España', 'website' => 'https://www.planetadelibros.com'],
            ['name' => 'Anagrama', 'country' => 'España', 'website' => 'https://www.anagrama-ed.es'],
            ['name' => 'Sudamericana', 'country' => 'Argentina', 'website' => 'https://www.penguinlibros.com/ar'],
            ['name' => 'Fondo de Cultura Económica', 'country' => 'México', 'website' => 'https://www.fondodeculturaeconomica.com'],
            ['name' => 'Siglo XXI Editores', 'country' => 'México', 'website' => 'https://www.sigloxxieditores.com.mx'],
            ['name' => 'Tusquets', 'country' => 'España', 'website' => 'https://www.tusquetseditores.com'],
            ['name' => 'Lumen', 'country' => 'España', 'website' => 'https://www.penguinlibros.com/es'],
            ['name' => 'Debolsillo', 'country' => 'España', 'website' => 'https://www.megustaleer.com'],
            ['name' => 'Editorial Norma', 'country' => 'Colombia', 'website' => 'https://www.norma.com'],
            ['name' => 'Seix Barral', 'country' => 'España', 'website' => 'https://www.planetadelibros.com'],
            ['name' => 'Editorial Universitaria', 'country' => 'Chile', 'website' => 'https://editorial.uchile.cl'],
            ['name' => 'Random House', 'country' => 'Estados Unidos', 'website' => 'https://www.randomhousebooks.com'],
            ['name' => 'Ediciones B', 'country' => 'España', 'website' => 'https://www.edicionesb.com'],
            ['name' => 'Editorial EDAF', 'country' => 'España', 'website' => 'https://www.edaf.net'],
        ];

        foreach ($editorials as $editorial) {
            DB::table('editorials')->insert([
                'name' => $editorial['name'],
                'country' => $editorial['country'],
                'website' => $editorial['website'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
