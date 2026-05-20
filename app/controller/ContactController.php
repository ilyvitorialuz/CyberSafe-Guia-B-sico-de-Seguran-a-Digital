<?php



class ContactController
{
    private $service;

    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $contactId = $this->service->sendMessage(
                $data['nome'] ?? '', // Mapping from frontend keys
                $data['email'] ?? '',
                $data['mensagem'] ?? '',
                $data['categoria'] ?? 'Geral'
            );

            echo json_encode([
                'status' => 'success',
                'message' => 'Mensagem enviada com sucesso!',
                'data' => ['id' => $contactId]
            ]);
        } catch (BusinessRuleException $e) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro interno do servidor.']);
        }
    }

    public function index()
    {
        try {
            $messages = $this->service->getAllMessages();
            echo json_encode(['status' => 'success', 'data' => $messages]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar mensagens.']);
        }
    }

    public function delete($id)
    {
        try {
            $this->service->deleteMessage($id);
            echo json_encode(['status' => 'success', 'message' => 'Mensagem excluída.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir mensagem.']);
        }
    }
}
