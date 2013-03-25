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
            if ( ($user_id = $User->authentication()) ) {
                $_SESSION['logged'] = $user_id;
                header("Location: ".App::url('user/home'));
            } else {
                $_SESSION['logged'] = false;
                App::template( "msg.html", Array('msg' => "Login/senha incorretos ou não existentes.") );
            }
        }

    }


    /**
     * Desconecta usuário.
     * @return void
     */
    function logout() {
        $_SESSION['logged'] = false;
        header("Location: ".App::url(''));
    }

    /**
     * Página inicial do usuário logado.
     * @return void
     */
    function home() {
        $data = Array();
        if ( !App::logged() ) {
            App::redirect('');
        }

        App::model('post');
        $Post           = new \Model\Post;
        $data['list']['posts']  = $Post->listing( $_SESSION['logged'] );
        $data['list']['posts_type'] = 'recentes';
        App::template( "home.html", $data );
    }

    /**
     * Salva Postagem do usuário
     * @return void
     */
    function add() {
        if ( !App::logged() ) {
            App::redirect('');
        }

        // grava post
        //
        if ( App::method('post') ) {
            $User->fill( $_POST['user'] );
            if ( ($user_id = $User->authentication()) ) {
                $_SESSION['logged'] = $user_id;
                App:redirect('user/home');
            } else {
                $_SESSION['logged'] = false;
                App::template( "msg.html", Array('msg' => "Login/senha incorretos ou não existentes.") );
            }
        }
    }


    /**
     * Visualiza uma lista de usuários.
     * @return void
     */
    function users()    {
        if ( !App::logged() ) {
            App::redirect('');
        }

        App::model('user');
        $User   = new \Model\User;
        $rs     = $User->users();
        if ( $rs ) {
            $data['rs'] = $rs;
            App::template('users.html', $data);
        }
    }


    /**
     * Segue um usuário.
     * @return void
     */
    function follow() {
        if ( !App::logged() ) {
            App::redirect('');
        }

        // grava o usuário seguido
        //
        if ( App::method('post') ) {
            App::model('user_follow');
            $Follow = new \Model\User_Follow;
            $Follow->user_id = $_SESSION['logged'];
            $Follow->user_following_id = key($_POST['user']);
            if ( $Follow->save() ) {
                App::redirect('user/home');
            } else {
                App::template( "msg.html", Array('msg' => "Ocorreu um problema seguindo usuário.") );
            }
        }

    }

}
?>