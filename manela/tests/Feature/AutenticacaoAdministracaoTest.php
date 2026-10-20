<?php

namespace Tests\Feature;

use App\Models\DocesprodutosModel;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\PersonalAccessToken;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AutenticacaoAdministracaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_emits_a_working_sanctum_token_and_hides_password(): void
    {
        $usuario = Usuario::factory()->create();
        $response = $this->postJson('/api/login', ['email' => $usuario->email, 'senha' => 'senha-teste']);
        $response->assertOk()->assertJsonPath('token_type', 'Bearer')->assertJsonMissingPath('usuario.senha');
        $token = $response->json('token');
        $this->assertNotNull(PersonalAccessToken::findToken($token));
        $this->api('GET', '/api/user', [], $token)->assertOk()->assertJsonPath('id', $usuario->id)->assertJsonMissingPath('senha');
        $this->assertDatabaseCount('token_usuario', 0);
    }

    public function test_invalid_credentials_and_excessive_attempts_are_rejected(): void
    {
        $usuario = Usuario::factory()->create();
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', ['email' => $usuario->email, 'senha' => 'errada'])->assertUnauthorized();
        }
        $this->postJson('/api/login', ['email' => $usuario->email, 'senha' => 'errada'])->assertStatus(429);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_expired_tokens_and_logout_are_enforced(): void
    {
        $usuario = Usuario::factory()->create();
        $expirado = $usuario->createToken('expirado', ['*'], now()->subMinute())->plainTextToken;
        $this->api('GET', '/api/user', [], $expirado)->assertUnauthorized();
        $token = $usuario->createToken('ativo', ['*'], now()->addDays(2))->plainTextToken;
        $this->api('POST', '/api/logout', [], $token)->assertOk();
        $this->api('GET', '/api/user', [], $token)->assertUnauthorized();
    }

    public function test_new_login_revokes_previous_token_and_expires_after_two_days(): void
    {
        $usuario = Usuario::factory()->create();
        $anterior = $usuario->createToken('anterior')->plainTextToken;
        $response = $this->postJson('/api/login', ['email' => $usuario->email, 'senha' => 'senha-teste'])->assertOk();
        $this->api('GET', '/api/user', [], $anterior)->assertUnauthorized();
        $token = $response->json('token');
        $this->api('GET', '/api/user', [], $token)->assertOk();
        $this->travel(2)->days();
        $this->travel(1)->seconds();
        $this->api('GET', '/api/user', [], $token)->assertUnauthorized();
    }

    #[DataProvider('rotasAdministrativas')]
    public function test_management_requires_an_authenticated_administrator(string $method, string $uri): void
    {
        $this->api($method, $uri)->assertUnauthorized();
        $usuario = Usuario::factory()->create();
        $token = $usuario->createToken('usuario')->plainTextToken;
        $this->api($method, $uri, ['is_admin' => true, 'id_usuario' => $usuario->id], $token)->assertForbidden();
        $this->assertDatabaseCount('doces', 0);
        $this->assertDatabaseCount('usuario', 1);
    }

    public static function rotasAdministrativas(): array
    {
        return [
            ['POST', '/api/cadastro_usuario'],
            ['POST', '/api/cadastro_doces'],
            ['GET', '/api/listar_doces'],
            ['PUT', '/api/atualizar_doces/1'],
            ['DELETE', '/api/excluir_doces/1'],
            ['GET', '/api/buscar_doces/1'],
        ];
    }

    public function test_admin_can_create_users_without_granting_admin_from_request(): void
    {
        $admin = Usuario::factory()->administrador()->create();
        $token = $admin->createToken('admin')->plainTextToken;
        $dados = [
            'nome' => 'Usuario',
            'email' => 'usuario@example.com',
            'senha' => 'senha-teste',
            'cpf' => '12345678901',
            'data_nascimento' => '2000-01-01',
            'is_admin' => true,
        ];
        $this->api('POST', '/api/cadastro_usuario', $dados, $token)->assertCreated();
        $this->assertDatabaseHas('usuario', ['email' => $dados['email'], 'is_admin' => false, 'senha' => md5('senha-teste')]);
        $this->api('POST', '/api/cadastro_usuario', $dados, $token)->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_admin_can_manage_doces(): void
    {
        $admin = Usuario::factory()->administrador()->create();
        $token = $admin->createToken('admin')->plainTextToken;
        $dados = DocesprodutosModel::factory()->make()->toArray();
        $this->api('POST', '/api/cadastro_doces', $dados, $token)->assertCreated();
        $doce = DocesprodutosModel::firstOrFail();
        $this->api('PUT', '/api/atualizar_doces/'.$doce->id, [...$dados, 'nome' => 'Brigadeiro novo'], $token)->assertOk();
        $this->api('GET', '/api/buscar_doces/'.$doce->id, [], $token)->assertOk()->assertJsonPath('nome', 'Brigadeiro novo');
        $this->api('GET', '/api/listar_doces', [], $token)->assertOk()->assertJsonCount(1);
        $this->getJson('/api/cardapio')->assertOk()->assertJsonCount(1)->assertJsonPath('0.nome', 'Brigadeiro novo');
        $this->api('DELETE', '/api/excluir_doces/'.$doce->id, [], $token)->assertOk();
        $this->assertDatabaseCount('doces', 0);
    }

    public function test_admin_can_upload_and_delete_product_image(): void
    {
        Storage::fake('public');
        $admin = Usuario::factory()->administrador()->create();
        $token = $admin->createToken('admin')->plainTextToken;
        $dados = DocesprodutosModel::factory()->make()->toArray();
        $imagem = UploadedFile::fake()->image('bolo.jpg', 800, 600);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/cadastro_doces', [...$dados, 'imagem_upload' => $imagem])
            ->assertCreated();

        $caminho = $response->json('doce.imagem');
        $this->assertStringStartsWith('storage/produtos/', $caminho);
        Storage::disk('public')->assertExists(substr($caminho, strlen('storage/')));

        $doce = DocesprodutosModel::firstOrFail();
        $this->api('DELETE', '/api/excluir_doces/'.$doce->id, [], $token)->assertOk();
        Storage::disk('public')->assertMissing(substr($caminho, strlen('storage/')));
    }

    public function test_admin_can_save_a_web_image_url(): void
    {
        $admin = Usuario::factory()->administrador()->create();
        $token = $admin->createToken('admin')->plainTextToken;
        $dados = DocesprodutosModel::factory()->make()->toArray();
        $url = 'https://images.example.com/bolo.jpg';

        $this->api('POST', '/api/cadastro_doces', [...$dados, 'imagem' => $url], $token)
            ->assertCreated()
            ->assertJsonPath('doce.imagem', $url);

        $this->assertDatabaseHas('doces', ['imagem' => $url]);
    }

    public function test_administrator_can_be_created_from_terminal(): void
    {
        $this->artisan('admin:criar')
            ->expectsQuestion('Nome', 'Administrador')
            ->expectsQuestion('Email', 'admin@example.com')
            ->expectsQuestion('Senha (mínimo de 6 caracteres)', 'senha-teste')
            ->expectsQuestion('Data de nascimento (AAAA-MM-DD)', '2000-01-01')
            ->expectsQuestion('CPF (11 dígitos)', '12345678901')
            ->expectsOutput('Administrador criado com sucesso.')
            ->assertSuccessful();
        $this->assertDatabaseHas('usuario', ['email' => 'admin@example.com', 'is_admin' => true]);
    }

    private function api(string $method, string $uri, array $dados = [], ?string $token = null): TestResponse
    {
        $this->app['auth']->forgetGuards();

        return $this->json($method, $uri, $dados, $token ? ['Authorization' => 'Bearer '.$token] : []);
    }
}
