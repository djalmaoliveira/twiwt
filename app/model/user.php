<?
namespace Model;
\App::loadClass( APP_ROOT.'/app/class/', 'model' );

/**
 * Classe de modelo User.
 * Modelo da tabela User.
 */
class User extends Model {

    public $id;
    public $user_name;
    public $password;

    /**
     * Grava as informações do objeto atual na tabela.
     * @return boolean
     */
    function save() {

        $this->password = sha1( $this->password );

        \App::loadClass( APP_ROOT.'/app/class/', 'db' );
        return \Db::save( $this );
    }
}
?>