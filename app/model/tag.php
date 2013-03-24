<?
namespace Model;
\App::loadClass( APP_ROOT.'/app/class/', 'model' );
\App::loadClass( APP_ROOT.'/app/class/', 'db' );

/**
 * Classe de modelo Tag.
 * Modelo da tabela tag.
 */
class Tag extends Model {

    public $id;
    public $tag;


    /**
     * Grava as informações do objeto atual na tabela.
     * @return false|integer
     */
    function save() {

        // se já existir ignora
        //
        $sql = 'select * from tag where tag=:tag';
        $q   = \Db::query( $sql, Array(':tag' => $this->tag) );
        if ( $q and ($tag = $q->fetch()) ) {
            return $tag['id'];
        }

        return \Db::save( $this );
    }
}
?>