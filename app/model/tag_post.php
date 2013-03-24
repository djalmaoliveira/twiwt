<?
namespace Model;
\App::loadClass( APP_ROOT.'/app/class/', 'model' );
\App::loadClass( APP_ROOT.'/app/class/', 'db' );

/**
 * Classe de modelo Tag_Post.
 * Modelo da tabela tag_post.
 */
class Tag_Post extends Model {

    public $id;
    public $tag_id;
    public $post_id;


    /**
     * Grava as informações do objeto atual na tabela.
     * @return false|integer
     */
    function save() {
        return \Db::save( $this );
    }


    /**
     * Retorna lista de posts com uma determinada hashtag.
     * @param  string $hashtag
     * @return PDOStatement | false
     */
    function hashtag_posts( $hashtag ) {
        $sql = "
        select
            p.*, u.*
        from
            post p left join tag_post tp on tp.post_id=p.id
            left join tag t on t.id=tp.tag_id
            left join user u on u.id=p.user_id
        where
            t.tag=:hashtag
        order by p.unixtime desc
        ";

        $rs   = \Db::query( $sql, Array(':hashtag' => $hashtag) );
        if ( $rs ) {
            return $rs;
        }
        return false;
    }

}
?>