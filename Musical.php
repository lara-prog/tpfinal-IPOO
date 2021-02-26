<?php
include_once 'BaseDatos.php';
include_once 'Funcion.php';
class Musical extends Funcion{
	private $director;
	private $cantPersonas;

	//CONSTRUCT
	/*
	*@param
	*@return Musical $unMusical
	*/
	public function __construct(){
		parent::__construct();
		$this->director = "";
		$this->cantPersonas = "";
	}

	/*
	*metodo que permite setear los atributos del funcion
	*@param int $id, string $nombre, string $direccion, float $unPrecio, int $unaHora, int $minutos, int $duracion, int $idTeatro, string $unDirector, int $cantPersonas
	*@return Teatro $unTeatro
	*/
	public function cargar($id, $unNombre, $unPrecio, $unaHora, $minutos, $unaDuracion, $idTeatro, $unDirector, $cantPersonas){
		parent::cargar($id, $unNombre, $unPrecio, $unaHora, $minutos, $unaDuracion, $idTeatro);
		$this->set_director($unDirector);
		$this->set_cant_personas($cantPersonas);
	}

	//GETS
	public function get_director(){
		return $this->director;
	}

	public function get_cant_personas(){
		return $this->cantPersonas;
	}

	/*
	*@return String $this->mensaje_operacion
	*/
	public function get_mensaje_operacion(){
		return $this->mensaje_operacion ;
	}

	//SETS
	public function set_director($unDirector){
		$this->director = $unDirector;
	}

	public function set_cant_personas($unaCantidad){
		$this->cantPersonas = $unaCantidad;
	}


	/*
	*@param String $mensaje
	*/
	public function set_mensaje_operacion($mensaje){
		$this->mensaje_operacion=$mensaje;
	}

	/*
	*este metodo aplica un incremento sobre el precio de la funcion de musical y retorna un costo
	*@return int $costoS
	*/
	public function dar_costos(){
		/*variables: int $valor, float $costo*/
		$valor = parent::dar_costos();
		$costo = $valor + ($valor * 0.12);
		return $costo;
	}

	/*
	*TOSTRING
	*@return String $s
	*/
	public function  __toString(){
		$s=parent::__toString();
		$s=$s.", Director: ".$this->get_director().", Cantidad de Personas en Escena: ".$this->get_cant_personas()."\n";
		return $s;
	}
	/*********************************************************************************************************************/
	/**
	 * Recupera los datos de un musical por nombre
	 * @param string $nombre_musical
	 * @return true en caso de encontrar los datos, false en caso contrario
	 */
	  public function Buscar($id_musical){
		$base=new BaseDatos();
		$consulta_buscar="Select * from musical where id_funcion=".$id_musical;
		$resp= false;
		//debbugin
		//echo "\n".$consulta_buscar."\n";
		if($base->Iniciar()){
		    if($base->Ejecutar($consulta_buscar)){
				if($row2=$base->Registro()){
				    parent::Buscar($id_musical);
				    $this->set_director($row2['director']);
				    $this->set_cant_personas($row2['cant_personas']);
					$resp= true;
				}
		 	}	else {
		 			$this->set_mensaje_operacion($base->getError());
			}
		 }	else {
		 		$this->set_mensaje_operacion($base->getError());
		 }
		 return $resp;
	}

	/**
	 * Lista los datos de todos los musicales que cumplen con una condicion
	 * @param String $condicion
	 * @return array $arreglo_musical
	 */
	public function listar($condicion){
	    $arreglo_musical= array();
		$base=new BaseDatos();
		$consulta_listar="Select * from musical inner join funcion on musical.id_funcion = funcion.id_funcion";
		if ($condicion!=""){
		    $consulta_listar=$consulta_listar.' where funcion.'.$condicion;//"id_teatro = '$id'"
		}
		$consulta_listar.=" order by nombre ";
		//debuggin
		//echo "\n".$consulta_listar."\n";
		if($base->Iniciar()){
		    if($base->Ejecutar($consulta_listar)){
				while($row2=$base->Registro()){
					$obj=new Musical();
					$obj->Buscar($row2['id_funcion']);
					array_push($arreglo_musical,$obj);
				}
		 	} else {
		 		$this->set_mensaje_operacion($base->getError());
			}
		 }	else {
		 		$this->set_mensaje_operacion($base->getError());
		 }
		 return $arreglo_musical;
	}

	/**
	 * Inserta una nueva instancia en la tabla musical
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;

		if(parent::insertar()){
			$id = parent::get_id();
			$director = $this->get_director();
			$cantidad = $this->get_cant_personas();
		    $consulta_insertar="INSERT INTO musical(id_funcion,director, cant_personas) VALUES ('$id', '$director', '$cantidad')";
				//debbugin
				//echo "\n".$consulta_insertar."\n";
		    if($base->Iniciar()){
		        if($base->Ejecutar($consulta_insertar)){
		            $resp=  true;
		        }	else {
		            $this->set_mensaje_operacion($base->getError());
		        }
		    } else {
		        $this->set_mensaje_operacion($base->getError());
		    }
		 }
		return $resp;
	}

	/**
	 * Modifica una instancia en la tabla musical
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function modificar(){
	    $resp =false;
	    $base=new BaseDatos();
	    if(parent::modificar()){
	    	$director = $this->get_director();
	    	$cantidad = $this->get_cant_personas();
	    	$id = parent::get_id();
	        $consulta_modifica="UPDATE musical SET director = '$director', cant_personas='$cantidad' WHERE id_funcion = '$id'";
	        //debbugin
	       // echo "\n".$consulta_modifica."\n";
	        if($base->Iniciar()){
	            if($base->Ejecutar($consulta_modifica)){
	                $resp=  true;
	            }else{
	                $this->set_mensaje_operacion($base->getError());

	            }
	        }else{
	            $this->set_mensaje_operacion($base->getError());

	        }
	    }

		return $resp;
	}
	/**
	 * Elimina una instancia en la tabla musical
	 * @param
	 * @return true en caso de eliminar los datos, false en caso contrario
	 */
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$id = parent::get_id();
				$consulta_borra="DELETE FROM musical WHERE id_funcion= '$id'";
				//debbugin
				//echo "\n".$consulta_borra."\n";
				if($base->Ejecutar($consulta_borra)){
				    if(parent::eliminar()){
				        $resp=  true;
				    }
				}else{
						$this->set_mensaje_operacion($base->getError());

				}
		}else{
				$this->set_mensaje_operacion($base->getError());

		}
		return $resp;
	}

}
?>