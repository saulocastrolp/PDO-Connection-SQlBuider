<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-type: application/json; charset=UTF-8');

require_once 'class/ConnectionDB.class.php';
require_once 'class/SQLBuilder.class.php';

try {
    $qb = new \SIG\DB\SQLBuilder();

    $content = $qb
                ->table('sig_conteudos')
                //->fields(['*'])
                ->limit('1')
                ->select();
} catch (Exception | PDOException $ex) {
    echo $ex->getMessage();
} finally {
    /** @noinspection PhpUndefinedConstantInspection */
    // echo (is_a($con, PDO))?'Base de dados instânciada com êxito' :'Ops! Algo de errado ocorreu!';
    echo json_encode($content);
}



