<?php
include_once 'BaseDatos.php';
class Funcion{
	private $id_funcion;
	private $nombre;
	private $precio;
	private $horaInicio;
	private $duracion;
	private $id_teatro;
	private $mensaje_operacion;
	//tanto el precio como la hora de inicio se registrara como un arreglo
	//en cuanto a la hora, sera una variable flotante
	//la duracion de las funciones se registrara en minutos

	//CONSTRUCT
	/*
	*@return Funcion $unaFuncion
	*/
	public function __construct(){
		$this->id_funcion =0;
		$this->nombre ="";
		$this->precio = "";
		$this->horaInicio = "";
		$this->duracion = "";
		$this->id_teatro = "";
	}

	/*
	*metodo que permite setear los atributos del funcion
	*@param int $id, string $nombre, string $direccion, float $unPrecio, int $unaHora, int $minutos, int $duracion, int $idTeatro
	*@return Teatro $unTeatro
	*/
	public function cargar($id,$unNombre, $unPrecio, $unaHora, $minutos, $unaDuracion, $idTeatro){
		$this->set_id($id);
		$this->set_nombre($unNombre);
		$this->set_precio($unPrecio);
		$this->set_hora_inicio((float)($unaHora.".".$minutos));
		$this->set_duracion($unaDuracion);
		$this->set_id_teatro($idTeatro);
	}
	//GETS
	/*
	*@return int $this->ids
	*/
	public function get_id(){
		return $this->id_funcion;
	}

	/*
	*@return String $this->nombre
	*/
	public function get_nombre(){
		return $this->nombre;
	}

	/*
	*@return real $this->precio
	*/
	public function get_precio(){
		return $this->precio;
	}

	/*
	*@return array $this->horaInicio
	*/
	public function get_hora_inicio(){
		return $this->horaInicio;
	}

	/*
	*@return int $this->duracion
	*/
	public function get_duracion(){
		return $this->duracion;
	}

	/*
	*@return int $this->id_teatro
	*/

	public function get_id_teatro(){
		return $this->id_teatro;
	}

	/*
	*@return String $this->mensaje_operacion
	*/
	public function get_mensaje_operacion(){
		return $this->mensaje_operacion ;
	}

	//SETS
	/*
	*@param int $unId
	*/
	public function set_id($unId){
		$this->id_funcion = $unId;
	}

	/*
	*@param String $unNombre
	*/
	public function set_nombre($unNombre){
		$this->nombre = $unNombre;
	}

	/*
	*@param Real $unPrecio
	*/
	public function set_precio($unPrecio){
		$this->precio = $unPrecio;
	}

	/*
	*@param Real $unaHora
	*/
	public function set_hora_inicio($horario){
		$this->horaInicio = $horario;
	}

	/*
	*@param int $unaDuracion
	*/
	public function set_duracion($unaDuracion){
		$this->duracion = $unaDuracion;
	}

	/*
	*@param string $teatro
	*/

	public function set_id_teatro($teatro){
		$this->id_teatro = $teatro;
	}

	/*
	*@param String $mensaje
	*/
	public function set_mensaje_operacion($mensaje){
		$this->mensaje_operacion=$mensaje;
	}

	/*
	* metodo que retorna el precio de la funcion
	*@return float $this->get_precio()
	*/
	public function dar_costos(){
		$valor = $this->get_precio();
		return $valor;
	}

	/*
	*este metodo calcula el horario en que finaliza la funcion
	*@return array $horaFin
	*/
	public function calcular_hora_fin(){
		//calculo lo que ocupara la duracion
		$duracionAHora = ($this->get_duracion())/60;
		$duracionAMinutos = ($this->get_duracion())%60;
		//recupero la hora de inicio
		$horaInicio = intval($this->get_hora_inicio());
		//calculo el total de hora
		$horaTotal = ($horaInicio+$duracionAHora);
		//recupero los minutos de inicio
		$minutosInicio = (float)$this->get_hora_inicio()-$horaInicio;
		$minutosInicio = ltrim($minutosInicio, "0.");
		$minutosInicio = intval($minutosInicio);
		//calculo el total de minutos
		$minutosTotales = ($minutosInicio + $duracionAMinutos);
		//verifico si total de minutos alcanzan la hora
		if($minutosTotales/60 > 0){
			$nuevaHora = $minutosTotales/60;
			$minutosTotales = $minutosTotales%60;
			$horaTotal = $horaTotal + $nuevaHora;
		}
		$horaFin = $horaTotal.".".$minutosTotales;
		return $horaFin;
	}


