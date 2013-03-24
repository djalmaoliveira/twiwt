<?

/**
 * Classe com algumas rotinas gerais para funcionamento da aplicação.
 */
class App {

    /**
     * Analisa a URL solicitada e executa o método correspondente.
     * Padrão de Url:
     *     /controller/method
     *     ou
     *     / (carrega arquivo index.html)
     * @return void
     */
    static function routing() {

        // separa os segmentos da URL
        $route = substr( $_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "index.php")+9 );
        list(, $controller, $action) = explode("/", $route."//");

        // chama o método do controller especificado
        if ( $controller and file_exists(APP_ROOT."/app/controller/$controller.php") ) {
            require_once APP_ROOT."/app/controller/$controller.php";
            $class = ucfirst( $controller );
            $Controller = new $class;
            if ( $action and method_exists ( $Controller, $action ) ) {
                call_user_func(array($Controller, $action));
            } else {
                if ( method_exists ( $Controller, 'index' ) ) {
                    call_user_func(array($Controller, "index"));
                }
            }
        } else {
            self::template("index.html");
        }
    }

    /**
     * Carrega um arquivo da pasta view, adicionando os arquivos de template header.html antes e footer.html depois.
     * @param  string $view_file Caminho do arquivo relativo a pasta view/
     * @param  array $data Dados necessários para construir o conteúdo do arquivo.
     * @return void
     */
    static function template( $view_file, $data=Array() ) {
        self::view('header.html', $data);
        self::view($view_file, $data);
        self::view('footer.html', $data);
    }

    /**
     * Carrega um arquivo dentro da pasta view.
     * @param  string $view_file Caminho do arquivo relativo a pasta view/
     * @param  array $data Dados necessários para construir o conteúdo do arquivo.
     * @return void
     */
    static function view( $view_file, $data=Array() ) {
        if ( file_exists( APP_ROOT."/app/view/".$view_file ) ) {
            extract($data);
            include APP_ROOT."/app/view/".$view_file;
        }
    }

    /**
     * Cria uma URL para o $path_to especificado.
     * @param  string $path_to  Caminho relativo à aplicalção da url
     * @return string
     */
    static function url( $path_to ) {
        // adiciona index.php se não existe na url base
        if ( strpos($_SERVER['REQUEST_URI'], "index.php") == false ) {
            $_SERVER['REQUEST_URI'] .= 'index.php'   ;
        }
        $path   = substr( $_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "/index.php")).'/';

        $scheme = 'http://';
        if ( isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] ) {
            $scheme = 'https://';
        }

        return $scheme.$_SERVER['SERVER_NAME'].$path.'index.php/'.$path_to;
    }

    /**
     * Retorna true se $method_name for igual ao método enviado pelo usuário
     * @param  string $method_name
     * @return boolean
     */
    static function method( $method_name ) {
        return ( strtolower($_SERVER['REQUEST_METHOD'])  == strtolower($method_name) ) ;
    }

    /**
     * Carrega uma modelo especificado.
     * @param  string $model_name Nome do modelo
     * @return void
     */
    static function model( $model_name ) {
        self::loadClass( APP_ROOT.'/app/model/', $model_name );
    }

    /**
     * Carrega uma classe através do caminho e nome especificado.
     * @param  string $file_path
     * @param  string $name
     */
    static function loadClass( $file_path, $name ) {
        if ( file_exists($file_path.$name.'.php') ) {
            require_once $file_path.$name.'.php';
        }
    }

}

?>