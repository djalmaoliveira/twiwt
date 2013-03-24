<?
namespace Model;
\App::loadClass( APP_ROOT.'/app/class/', 'model' );
\App::loadClass( APP_ROOT.'/app/class/', 'db' );

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


        return \Db::save( $this );
    }


    /**
     * Verifica se user/password do objeto atual existe na base de dados
     * @return boolean
     */
    function autenticar() {

        // procura usuário
        //
        $sql = 'select * from user where user_name=:user and password=:password';
        $q = \Db::query( $sql, Array(':user' => $this->user_name, ':password' => $this->password) );
        if ( $q ) {
            $user = $q->fetch(  );

            // é o mesmo usuário
            //
            if ( $user['user_name'] == $this->user_name and $user['password'] == $this->password ) {
                return true;
            }
        }

        return false;
    }

}
?>