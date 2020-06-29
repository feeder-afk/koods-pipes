<?php
namespace koods\Pipes;

class Pipe{
	public $name;
	private $mixes;
	private $count;

	public function __construct($name){
		$this->name = $name;
		$this->mixes = [];
		$this->count = 0;
	}

	/**
	 * 
	 * Agrega informacion al flujo de la tuberia en el orden especificado
	 * 
	 * @param mixed $mixed Informacion de flujo
	 * @param int $ord Orden (la primera posicion es 1)
	 */
	public function add($mixed, $ord = false){
		if( $ord ){
			if( $ord < 1 ) $ord = 1;
			array_splice( $this->mixes, $ord - 1, 0, $mixed );		
		}else
			$this->mixes[$this->count] = $mixed;
		$this->count++;
	}

	private function echo($args){
		$return = '';
		if( !empty( $this->mixes ) ){

			foreach( $this->mixes as $mixed ){				
				if( is_callable( $mixed ) ){
					$ret = call_user_func_array($mixed, $args);
					$return .= ( !empty( $ret ) )?$ret:'';
				}else{
					$return .= $mixed;
				}

			}
		}
		return $return;
	}

	public function __toString(){
		return $this->echo('');
	}

	public function __invoke($args){
		return $this->echo($args);
	}
}

/**
 * Clase que maneja las tuberias de flujo de impresion
 * para utilizarse en cualquier punto del programa
 * 
 *  
 * @author muutus
 * @author http://muutus.cl
 */
class Pipes{
	private static $pipes = [];
	
	/**
	 * Agrega tuberias,funciones o strings al flujo impresion de la tuberia pasada como parametro
	 * de no existir la tuberia esta es creada
	 * 
	 * @param string $name Nombre de la tubería
	 * @param mixed|callable funcion o información
	 * @param number $ord [optional]
	 * orden en que se guardara en el flujo
	 * el primer flujo de la tuberia permanecera primero a menos que se agregue
	 * uno en esa posicion.  
	 */
	static public function add($pipe, $mixed = '', $ord = false){
		if( is_a( $pipe, 'koods\Pipes\Pipe' ) ){
			self::$pipes[ $pipe->name ] = $pipe;
			return;
		}

		if( empty( self::$pipes[$pipe] ) ){
			$p = new Pipe($pipe);
			$p->add( $mixed, $ord );
			self::$pipes[$pipe] = $p;
		}else{
			self::$pipes[$pipe]->add($mixed, $ord);
		}
	}
	
		/**
	 * Abre el flujo de la tuberia indicada
	 * si no existe no hace nada
	 * 
	 * @param string $name
	 * @param mixed $params Lista de parametros para pasarle a la tuberia de flujo
	 */
	static public function flow($name, $params):string{
		$pipes = self::$pipes;
		
		if( empty( self::$pipes ) )
			return '';
		if( empty( self::$pipes[$name] ) )
			return '';
			
		$args = func_get_args();
		array_shift( $args );

		$pipe = $pipes[$name];

		return $pipe($args);
	}
	
	/**
	 * Devuelve la lista de tuberias
	 * 
	 * @return mixed
	 */
	static public function get(){		
		return array_keys( self::$pipes );
	}	
}