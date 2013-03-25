<?

/**
 * Classe com rotinas comuns de acesso ao banco de dados MYSQL.
 */
class Db {

    /**
     * Instancia PDO.
     * @var PDO
     */
    static private $pdo;

    /**
     * Retorna instância PDO baseado nas configurações do config.php.
     * @return PDO
     */
    static function instance() {
        if ( self::$pdo ) {
            return self::$pdo;
        }

        // tenta efetuar conexão com o banco
        //
        $config = include APP_ROOT."/app/config/config.php";
        try {
            $dsn = "mysql:dbname={$config['db_name']};host={$config['host']}";
            self::$pdo = new PDO($dsn, $config['user'], $config['password']);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Verifique a conexão ao banco de dados: ' . $e->getMessage();
        }
        return self::$pdo;
    }

    /**
     * Salva informações do objeto Modelo informado.
     * @param  object $Model Instância do modelo
     * @return false|integer  Retorna ID do registro adicionado caso de sucesso, senão false
     */
    static function save( $Model ) {
        // Extrai informações do Modelo
        //
        $class      = get_class($Model);
        $namespaces = explode('\\', $class);
        end($namespaces);

        $Pdo    = self::instance();
        $table  = strtolower( current(($namespaces)) );
        $fields = get_object_vars($Model);

        // prepara dados
        //
        foreach ( $fields as $key => $value ) {
            $param          = ":".$key;
            $params[$param] = $value;
        }

        // Adiciona um novo
        //
        if ( !$fields['id'] ) {
            $sql = "insert into $table (".implode(', ', array_keys($fields) ).") values (".implode(', ', array_keys($params)).")";
        }

        if ( ($q = $Pdo->prepare($sql)) ) {
            if ( ($ret = $q->execute( $params )) !== false ) {
                return $Pdo->lastInsertId() ;
            }
        }
//        print_r($q->errorinfo());
//        print_r($Pdo->errorinfo());
        return false;

    }

    /**
     * Executa uma consulta ao banco de dados de acordo com o $sql e $params especificados.
     * @param  string $sql
     * @param  array $params
     * @return PDOStatement | false
     */
    static function query($sql, $params) {
        $Pdo    = self::instance();

        if ( ($q = $Pdo->prepare($sql)) ) {
            if ( ($ret = $q->execute( $params )) !== false ) {
                return $q;
            }
        }
        return false;
    }

}

?>