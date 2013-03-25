<?
namespace Model;
\App::loadClass( APP_ROOT.'/app/class/', 'model' );
\App::loadClass( APP_ROOT.'/app/class/', 'db' );


/**
 * Classe de modelo Post.
 * Modelo da tabela Post.
 */
class Post extends Model {

    public $id;
    public $content;
    public $unixtime;
    public $user_id;

    /**
     * Grava as informações do objeto atual na tabela.
     * @return false|integer
     */
    function save() {
        \App::model('tag');
        \App::model('tag_post');
        $Tag     = new \Model\Tag;
        $TagPost = new \Model\Tag_Post;

        $this->content = strip_tags( $this->content );

        // extrai as hashtags
        //
        $linhas     = explode(" ", $this->content);
        $hashtags   = preg_grep("/(#\w+)/", $linhas);
        foreach ($hashtags as $value) {
            $Tag        = new \Model\Tag;
            $Tag->tag   = $value;
            $tags_id[]  = $Tag->save();
        }

        // salva post e hashtags associadas
        //
        $this->unixtime = microtime(true);
        $post_id = \Db::save( $this );
        if ( $post_id ) {
            foreach ($tags_id as $value) {
                $TagPost            = new \Model\Tag_Post;
                $TagPost->post_id   = $post_id;
                $TagPost->tag_id    = $value;
                $TagPost->save();
            }
        }

        return $post_id;
    }


    /**
     * Retorna uma lista de posts de um $user_id especificado.
     * @return PDOStatement | false
     */
    function listing( $user_id ) {
        \App::model('user_follow');
        $Follow         = new \Model\User_Follow;
        $users_id       = Array($user_id);
        $rs_seguidos    = $Follow->follows( $user_id );

        // usuários seguidos
        if ( $rs_seguidos ) {
            while( ($user_follow = $rs_seguidos->fetch()) ) {
                $users_id[] = $user_follow['user_following_id'];
            }
        }

        // posts associados aos usuários seguidos
        $sql = "
            select
                p.*,
                u.*,
                p.id as post_id
            from
                post p left join user u on p.user_id=u.id
            where
                u.id in ( ".implode(',', $users_id)." )
            order by p.unixtime desc
        ";

        $rs   = \Db::query( $sql, Array() );
        if ( $rs ) {
            return $rs;
        }

        return false;
    }
}
?>