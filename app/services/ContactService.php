<?php



class ContactService
{
    private $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    public function sendMessage($name, $email, $message, $category = 'Geral')
    {
        if (empty($name) || empty($email) || empty($message)) {
            throw new BusinessRuleException("Preencha todos os campos obrigatórios.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BusinessRuleException("E-mail inválido.");
        }

        return $this->repository->save([
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'category' => $category
        ]);
    }

    public function getAllMessages()
    {
        return $this->repository->findAll();
    }

    public function deleteMessage($id)
    {
        return $this->repository->delete($id);
    }
}
