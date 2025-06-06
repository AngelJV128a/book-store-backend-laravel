<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            ['name' => 'Gabriel', 'last_name' => 'García Márquez', 'nationality' => 'Colombiana'],
            ['name' => 'Jorge Luis', 'last_name' => 'Borges', 'nationality' => 'Argentina'],
            ['name' => 'Isabel', 'last_name' => 'Allende', 'nationality' => 'Chilena'],
            ['name' => 'Mario', 'last_name' => 'Vargas Llosa', 'nationality' => 'Peruana'],
            ['name' => 'Octavio', 'last_name' => 'Paz', 'nationality' => 'Mexicana'],
            ['name' => 'Pablo', 'last_name' => 'Neruda', 'nationality' => 'Chilena'],
            ['name' => 'Carlos', 'last_name' => 'Fuentes', 'nationality' => 'Mexicana'],
            ['name' => 'Laura', 'last_name' => 'Esquivel', 'nationality' => 'Mexicana'],
            ['name' => 'Juan', 'last_name' => 'Rulfo', 'nationality' => 'Mexicana'],
            ['name' => 'Eduardo', 'last_name' => 'Galeano', 'nationality' => 'Uruguaya'],
            ['name' => 'Claribel', 'last_name' => 'Alegría', 'nationality' => 'Nicaragüense'],
            ['name' => 'Rubén', 'last_name' => 'Darío', 'nationality' => 'Nicaragüense'],
            ['name' => 'Julia', 'last_name' => 'de Burgos', 'nationality' => 'Puertorriqueña'],
            ['name' => 'Horacio', 'last_name' => 'Quiroga', 'nationality' => 'Uruguaya'],
            ['name' => 'Alfonsina', 'last_name' => 'Storni', 'nationality' => 'Argentina'],
        ];

        foreach ($authors as $author) {
            DB::table('authors')->insert([
                'name' => $author['name'],
                'last_name' => $author['last_name'],
                'nationality' => $author['nationality'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
