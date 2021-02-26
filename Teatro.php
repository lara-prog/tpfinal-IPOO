<?php
include_once 'Funcion.php';
include_once 'Obra.php';
include_once 'Musical.php';
include_once 'Pelicula.php';
include_once 'BaseDatos.php';
class Teatro{
	private $id;
	private $nombre;
	private $direccion;
	private $mensaje_operacion;
	private $col_funciones;

	//CONSTRUCT
	/*
	*@param
	*@return Teatro $unTeatro
	*/
	public function __construct(){
		$this->id = 0;
		$this->nombre = "";
		$this->direccion = "";
		$this->col_Funciones = array ();
	}

	/*
	*metodo que permite setear los atributos del teatro
	*@param string $nombre, $direccion
	*@return Teatro $unTeatro
	*/
	public function cargar($id,$nombre, $direccion){
		$this->set_id($id);
		$this->set_nombre($nombre);
		$this->set_direccion($direccion);
	}

	//GETS
	/*
	*@return int $this->nombre
	*/
	public function get_id(){
		return $this->id;
	}

	/*
	*@return String $this->nombre
	*/
	public function get_nombre(){
		return $this->nombre;
	}

	/*
	*@return String $this->direccion
	*/
	public function get_direccion(){
		return $this->direccion;
	}

	/*
	*@return String $this->mensaje_operacion
	*/
	public function get_mensaje_operacion(){
		return $this->mensaje_operacion ;
	}


	/*
	*@return array $this->colFunciones
	*/

	public function get_col_funciones(){
		$funciones_musical = new Musical();
		$funciones_pelicula = new Pelicula();
		$funciones_obra = new Obra();
		$id = $this->get_id();

		$musicales = $funciones_musical->listar("id_teatro = '$id'");
		$peliculas = $funciones_pelicula->listar("id_teatro = '$id'");
		$obras = $funciones_obra->listar("id_teatro = '$id'");

		$funciones = array_merge($musicales, $peliculas, $obras);

		$this->set_col_funciones($funciones);

	return $this->col_funciones;
	}

	//SET
	/*
	*@param  $unId
	*/
	public function set_id($unId){
		$this->id= $unId;
	}

	/*
	*@param String $unNombre
	*/
	public function set_nombre($unNombre){
		$this->nombre = $unNombre;
	}

	/*
	*@param String $unaDireccion
	*/
	public function set_direccion($unaDireccion){
		$this->direccion = $unaDireccion;
	}

	/*
	*@param String $mensaje
	*/
	public function set_mensaje_operacion($mensaje){
		$this->mensaje_operacion=$mensaje;
	}


	/*
	*@param array $unaColeccion
	*/
	public function set_col_funciones($unaColeccion){
		$this->col_funciones = $unaColeccion;
	}

	/*
	*permite agregar una nueva funcion solo si esta no existe en la coleccion,
	*retorna un booleano para verificarlo
	*@param string $nombre, float $precio, int $hora, int $minuto, int $duracion, string $unaOpcion
	*@return boolean $realizado
	*/
	public function agregar_funcion($nombre, $precio, $hora, $minuto, $duracion, $unaOpcion){
		/*variable: boolean $encontrado, $realizado, Funcion $unaFuncion, String $genero, String $pais, String $director, int $cantidad*/
		$realizado = false;
		$coleccion = $this->get_col_funciones();
		$longi = count($coleccion);
		if(strcmp ($unaOpcion, "pelicula")===0 ||strcmp ($unaOpcion, "obra de teatro")===0 ||strcmp ($unaOpcion, "musical")===0){
		//verifico que la opcion es correcta
			if($longi < 0){
				//verifico que la coleccion esta vacia, entonces puedo ingresar la funcion
				$realizado=$this->crear_agregar_funcion($nombre, $precio, $hora, $minuto, $duracion, $unaOpcion);
			}
			else{
				$encontrado = $this->verificar_Funcion($nombre, $hora, $minuto, $duracion);
				//si la coleccion no es vacia, tengo que verificar que no la encuentro en la coleccion
				if(!$encontrado){
					$realizado=$this->crear_agregar_funcion($nombre, $precio, $hora, $minuto, $duracion, $unaOpcion);
			}
		}
	}
		return $realizado;
	}

