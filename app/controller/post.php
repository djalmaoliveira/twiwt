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
        if ( !App::logged() ) {
            App::redirect('');
        }

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


    /**
     * Visualiza posts com uma determinada hashtag.
     * @return void
     */
    function hashtag() {
        if ( !App::logged() ) {
            App::redirect('');
        }

        App::model('tag_post');
        $TagPost        = new \Model\Tag_Post;
        $data['list']['posts']  = $TagPost->hashtag_posts( urldecode($_GET['q']) );
        $data['list']['posts_type'] = "por hashtag ".urldecode($_GET['q']);
        App::template('home.html', $data);
    }

}
?>