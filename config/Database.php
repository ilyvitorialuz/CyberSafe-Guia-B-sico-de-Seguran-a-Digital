<?php

namespace Config;

use PDO;
use PDOException;

/**
 * Database Singleton Class
 * 
 * Gerencia a conexão com o banco de dados usando o padrão Singleton.
 * Garante que apenas uma instância do PDO seja criada durante toda a execução da aplicação.
 * 
 * Responsabilidades:
 * - Ler configurações do arquivo config.ini
 * - Estabelecer conexão com PDO
 * - Gerenciar exceções de conexão
 * - Retornar instância única do PDO
 */
class Database
{
    /**
     * @var PDO|null Instância única do PDO
     */
    private static ?PDO $instance = null;

    /**
     * Caminho do arquivo de configuração
     */
    private const CONFIG_FILE = __DIR__ . '/config.ini';

    /**
     * Construtor privado para impedir instanciação direta
     */
    private function __construct()
    {
    }

    /**
     * Clona privado para impedir clonagem da instância
     */
    private function __clone()
    {
    }

    /**
     * Obtém a instância única do PDO
     * 
     * @return PDO Instância de conexão com o banco de dados
     * @throws PDOException Se a conexão falhar
     * @throws Exception Se o arquivo de configuração não existir
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }

        return self::$instance;
    }

    /**
     * Cria a conexão com o banco de dados
     * 
     * @return PDO Conexão estabelecida
     * @throws PDOException Se falhar ao conectar
     * @throws Exception Se configuração estiver faltando
     */
    private static function createConnection(): PDO
    {
        if (!file_exists(self::CONFIG_FILE)) {
            throw new Exception(
                "Arquivo de configuração não encontrado: " . self::CONFIG_FILE . 
                "\nCopie config/config.ini.example para config/config.ini"
            );
        }

        $config = parse_ini_file(self::CONFIG_FILE, true);

        if (!isset($config['database'])) {
            throw new Exception("Seção 'database' não encontrada em config.ini");
        }

        $db = $config['database'];

        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $db['host'] ?? 'localhost',
                $db['port'] ?? '3306',
                $db['name'] ?? '',
                $db['charset'] ?? 'utf8mb4'
            );

            $pdo = new PDO(
                $dsn,
                $db['user'] ?? '',
                $db['password'] ?? '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException(
                "Erro ao conectar ao banco de dados: " . $e->getMessage(),
                (int)$e->getCode()
            );
        }
    }

    /**
     * Fecha a conexão (opcional)
     */
    public static function closeConnection(): void
    {
        self::$instance = null;
    }
}