	/*
	*TOSTRING
	*@return String $s
	*/
	public function __toString(){
		$s = "Id Funcion:".$this->get_id().
		", Nombre Funcion: ".$this->get_nombre().
		", Precio Funcion: ".$this->get_precio().
		", Hora de inicio: ".$this->get_hora_inicio().
		", Duracion en minutos: ".$this->get_duracion();
		return $s;
	}
	/*****************************************************************************************************************************/
	/**
	 * Recupera los datos de una funcion por nombre
	 * @param string $nombre_funcion
	 * @return true en caso de encontrar los datos, false en caso contrario
	 */
    public function Buscar($id_funcion){
		$base=new BaseDatos();
		$consulta_funcion="Select * from funcion where id_funcion = ".$id_funcion;
		$resp= false;
		//debuging
		//echo "\n".$consulta_funcion."\n";
		if($base->Iniciar()){
			if($base->Ejecutar($consulta_funcion)){
				if($row2=$base->Registro()){
					$this->set_id($id_funcion);
				    $this->set_nombre($row2['nombre']);
					$this->set_precio($row2['precio']);
					$this->set_hora_inicio($row2['hora_inicio']);
					$this->set_duracion($row2['duracion']);
					$this->set_id_teatro($row2['id_teatro']);
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
	 * Lista los datos de todos las funciones que cumplen con una condicion
	 * @param String $condicion
	 * @return array $arreglo_funcion
	 */
	public function listar($condicion){
	    $arreglo_funcion = array();
		$base=new BaseDatos();
		$consulta_listar="Select * from funcion ";
		if ($condicion!=""){
		    $consulta_listar=$consulta_listar.' where '.$condicion;
		}
		$consulta_listar.=" order by nombre ";
		//debuging
		//echo "\n".$consulta_listar."\n";
		if($base->Iniciar()){
			if($base->Ejecutar($consulta_listar)){

				while($row2=$base->Registro()){
					$id_funcion = $row2['id_funcion'];
					$nombre_funcion=$row2['nombre'];
					$precio=$row2['precio'];
					$hora_inicio = $row2['hora_inicio'];
					$duracion = $row2['duracion'];
					$id_teatro = $row2['id_teatro'];
					$funcion=new Funcion();
					$funcion->cargar($id_funcion,$nombre_funcion,$precio,$hora_inicio,$duracion,$id_teatro);
					array_push($arreglo_funcion,$funcion);
				}
		 	}	else {
		 			$this->set_mensaje_operacion($base->getError());
			}
		 }	else {
		 		$this->set_mensaje_operacion($base->getError());
		 }
		 return $arreglo_funcion;
	}

	/**
	 * Inserta una nueva instancia en la tabla funcion
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$nombre = $this->get_nombre();
		$precio =  $this->get_precio();
		$hora = $this->get_hora_inicio();
		$duracion = $this->get_duracion();
		$id_teatro = $this->get_id_teatro();

		$consulta_insertar="INSERT INTO funcion(nombre, precio, hora_inicio, duracion, id_teatro)
		VALUES ('$nombre','$precio', '$hora', '$duracion', $id_teatro)";

		//debbugin
		//echo "\n".$consulta_insertar."\n";
		if($base->Iniciar()){
			if($id = $base->devuelveIDInsercion($consulta_insertar)){
				$this->set_id($id);
			    $resp=true;
			}	else {
					$this->set_mensaje_operacion($base->getError());
			}
		} else {
				$this->set_mensaje_operacion($base->getError());
		}
		return $resp;
	}

	/**
	 * Modifica una instancia en la tabla funcion
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function modificar(){
	    $resp =false;
	    $base=new BaseDatos();
	    $nombre = $this->get_nombre();
	    $precio = $this->get_precio();
	    $hora_inicio = $this->get_hora_inicio();
	    $duracion = $this->get_duracion();
	    $id_teatro = $this->get_id_teatro();
	    $id_funcion = $this->get_id();

		$consulta_modificar="UPDATE funcion SET nombre = '$nombre', precio = '$precio', hora_inicio = '$hora_inicio', duracion = '$duracion', id_teatro = '$id_teatro' WHERE id_funcion = '$id_funcion'";
		//debuggin
		//echo "\n".$consulta_modificar."\n";
		if($base->Iniciar()){
			if($base->Ejecutar($consulta_modificar)){
			    $resp=  true;
			}else{
				$this->set_mensaje_operacion($base->getError());
			}
		}else{
				$this->set_mensaje_operacion($base->getError());
		}
		return $resp;
	}

	/**
	 * Elimina una instancia en la tabla funcion
	 * @param
	 * @return true en caso de eliminar los datos, false en caso contrario
	 */
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$id = $this->get_id();
				$consulta_borrar="DELETE FROM funcion WHERE id_funcion='$id'";
				//debbugin
				//echo "\n".$consulta_borrar."\n";
				if($base->Ejecutar($consulta_borrar)){
				    $resp=  true;
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