	/*
	*permite crear y agregar una nueva funcion en la coleccion,
	*retorna un booleano para verificarlo
	*@param string $nombre, float $precio, int $hora, int $minuto, int $duracion, string $unaOpcion
	*@return boolean $realizado
	*/
	private function crear_agregar_funcion($nombre, $precio, $hora, $minuto, $duracion, $unaOpcion){
		/*variable: Funcion $unaFuncion*/
		$id_teatro = $this->get_id();
		switch ($unaOpcion) {
						case "pelicula": echo "\nIngrese el genero de la pelicula: \n";
						         	 $genero = strtolower(trim(fgets(STDIN)));
						         	 echo "\nIngrese el país de origen de la pelicula: \n";
						        	 $pais = strtolower(trim(fgets(STDIN)));
						        	 //creo una pelicula
						        	 $unaFuncion = new Pelicula();
						        	 $unaFuncion->cargar(null, $nombre, $precio, $hora, $minuto, $duracion, $id_teatro, $genero, $pais);
						        	 break;

						case "musical": echo "\nIngrese el nombre del Director del musical: \n";
						        	$director = strtolower(trim(fgets(STDIN)));
						        	echo "\nIngrese la cantidad de personas en escenas: \n";
						        	$cantidad = trim(fgets(STDIN));
						        	//creo un musical
						        	$unaFuncion = new Musical();
						        	$unaFuncion ->cargar(null,$nombre, $precio, $hora, $minuto, $duracion, $id_teatro, $director, $cantidad);
						        	break;

						case "obra de teatro":echo "\nIngrese el nombre del Autor de la obra: \n";
						        		   $autor = strtolower(trim(fgets(STDIN)));
						        		    //creo una obra
										   $unaFuncion = new Obra();
										   $unaFuncion->cargar(null,$nombre, $precio, $hora, $minuto, $duracion, $id_teatro, $autor);
										   break;

						default: echo "ERROR";
					     	 break;
				}
		//se inserta la funcion!
		$realizado = $unaFuncion ->insertar();

		return $realizado;
	}

	/*
	*verifica si los parametros son validos y no existen en la coleccion de funciones
	*@param string $nombre, int $hora, int $duracion
	*@return boolean $existe
	*/
	public function verificar_funcion($nombre, $hora, $minuto, $duracion){
		/*variables: boolean $existe, int $horaFin, Funcion $funcion, array $coleccion*/
		$existe = false;
		$coleccion = $this->get_col_funciones();
		$longi = count($coleccion);
		if($longi>0){
			$i = 0;
			while(!$existe && $i<$longi){
			//verifico que no existe otra funcion que tenga el mismo nombre
				if($coleccion[$i]->get_nombre() == $nombre){
					$existe = true;
				}else{
				//calculo en que horario finaliza la funcion
					$horaFin = $coleccion[$i]->calcular_hora_fin();
					$horarioFuncion = $coleccion[$i]->get_hora_inicio();
					if($this ->verificar_inicio($horarioFuncion, $hora, $minuto) || $this->verificar_fin($horaFin, $hora, $minuto)){
						$existe = true;
					}
				}
				$i++;
				}
		}
		return $existe;
	}

	//estas funciones comparan los horarios
	public function verificar_inicio($horarioFuncion, $hora, $minuto){
		$horaInicio = intval($horarioFuncion);
		$minutoInicio = (float)($horarioFuncion - $horaInicio);
		$minutoInicio = round($minutoInicio, 2, PHP_ROUND_HALF_UP);
		$minutoInicio = intval(ltrim($minutoInicio, "0."));
		return ($hora == $horaInicio && $minuto > $minutoInicio);
	}

