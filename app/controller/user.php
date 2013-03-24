<?

/**
 * Classe que controla ações executadas no Modelo User.
 */
class User {

    /**
     * Cadastra um novo usuário.
     * @return void
     */
    function register() {
        App::model('user');
        $User = new \Model\User;

        // visualiza o formulário
        //
        if ( App::method('get') ) {
            $data['model'] = get_object_vars( $User );
            App::template( "register.html", $data );
        }

        // efetua a gravação do novo usuário
        //
        if ( App::method('post') ) {
            $User->fill( $_POST['user'] );
            if ( $User->save() ) {
                App::template( "msg.html", Array('msg' => "Usuários cadastrado com sucesso.") );
            } else {
                App::template( "msg.html", Array('msg' => "Ocorreu um problema cadastrando um novo usuário.") );
            }
        }
    }

    /**
     * Autentica usuário
     * @return void
     */
    function login() {
        App::model('user');
        $User = new \Model\User;

        // visualiza o formulário
        //
        if ( App::method('get') ) {
            $data['model'] = get_object_vars( $User );
            App::template( "login.html", $data );
        }

        // autentica usuário
        //
        if ( App::method('post') ) {
            $User->fill( $_POST['user'] );
            if ( $User->autenticar() ) {
                $_SESSION['logged'] = true;
                header("Location: ".App::url(''));
            } else {
                $_SESSION['logged'] = false;
                App::template( "msg.html", Array('msg' => "Login/senha incorretos ou não existentes.") );
            }
        }

    }


    /**
     * Desconecta usuário
     * @return void
     */
    function logout() {
        $_SESSION['logged'] = false;
        header("Location: ".App::url(''));
    }

}
?>