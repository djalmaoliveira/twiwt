<?
namespace Model;

/**
 *  Classe pai com rotinas comuns para os modelos (app/model/*).
 */
class Model {

    /**
     * Preenche o objeto atual com os dados do array especificado.
     * @param  array $data
     * @return void
     */
    function fill($data) {
        foreach ( get_object_vars($this) as $key => $value ) {
            if ( isset($data[$key]) ) {
                $this->$key = $data[$key];
            }
        }
    }
}
?>