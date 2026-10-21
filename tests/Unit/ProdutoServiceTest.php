<?php

namespace Tests\Unit;

use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProdutoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_cria_produto_se_valor_nao_segue_regra_de_negocio(): void
    {
        $service = new ProdutoService();

        $this->expectException(ValidationException::class);

        $service->create([
            'nome' => 'Lajota',
            'preco_custo' => 100,
            'preco' => 105,
        ]);
    }

    public function test_atualiza_produto_com_sucesso(): void
    {
        $service = new ProdutoService();

        $produto = Produto::create([
            'nome' => 'Cimento',
            'preco_custo' => 100,
            'preco' => 120,
        ]);

        $produtoAtualizado = $service->update($produto, [
            'preco' => 130,
        ]);

        $this->assertEquals(130, $produtoAtualizado->preco);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'preco' => 130,
        ]);
    }

    public function test_nao_atualiza_produto_se_preco_nao_seguir_regra_de_negocio(): void
    {
        $service = new ProdutoService();

        $produto = Produto::create([
            'nome' => 'Areia',
            'preco_custo' => 100,
            'preco' => 120,
        ]);

        $this->expectException(ValidationException::class);

        $service->update($produto, [
            'preco' => 109,
        ]);
    }
}
