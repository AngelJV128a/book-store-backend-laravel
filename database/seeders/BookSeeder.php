<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            ['title' => 'La sombra del viento', 'isbn' => '9788408058027', 'price' => '19.99', 'stock' => '30', 'release_date' => '2001-04-17', 'language' => 'Español'],
            ['title' => '1984', 'isbn' => '9780451524935', 'price' => '15.50', 'stock' => '20', 'release_date' => '1949-06-08', 'language' => 'Inglés'],
            ['title' => 'Cien años de soledad', 'isbn' => '9780307474728', 'price' => '22.00', 'stock' => '40', 'release_date' => '1967-05-30', 'language' => 'Español'],
            ['title' => 'El nombre del viento', 'isbn' => '9788401352832', 'price' => '25.00', 'stock' => '15', 'release_date' => '2007-03-27', 'language' => 'Español'],
            ['title' => 'Harry Potter y la piedra filosofal', 'isbn' => '9788478884452', 'price' => '18.00', 'stock' => '50', 'release_date' => '1997-06-26', 'language' => 'Español'],
            ['title' => 'El señor de los anillos', 'isbn' => '9780618640157', 'price' => '35.00', 'stock' => '25', 'release_date' => '1954-07-29', 'language' => 'Inglés'],
            ['title' => 'Los juegos del hambre', 'isbn' => '9780439023528', 'price' => '16.75', 'stock' => '35', 'release_date' => '2008-09-14', 'language' => 'Español'],
            ['title' => 'El alquimista', 'isbn' => '9780061122415', 'price' => '14.99', 'stock' => '45', 'release_date' => '1988-04-15', 'language' => 'Español'],
            ['title' => 'Orgullo y prejuicio', 'isbn' => '9780141439518', 'price' => '12.00', 'stock' => '38', 'release_date' => '1813-01-28', 'language' => 'Inglés'],
            ['title' => 'Don Quijote de la Mancha', 'isbn' => '9780060934347', 'price' => '20.00', 'stock' => '27', 'release_date' => '1605-01-16', 'language' => 'Español'],
            ['title' => 'Drácula', 'isbn' => '9780141439846', 'price' => '13.50', 'stock' => '31', 'release_date' => '1897-05-26', 'language' => 'Inglés'],
            ['title' => 'Frankenstein', 'isbn' => '9780486282114', 'price' => '11.99', 'stock' => '29', 'release_date' => '1818-01-01', 'language' => 'Inglés'],
            ['title' => 'El principito', 'isbn' => '9780156012195', 'price' => '10.00', 'stock' => '60', 'release_date' => '1943-04-06', 'language' => 'Español'],
            ['title' => 'Crónica de una muerte anunciada', 'isbn' => '9780307387738', 'price' => '17.00', 'stock' => '34', 'release_date' => '1981-03-01', 'language' => 'Español'],
            ['title' => 'It', 'isbn' => '9781501142970', 'price' => '28.50', 'stock' => '22', 'release_date' => '1986-09-15', 'language' => 'Inglés'],
        ];

        foreach ($books as $index => $book) {
            DB::table('books')->insert([
                'id' => Str::uuid(),
                'title' => $book['title'],
                'isbn' => $book['isbn'],
                'author_id' => $index + 1,
                'editorial_id' => $index + 1,
                'category_id' => $index + 1,
                'price' => $book['price'],
                'stock' => $book['stock'],
                'release_date' => $book['release_date'],
                'language' => $book['language'],
                'image' => 'book' . ($index + 1) . '.jpg',
                'description' => 'Descripción de ejemplo para "' . $book['title'] . '".',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
