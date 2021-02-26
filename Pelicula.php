<?php
include_once 'BaseDatos.php';
include_once 'Funcion.php';
class Pelicula extends Funcion{
	private $genero;
	private $pais_origen;

	//CONSTRUCT
	/*
	*@param
	*@return Pelicula $unaPelicula
	*/
	public function __construct(){
		parent::__construct();
		$this->genero = "";
		$this->pais_origen = "";
	}

	/*
	*metodo que permite setear los atributos del la obra
	*@param int $id, string $nombre, string $direccion, float $unPrecio, int $unaHora, int $minutos, int $duracion, int $idTeatro, string $genero, string $pais_origen
	*@return Teatro $unTeatro
	*/
	public function cargar($id, $unNombre, $unPrecio, $unaHora, $minutos, $unaDuracion, $idTeatro,$genero, $pais_origen){
		parent::cargar($id, $unNombre, $unPrecio, $unaHora, $minutos, $unaDuracion, $idTeatro);
		$this->set_genero($genero);
		$this->set_pais_origen($pais_origen);

	}

	//GETS
	public function get_genero(){
		return $this->genero;
	}

	public function get_pais_origen(){
		return $this->paisOrigen;
	}

	/*
	*@return String $this->mensaje_operacion
	*/
	public function get_mensaje_operacion(){
		return $this->mensaje_operacion ;
	}

	//SETS
	public function set_genero($unGenero){
		$this->genero = $unGenero;
	}

	public function set_pais_origen($unPais){
		$this->paisOrigen = $unPais;
	}

	/*
	*@param String $mensaje
	*/
	public function set_mensaje_operacion($mensaje){
		$this->mensaje_operacion=$mensaje;
	}

	/*
	*este metodo aplica un incremento sobre el precio de la funcion de pelicula y retorna un costo
	*@return int $costo
	*/
	public function dar_costos(){
		/*variables: int $valor, float $costo*/
		$valor = parent::dar_costos();
		$costo = $valor + ($valor * 0.65);
		return $costo;
	}

	/*
	*TOSTRING
	*@return String $s
	*/
	public function  __toString(){
		$s=parent::__toString();
		$s=$s.", Genero: ".$this->get_genero().", Pais de origen: ".$this->get_pais_origen()."\n";
		return $s;
	}

	/*********************************************************************************************************************************/
	/**
	 * Recupera los datos de una pelicula por nombre
	 * @param string $nombre_pelicula
	 * @return true en caso de encontrar los datos, false en caso contrario
	 */
	  public function Buscar($id_pelicula){
		$base=new BaseDatos();
		$consulta_buscar="Select * from pelicula where id_funcion=".$id_pelicula;
		$resp= false;
		//debbugin
		//echo $consulta_buscar;
		if($base->Iniciar()){
		    if($base->Ejecutar($consulta_buscar)){
				if($row2=$base->Registro()){
				    parent::Buscar($id_pelicula);
				    $this->set_genero($row2['genero']);
				    $this->set_pais_origen($row2['pais_origen']);
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
	 * Lista los datos de todos las peliculas que cumplen con una condicion
	 * @param String $condicion
	 * @return array $arreglo_pelicula
	 */
	public function listar($condicion){
	    $arreglo_pelicula= array();
		$base=new BaseDatos();
		$consulta_listar="Select * from pelicula inner join funcion on pelicula.id_funcion = funcion.id_funcion";
		if ($condicion!=""){
		    $consulta_listar=$consulta_listar.' where funcion.'.$condicion;
		}
		$consulta_listar.=" order by nombre ";
		//debuggin
		//echo "\n".$consulta_listar."\n";
		if($base->Iniciar()){
		    if($base->Ejecutar($consulta_listar)){

				while($row2=$base->Registro()){
					$obj=new Pelicula();
					$obj->Buscar($row2['id_funcion']);
					array_push($arreglo_pelicula,$obj);
				}
		 	}	else {
		 			$this->set_mensaje_operacion($base->getError());
			}
		 }	else {
		 		$this->set_mensaje_operacion($base->getError());
		 }
		 return $arreglo_pelicula;
	}

	/**
	 * Inserta una nueva instancia en la tabla pelicula
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;

		if(parent::insertar()){
			$id = parent::get_id();
			$genero =  $this->get_genero();
			$pais = $this->get_pais_origen();
		    $consulta_insertar="INSERT INTO pelicula(id_funcion,genero, pais_origen) VALUES ('$id', '$genero', '$pais')";
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
	 * Modifica una instancia en la tabla pelicula
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function modificar(){
	    $resp =false;
	    $base=new BaseDatos();
	    if(parent::modificar()){
	    	$genero = $this->get_genero();
	    	$pais = $this->get_pais_origen();
	    	$id = parent::get_id();
	        $consulta_modifica="UPDATE pelicula SET genero='$genero', pais_origen='$pais' WHERE id_funcion = '$id'";
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
	 * Elimina una instancia en la tabla pelicula
	 * @param
	 * @return true en caso de eliminar los datos, false en caso contrario
	 */
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$id = parent::get_id();
				$consulta_borra="DELETE FROM pelicula WHERE id_funcion='$id'";
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