	public function verificar_fin($horaFin, $hora, $minuto){
		$horaFinal = intval($horaFin);
		$minutoFin = (float)$horaFin - $horaFinal;
		$minutoFin = round($minutoFin, 2, PHP_ROUND_HALF_UP);
		$minutoFin = intval(ltrim($minutoFin, "0."));
		return ($hora == $horaFinal && $minuto < $minutoFin);
    }

	/*
	*busca una funcion por el nombre, si no existe retorna null
	*@param string $nombre
	*@return Funcion $funcion
	*/
	public function buscar_funcion($nombre){
		/*variable: boolean $encontrado, int $i, longi, Funcion $funcion, array $coleccion*/
		$encontrado = false;
		$i = 0;
		$coleccion = $this->get_col_funciones();
		$longi = count($coleccion);
		$funcion = null;
		while(!$encontrado && $i<$longi){
			if($coleccion[$i]->get_nombre() == $nombre){
				$funcion = $coleccion[$i];
				$encontrado = true;
			}
		}
		return $funcion;
	}

	/*
	*permite modificar el nombre de una funcion solo si existe en la coleccion
	*retorna un booleano para verificar que se pudo modificar correctamente la funcion
	*@param string $nombreAnterior, String $nombreNuevo
	*@return boolean $realizado
	*/
	public function modificar_nombre_funcion($nombreAnterior, $nombreNuevo){
		/*variable: Funcion $funcion, boolean $realizado*/
		$realizado = false;
		$funcion = $this->buscar_funcion($nombreAnterior);
		if($funcion != null){
			$funcion -> set_nombre($nombreNuevo);
			$realizado = $funcion->modificar();
		}
		return $realizado;
	}

	/*
	*permite modificar el precio de una funcion solo si existe en la coleccion
	*retorna un booleano para verificar que se pudo modificar correctamente la funcion
	*@param string $nombre, real $precioNuevo
	*@return boolean $realizado
	*/
	public function modificar_precio_funcion($nombre, $precioNuevo){
		/*variable: Funcion $funcion, boolean $realizado*/
		$realizado = false;
		$funcion = $this->buscar_funcion($nombre);
		if($funcion != null){
			$funcion -> set_precio($precioNuevo);
			$realizado = $funcion->modificar();
		}
		return $realizado;
	}

	/*
	*permite borrar una funcion solo si existe en la coleccion
	*retorna un booleano para verificar que se pudo modificar correctamente la coleccion
	*@param string $nombre
	*@return boolean $realizado
	*/
	public function borrar_funcion($nombre){
		/*variable: Funcion $funcionABorrar, array $nuevaColeccion, coleccion, boolean $realizado*/
		$realizado = false;
		$funcionABorrar = $this->buscar_funcion($nombre);
		if($funcionABorrar != null){
			$realizado = $funcionABorrar->eliminar();
		}
		return $realizado;
	}

	/*
	*este metodo calcula el costo total de las actividades
	*@return int $costo_total
	*/
	public function calcular_costo_total(){
		/*variable: array $coleccion, int $costo_total, int $costo*/
		//la coleccion tiene instancias de obra, musical y pelicula
		$coleccion = $this->get_col_funciones();
		$costo_total = 0;
		foreach ($coleccion as $actividad) {
			$costo = $actividad->dar_costos();
			$costo_total += $costo;
		}
		return $costo_total;
	}

	//TO STRING
	public function col_funciones_string(){
		$s="";
		$coleccion = $this->get_col_funciones();
		foreach ($coleccion as $funcion) {
			$s.=$funcion;
		}
		return $s;
	}

