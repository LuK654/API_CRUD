<?php
namespace App\Controllers;

use App\DTO\CreateUserDTO; // O nosso DTO para validar os dados de criação de utilizadores.
use App\Services\UsuarioService; // A nossa camada de serviço, que contém a lógica de negócio.
use Psr\Http\Message\ResponseInterface as Response; // Representa a resposta HTTP que vamos construir e enviar.
use Psr\Http\Message\ServerRequestInterface as Request; // Representa a requisição HTTP que recebemos do cliente.

class UsuariosController {
    // O controller tem uma única dependência: a camada de serviço.
    // Isto mantém o controller "magro" (thin), focando-se apenas no HTTP.
   // private $usuarioService;

    // O construtor implementa a Injeção de Dependência.
    // O contentor de DI (PHP-DI) que configurámos em `dependencies.php` é responsável
    // por criar uma instância de `UsuarioService` e passá-la para este construtor.
    public function __construct(private UsuarioService $usuarioService) {
       // $this->usuarioService = $usuarioService;
    }

    /**
     * Lida com a requisição GET /usuarios.
     */
    public function buscarGeral(Request $request, Response $response, array $args): Response {
        // 1. Delega a busca dos dados para a camada de serviço.
        $usuarios = $this->usuarioService->getTodos();
        
        // 2. Converte o resultado (uma lista de DTOs) para uma string JSON.
        $payload = json_encode($usuarios);
        
        // 3. Escreve o JSON no corpo do objeto de resposta.
        $response->getBody()->write($payload);
        
        // 4. Retorna a resposta, definindo o cabeçalho `Content-Type` para que o cliente saiba que é um JSON.
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Lida com a requisição GET /usuarios/{id}.
     */
    public function buscar(Request $request, Response $response, array $args): Response {
        // O Slim extrai automaticamente os parâmetros da URL (como {id}) e coloca-os no array $args.
        $id = $args['id'];
        try {
            // Delega a busca para o serviço.
            $usuario = $this->usuarioService->getPorId($id);
            $response->getBody()->write(json_encode($usuario));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // Se o serviço lançar uma exceção (ex: "Utilizador não encontrado"), nós capturamo-la.
            $payload = json_encode(['message' => $e->getMessage()]);
            $response->getBody()->write($payload);
            // Usamos o código de erro da exceção para definir o status HTTP da resposta (ex: 404).
            // O `?: 500` é um fallback para o caso de a exceção não ter um código definido.
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode() ?: 500);
        }
    }

    /**
     * Lida com a requisição POST /usuarios.
     */
    public function criar(Request $request, Response $response, array $args): Response {
        // O Slim já fez o parse do corpo JSON da requisição. Acedemos a ele com `getParsedBody()`.
        // O `(object)` garante que temos um objeto, como o nosso DTO espera.
        $data = (object)$request->getParsedBody();
        try {
            // 1. Criamos o DTO. A validação de dados obrigatórios acontece aqui dentro.
            $createUserDTO = new CreateUserDTO($data);
            
            // 2. Passamos o DTO validado para a camada de serviço.
            $resultado = $this->usuarioService->criarUsuario($createUserDTO);

            $response->getBody()->write(json_encode($resultado));
            // Retornamos um status `201 Created`, que é a resposta padrão para a criação bem-sucedida de um recurso.
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            // Captura erros tanto do DTO (dados inválidos) como do Serviço (email duplicado).
            $payload = json_encode(['message' => $e->getMessage()]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode() ?: 500);
        }
    }

    /**
     * Lida com a requisição PUT /usuarios/{id}.
     */
    public function atualizar(Request $request, Response $response, array $args): Response {
        $id = $args['id'];
        $data = (object)$request->getParsedBody();
        try {
            // (No futuro, poderíamos criar um UpdateUserDTO aqui para validar os dados de atualização).
            $resultado = $this->usuarioService->atualizarUsuario($id, $data);
            $response->getBody()->write(json_encode($resultado));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $payload = json_encode(['message' => $e->getMessage()]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode() ?: 500);
        }
    }

    /**
     * Lida com a requisição DELETE /usuarios/{id}.
     */
    public function deletar(Request $request, Response $response, array $args): Response {
        $id = $args['id'];
        try {
            $resultado = $this->usuarioService->deletarUsuario($id);
            $response->getBody()->write(json_encode($resultado));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $payload = json_encode(['message' => $e->getMessage()]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode() ?: 500);
        }
    }
}