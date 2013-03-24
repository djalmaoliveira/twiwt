<?
namespace Model;
\App::loadClass( APP_ROOT.'/app/class/', 'model' );
\App::loadClass( APP_ROOT.'/app/class/', 'db' );

/**
 * Classe de modelo UserFollow.
 * Modelo da tabela user_follow.
 */
class User_Follow extends Model {

    public $id;
    public $user_id;
    public $user_following_id;


    /**
     * Grava as informações do objeto atual na tabela.
     * @return boolean
     */
    function save() {
        if ( $this->following($this->user_id, $this->user_following_id ) ) {
            return true;
        }

        return \Db::save( $this );
    }


    /**
     * Verifica se $user_id já está seguindo $user_following_id especificados.
     * @param  integer $user_id
     * @param  integer $user_following_id
     * @return void
     */
    function following($user_id, $user_following_id) {
        $sql = "select * from user_follow where user_id=:user_id and user_following_id=:user_following_id";
        $q = \Db::query( $sql, Array(':user_id'=> $user_id, ':user_following_id' => $user_following_id) );
        if ( $q and $q->fetch() ) {
            return true;
        }
        return false;
    }


}
?>