	/*
	*TO STRING
	*@return String $s
	*/
	public function __toString(){
		$s="Nombre Teatro: ".$this->get_nombre()."\n".
		"Direccion: ".$this->get_direccion()."\n".
		"Funciones: ".$this->col_funciones_string()."\n";
		return $s;
	}

	/**************************************************************************************************************************/
	/**
	 * Recupera los datos de una persona por dni
	 * @param string $nombre_teatro
	 * @return true en caso de encontrar los datos, false en caso contrario
	 */
    public function Buscar($id_teatro){
		$base=new BaseDatos();
		$consulta_teatro="Select * from teatro where id_teatro".$id_teatro;
		$resp= false;
		//debbugin
		//echo "\n".$consulta_teatro."\n";
		if($base->Iniciar()){
			if($base->Ejecutar($consulta_teatro)){
				if($row2=$base->Registro()){
					$this->set_id($id_teatro);
					$this->set_nombre($row2['nombre']);
					$this->set_direccion($row2['direccion']);
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
	 * Lista los datos de todos los teatros que cumplen con una condicion
	 * @param String $condicion
	 * @return array $arreglo_teatro
	 */
	public function listar($condicion){
	    $arreglo_teatro = null;
		$base=new BaseDatos();
		$consulta_teatro="Select * from teatro ";
		if ($condicion!=""){
		    $consulta_teatro=$consulta_teatro.' where '.$condicion;
		}
		$consulta_teatro.=" order by nombre ";
		//debuggin
		//echo "\n".$consulta_teatro."\n";
		if($base->Iniciar()){
			if($base->Ejecutar($consulta_teatro)){
				$arreglo_teatro= array();
				while($row2=$base->Registro()){
					$id_teatro=$row2['id_teatro'];
					$nombre_teatro=$row2['nombre'];
					$direccion=$row2['direccion'];
					$teatro=new Teatro();
					$teatro->cargar($id_teatro,$nombre_teatro, $direccion);
					array_push($arreglo_teatro,$teatro);
				}
		 	}	else {
		 			$this->set_mensaje_operacion($base->getError());
			}
		 }	else {
		 		$this->set_mensaje_operacion($base->getError());
		 }
		 return $arreglo_teatro;
	}

	/**
	 * Inserta una nueva instancia en la tabla teatro
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$nombre = $this->get_nombre();
		$direccion = $this->get_direccion();
		$consulta_insertar = "INSERT INTO teatro (nombre, direccion) VALUES ('$nombre', '$direccion')";
		//debbugin
		//echo "\n".$consulta_insertar."\n";
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consulta_insertar)){
				$this->set_id($id);
			    $resp=true;
			} else {
				echo $base->getError();
				$this->set_mensaje_operacion($base->getError());
			}
		} else {
				$this->set_mensaje_operacion($base->getError());
		}
		return $resp;
	}

	/**
	 * Modifica una instancia en la tabla teatro
	 * @param
	 * @return true en caso de insertar los datos, false en caso contrario
	 */
	public function modificar(){
	    $resp =false;
	    $base=new BaseDatos();
	    $nombre = $this->get_nombre();
	    $direccion = $this->get_Direccion();
	    $id_teatro = $this->get_id();
		$consulta_modificar="UPDATE teatro SET nombre = '$nombre', direccion = '$direccion' WHERE id_teatro = '$id_teatro'";
		//debbugin
		//echo "\n".$consulta_modificar."\n";
		if($base->Iniciar()){
			if($base->Ejecutar($consulta_modificar)){
			    $resp=true;
			}else{
				$this->set_mensaje_operacion($base->getError());
			}
		}else{
				$this->set_mensaje_operacion($base->getError());
		}
		return $resp;
	}

	/**
	 * Elimina una instancia en la tabla teatro
	 * @param
	 * @return true en caso de eliminar los datos, false en caso contrario
	 */
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$id_teatro = $this->get_id();
				$consulta_borrar="DELETE FROM teatro WHERE id_teatro='$id_teatro'";
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
