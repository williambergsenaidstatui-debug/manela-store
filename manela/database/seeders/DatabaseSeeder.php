<?php

namespace Database\Seeders;

use App\Models\DocesprodutosModel;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Usuario::updateOrCreate(
            ['email' => 'admin@verify.com'],
            [
                'nome' => 'Administrador',
                'telefone' => '(15) 99144-4740',
                'senha' => md5('123456'),
                'cpf' => '00000000000',
                'data_nascimento' => '2000-01-01',
                'is_admin' => true,
            ]
        );

        foreach ([
            [
                'nome' => 'Bolo de Morango',
                'categoria' => 'Bolos',
                'preco' => 89.90,
                'sobre' => 'Massa fofinha com creme e morangos frescos.',
                'descricao' => 'Bolo artesanal de morango preparado sob encomenda.',
                'imagem' => 'assets/images/bolo-morango.jpg',
                'id_produto' => 0,
                'peso' => 0,
            ],
            [
                'nome' => 'Brigadeiro Gourmet',
                'categoria' => 'Doces',
                'preco' => 4.50,
                'sobre' => 'Brigadeiro cremoso com chocolate de qualidade.',
                'descricao' => 'Ideal para festas, lembrancinhas e sobremesas.',
                'imagem' => 'assets/images/brigadeiros.jpg',
                'id_produto' => 0,
                'peso' => 0,
            ],
            [
                'nome' => 'Copo Chocolate com Morango',
                'categoria' => 'Sobremesas',
                'preco' => 18.00,
                'sobre' => 'Camadas de creme, chocolate e morango.',
                'descricao' => 'Sobremesa individual montada no copo.',
                'imagem' => 'assets/images/copos-chocolate-morango.jpg',
                'id_produto' => 0,
                'peso' => 0,
            ],
            [
                'nome' => 'Bolo Personalizado',
                'categoria' => 'Bolos personalizados',
                'preco' => 120.00,
                'sobre' => 'Bolo feito sob medida para sua comemoracao.',
                'descricao' => 'Personalizacao conforme tema, sabor e tamanho combinados.',
                'imagem' => 'assets/images/bolo-de-aniversario.png',
                'id_produto' => 0,
                'peso' => 0,
            ],
        ] as $produto) {
            DocesprodutosModel::updateOrCreate(
                ['nome' => $produto['nome']],
                $produto
            );
        }
    }
}
