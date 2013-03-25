<?

/**
 * Classe que controla as rotinas REST da API.
 */
class Api {

    /**
     * Verifica a autenticação do usuário.
     * @param  string $user_name
     * @param  string $password
     * @return false|integer
     */
    private function authentication($user_name, $password) {
        App::model('user');
        $User               = new \Model\User;
        $User->user_name    = $user_name;
        $User->password     = $password;
        $user_id            = $User->authentication();

        return $user_id;
    }


    /**
     * Retorna lista de usuários seguidos em json.
     * @return void
     */
    function followed_users() {
        App::model('user_follow');
        App::model('user');
        $json = json_encode( Array() );

            // autenticação
            //
            if ( ($user_id = $this->authentication( $_POST['user_name'], $_POST['password'] )) ) {
                $Follow  = new \Model\User_Follow;
                $rs      = $Follow->follows( $user_id );
                if ( $rs ) {

                    // ids usuários seguidos
                    $follows = Array();
                    while( ($user = $rs->fetch()) ) {
                        $follows[] = $user['user_following_id'];
                    }

                    // lista de usuários
                    $User  = new \Model\User;
                    $users = $User->usersById( $follows );
                    if ( $users ) {
                        $json = json_encode( $users->fetchAll() );
                    }
                }
            }

        header('Content-Type: application/json');
        echo $json;
    }


    /**
     * Retorna lista de posts de um usuário em json.
     * @return void
     */
    function user_posts() {
        App::model('post');
        $json = json_encode( Array() );
        if ( App::method('post') ) {

            // autenticação
            //
            if ( ($user_id = $this->authentication( $_POST['user_name'], $_POST['password'] )) ) {
                $Post   = new \Model\Post;
                $rs     = $Post->listing( $user_id );
                if ( $rs ) {
                    $posts = Array();
                    while( ($post = $rs->fetch()) ) {
                        $row['id']          = $post['post_id'];
                        $row['content']     = $post['content'];
                        $row['unixtime']    = $post['unixtime'];
                        $posts[]            = $row;
                    }
                    $json = json_encode( $posts );
                }
            }
        }

        header('Content-Type: application/json');
        echo $json;
    }

}
?>