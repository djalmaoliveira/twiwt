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
     * @return boolean
     */
    function save() {
        $this->unixtime = microtime(true);
        return \Db::save( $this );
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
                u.*
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