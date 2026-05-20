<?php



class AuthController
{
    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $userId = $this->service->register(
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['password'] ?? ''
            );
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuário registrado com sucesso!',
                'data' => ['id' => $userId]
            ]);
        } catch (BusinessRuleException $e) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro interno do servidor.']);
        }
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $user = $this->service->login(
                $data['email'] ?? '',
                $data['password'] ?? ''
            );

            // In a real app, we would start a session or generate a JWT here
            session_start();
            $_SESSION['user'] = $user;

            echo json_encode([
                'status' => 'success',
                'message' => 'Login realizado com sucesso!',
                'data' => $user
            ]);
        } catch (BusinessRuleException $e) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro interno do servidor.']);
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Sessão encerrada.']);
    }
}
