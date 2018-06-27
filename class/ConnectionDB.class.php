<?php
/**
 * Created by PhpStorm.
 * User: saulo
 * Date: 26/06/18
 * Time: 22:58
 */

namespace DB;


use Exception;
use PDO;

class ConnectionDB {

    private static $connection;
    private static $arq_cfg = 'config/config.ini';

    /**
     * Singleton: Método construtor privado para impedir classe de gerar instâncias
     *
     */
    private function __construct() {}

    private function __clone() {}

    private function __wakeup() {}

    /**
     * Método para montar a strig de conexão PDO
     * @return PDO
     * @throws Exception
     * @internal param array $dados
     */
    private static function make(): PDO {

        if(file_exists(self::$arq_cfg)) {
            $dados = parse_ini_file(self::$arq_cfg, true)['db'];
        } else {
            throw new Exception('Erro: Arquivo não encontrado');
        }

        // capturar dados
        $sgdb       = isset($dados['sgdb']) ? $dados['sgdb'] : null;
        $user       = isset($dados['user']) ? $dados['user'] : null;
        $passwd     = isset($dados['passwd']) ? $dados['passwd'] : null;
        $db         = isset($dados['db']) ? $dados['db'] : null;
        $server     = isset($dados['server']) ? $dados['server'] : null;
        $port       = isset($dados['port']) ? $dados['port'] : null;

        if(!is_null($sgdb)) {
            // selecionar db - criar string de conexão
            switch (strtoupper($sgdb)) {
                case 'MYSQL' : $port = isset($port) ? $port : 3306 ;
                    return new PDO("mysql:host={$server};port={$port};dbname={$db}", $user, $passwd);
                    break;
                case 'MSSQL' : $port = isset($port) ? $port : 1433 ;
                    return new PDO("mssql:host={$server},{$port};dbname={$db}", $user, $passwd);
                    break;
                case 'PGSQL' : $port = isset($port) ? $port : 5432 ;
                    return new PDO("pgsql:dbname={$db}; user={$user}; password={$passwd}, host={$server};port={$port}");
                    break;
                case 'SQLITE' :
                    return new PDO("sqlite:{$db}");
                    break;
                case 'OCI8' :
                    return new PDO("oci:dbname={$db}", $user, $passwd);
                    break;
                case 'FIREBIRD' :
                    return new PDO("firebird:dbname={$db}",$user, $passwd);
                    break;
                default :
                    return new PDO("mysql:host={$server};port={$port};dbname={$db}", $user, $passwd);
            }
        } else {
            throw new Exception('Erro: tipo de db de dados não informado');
        }
    }

    /**
     * Método estático para retorno da instância com conexão estabelecida
     * @return PDO
     */
    public static function getInstance(): PDO {
        if(self::$connection == null) {
            self::$connection = self::make();
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->exec("set names utf8");
        }
        return self::$connection;
    }

    /**
     * Método para encerrar conexão com a base de dados
     */
    public static function close(): void {

        if (self::$connection !== null)
            self::$connection = null;
    }

    /**
     * @param $sql
     * @return \PDOStatement
     */
    private final function statement($sql) {
        return self::getInstance()->prepare($sql);
    }

    /**
     * @param string $sql
     * @param array $values
     * @return string
     */
    protected final function executeInsert($sql, array $values) {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return self::getInstance()->lastInsertId();
        }
        return null;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    protected final function executeSelect($sql, array $values) {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    protected final function executeUpdate($sql, array $values) {
        return $this->execute($sql, $values);
    }

    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    protected final function executeDelete($sql, array $values) {
        return $this->execute($sql, $values);
    }

    /**
     * @param $sql
     * @param array $values
     * @return int|null
     */
    protected final function execute($sql, array $values) {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $statement->rowCount();
        }
        return null;
    }

}