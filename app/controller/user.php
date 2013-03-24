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

        // visualiza o formulário
        //
        if ( App::method('get') ) {
            $data['model']['user_name'] = '';
            App::template( "register.html", $data );
        }

        // efetua a gravação do novo usuário
        //
        if ( App::method('post') ) {
            App::model('user');
            $User = new \Model\User;
            $User->fill( $_POST['user'] );
            if ( $User->save( $User ) ) {
                echo "ok";
            } else {
                echo "erro";
            }
        }
    }

    function login() {

    }

}
?>