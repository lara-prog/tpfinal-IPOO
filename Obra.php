<?php
include_once 'BaseDatos.php';
include_once 'Funcion.php';

class Obra extends Funcion {
	private $autor;

	//CONSTRUCT
	/*
	*@param
	*@return Obra $unaObra
	*/
	public function __construct(){
		parent::__construct();
		$this->autor="";
	}

	/*
	*metodo que permite setear los atributos del la obra
	*@param int $id, string $nombre, string $direccion, float $unPrecio, int $unaHora, int $minutos, int $duracion, int $idTeatro, string $unAutor
	*@return Teatro $unTeatro
	*/
	public function cargar($id, $unNombre, $unPrecio, $unaHora, $minutos, $unaDuracion, $idTeatro, $unAutor) {
		parent::cargar($id, $unNombre, $unPrecio, $unaHora, $minutos, $unaDuracion, $idTeatro);
		$this->set_autor($unAutor);
	}

	//GETS
	public function get_autor(){
		return $this->autor;
	}

	/*
	*@return String $this->mensaje_operacion
	*/
	public function get_mensaje_operacion(){
		return $this->mensaje_operacion ;
	}

	//SETS
	public function set_autor($autor){
		$this->autor = $autor;
	}


	/*
	*@param String $mensaje
	*/
	public function set_mensaje_operacion($mensaje){
		$this->mensaje_operacion=$mensaje;
	}


	/*
	*este metodo aplica un incremento sobre el precio de la funcion y retorna un costo
	*@return int $costo
	*/
	public function dar_costos(){
		/*variables: int $valor, float $costo*/
		$valor = parent::dar_costos();
		$costo = $valor + ($valor * 0.45);
		return $costo;
	}

	/*
	*TOSTRING
	*@return String $s
	*/
	public function  __toString(){
		return parent::__toString().", autor: ".$this->get_autor()."\n";
	}

	/*****************************************************************************************************************/
	/**
	 * Recupera los datos de una obra por nombre
	 * @param string $nombre_obra
	 * @return true en caso de encontrar los datos, false en caso contrario
	 */
	  public function Buscar($id_obra){
		$base=new BaseDatos();
		$consulta_buscar="Select * from obra where id_funcion=".$id_obra;
		//debbugin
		//echo "\n".$consulta_buscar."\n";
		$resp= false;
		if($base->Iniciar()){
		    if($base->Ejecutar($consulta_buscar)){
				if($row2=$base->Registro()){
				    parent::Buscar($id_obra);
				    $this->set_autor($row2['autor']);
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
	 * Lista los datos de todos las obras que cumplen con una condicion
	 * @param String $condicion
	 * @return array $arreglo_obra
	 */
	public function listar($condicion){
	    $arreglo_obra= array();
		$base=new BaseDatos();
		$consulta_listar="Select * from obra inner join funcion on obra.id_funcion = funcion.id_funcion";
		if ($condicion!=""){
		    $consulta_listar=$consulta_listar.' where funcion.'.$condicion;
		}
		$consulta_listar.=" order by nombre ";
		//debuggin
		//echo "\n".$consulta_listar."\n";
		if($base->Iniciar()){
		    if($base->Ejecutar($consulta_listar)){

				while($row2=$base->Registro()){
					$obj=new Obra();
					$obj->Buscar($row2['id_funcion']);
					array_push($arreglo_obra,$obj);
				}
		 	}	else {
		 			$this->set_mensaje_operacion($base->getError());
			}
		 }	else {
		 		$this->set_mensaje_operacion($base->getError());
		 }
		 return $arreglo_obra;
	}

	/**
	 * Inserta una nueva instancia en la tabla obra
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		if(parent::insertar()){
			$id = parent::get_id();
			$autor = $this->get_autor();
		    $consulta_insertar="INSERT INTO obra(id_funcion,autor)	VALUES ('$id', '$autor')";
		    //debuggin
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
	 * Modifica una instancia en la tabla obra
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function modificar(){
	    $resp =false;
	    $base=new BaseDatos();
	    if(parent::modificar()){
	    	$autor = $this->get_autor();
	    	$id = parent::get_id();
	        $consulta_modifica="UPDATE obra SET autor = '$autor' WHERE id_funcion='$id'";
	        //debuggin
	        //echo "\n".$consulta_modifica."\n";
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
	 * Elimina una instancia en la tabla obra
	 * @param
	 * @return true en caso de eliminar los datos, false en caso contrario
	 */
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$id = parent::get_id();
				$consulta_borra="DELETE FROM obra WHERE id_funcion='$id'";
				//debuggin
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