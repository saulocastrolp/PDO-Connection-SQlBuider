# PDO Connection and SQLBuilder #

Repósitório com conexão e SQLBuilder simples em PDO para inicio de modificações no SIG

### Repósitório para atualização de códigos do SIG ###

* Repósitorio dedicado as modificações nas consultas e regras de negocio do SIG
* 0.1
* [Repositório no Bitbucket](https://bitbucket.org/wsitebrasil/pdo-connection-sqlbuilder)
* Padronização de código com base nas PSR1, PSR2 e PSR4 favor mantê-la

### Modo de Uso ###

* Exemplo SELECT
```php
$qb = new \SIG\DB\SQLBuilder();
    $content = $qb
                ->table('table_name')
                ->fields(['*'])             // opcional: array de campos ou a emissão para * 
                ->join('expressao')         // opcional: quando necessário joins
                ->limit('qtd')              // opcional: quando necessário limitar o conteúdo
                ->orderby('expressao')      // opcional: quando necessário ordenação
                ->where('condicao')        // opcional: quando necessário uma condição       
                ->select();
```

* Exemplo INSERT
```php
$qb = new \SIG\DB\SQLBuilder();
$qb
    ->table('table')
    ->fields(['field1', 'field2', 'field3', ..., 'fileldn'])
    ->insert(['value1', 'value2', md5('value3'), ..., 'valuen']);
```

* Exemplo UPDATE
```php
$qb = new \SIG\DB\SQLBuilder();
->table('table') 
    ->fields(['field1', 'field2', 'field3', ..., 'fileldn'])
    ->where(['condicao'])
    ->update(['value1', 'value2', md5('value3'), ..., 'valuen');
```

* Exemplo DELETE
```php
$qb = new \SIG\DB\SQLBuilder();
$qb
    ->table('table')
    ->where(['condicao'])
    ->delete([valor para a condicao]);     // Informar o valor para que a condição seja aceita
```

##### Observações: #####

* Saliento que as funções básicas das quais me lembrei e que são mais usadas estão implementadas, porém podemos incrementar muita coisa no SQLBuilder ainda e toda ajuda será bem vinda!
