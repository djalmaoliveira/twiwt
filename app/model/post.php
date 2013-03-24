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

}
?>