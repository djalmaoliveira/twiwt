<?

/**
 *
 * Classe de controle dos posts.
 */
class Post {

    /**
     * Salva Postagem do usuário
     * @return void
     */
    function add() {
        App::model('post');
        $Post = new \Model\Post;

        // salva post
        //
        if ( App::method('post') ) {
            $Post->fill( $_POST['post'] );
            if ( $Post->save() ) {
                App::template( "msg.html", Array('msg' => "Postagem salva com sucesso.") );
            } else {
                App::template( "msg.html", Array('msg' => "Ocorreu um problema salvando a postagem.") );
            }
        }

    }



}
?>