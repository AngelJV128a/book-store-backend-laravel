<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Ficción', 'description' => 'Historias imaginarias o inventadas.'],
            ['name' => 'No Ficción', 'description' => 'Basado en hechos reales o información verídica.'],
            ['name' => 'Ciencia Ficción', 'description' => 'Explora avances tecnológicos y mundos futuristas.'],
            ['name' => 'Fantasía', 'description' => 'Mundos mágicos, criaturas míticas y poderes sobrenaturales.'],
            ['name' => 'Misterio', 'description' => 'Historias centradas en resolver crímenes o enigmas.'],
            ['name' => 'Romance', 'description' => 'Narrativas centradas en relaciones amorosas.'],
            ['name' => 'Biografía', 'description' => 'Relato de la vida de una persona.'],
            ['name' => 'Historia', 'description' => 'Estudios o relatos de eventos del pasado.'],
            ['name' => 'Autoayuda', 'description' => 'Consejos y estrategias para mejorar aspectos personales.'],
            ['name' => 'Poesía', 'description' => 'Obras literarias escritas en verso.'],
            ['name' => 'Infantil', 'description' => 'Libros dirigidos a niños y niñas.'],
            ['name' => 'Juvenil', 'description' => 'Literatura pensada para adolescentes.'],
            ['name' => 'Terror', 'description' => 'Historias diseñadas para provocar miedo o suspenso.'],
            ['name' => 'Aventura', 'description' => 'Relatos de exploración, desafíos y descubrimientos.'],
            ['name' => 'Educativo', 'description' => 'Materiales diseñados para la enseñanza y aprendizaje.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
