<?php
include 'Teatro.php';
include_once 'Funcion.php';
include_once 'Obra.php';
include_once 'Musical.php';
include_once 'Pelicula.php';

	function menu(){
		echo"\n------------------------------------------------------------------------------------------------------\n";
		echo "MENU DE OPCIONES: \n";
		echo "opcion 1: Crear Teatro \n";
		echo "opcion 2: Modificar nombre del Teatro \n";
		echo "opcion 3: Modificar direccion del Teatro \n";
		echo "opcion 4: Agregar funciones al teatro \n";
		echo "opcion 5: Modificar nombre de una funcion \n";
		echo "opcion 6: Modificar precio de una funcion \n";
		echo "opcion 7: Borrar una funcion \n";
		echo "opcion 8: Obtener costo total del Teatro \n";
		echo "opcion 9: Ver datos del Teatro\n";
		echo "opcion 10: Finalizar programa\n";
		echo "opcion: ";
		$opcion = trim(fgets(STDIN));
		return $opcion;
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@return Teatro $teatro
	*/
	function crear_teatro(){
		/*variables: String $nombre, $direccion, Teatro $teatro*/
		echo "\n";
		echo "\ningrese el nombre del teatro: \n";
		$nombre =strtolower(trim(fgets(STDIN)));
		echo "\ningrese la direccion del teatro: \n";
		$direccion =strtolower(trim(fgets(STDIN)));
		$teatro = new Teatro();
		$teatro -> cargar(null, $nombre, $direccion);
		$respuesta = $teatro->insertar();
		if($respuesta){
			echo "\nteatro creado correctamente\n\n";
		}else{
			echo "\nteatro NO creado correctamente\n\n";
		}

		return $teatro;
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function modificar_nombre_teatro($teatro){
		/*variable: String $nuevoNombre*/
		echo "\n";
		echo"\ningrese el nuevo nombre: \n";
		$nuevoNombre = strtolower(trim(fgets(STDIN)));
		$teatro ->set_nombre($nuevoNombre);
		$respuesta = $teatro -> modificar();
		if($respuesta) {
			echo "\nnombre modificado correctamente\n\n";
		} else {
			echo "\n nombre no modificado correctamente\n\n";
		}

	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function modificar_direccion_teatro($teatro){
		/*variable: String $nuevaDireccion*/
		echo "\n";
		echo "\ningrese la direccion nueva: \n";
		$nuevaDireccion = strtolower(trim(fgets(STDIN)));
		$teatro->set_direccion($nuevaDireccion);
		$respuesta = $teatro->modificar();
		if($respuesta){
			echo "\ndireccion modificada correctamente\n\n";
		} else {
			echo "\ndireccion NO modificada correctamente\n\n";
		}

	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function agregar_funciones_teatro($teatro){
		/*variables:  String $unaOpcion, String $nombre, real $precio, real $hora, real $minutos, int $duracion,  Musical $unMusical, Obra $unaObra, boolean $agregado,*/
		echo "\n";
		do{
			echo "\nIngrese la actividad a agregar: Pelicula, Obra de Teatro, Musical: \n";
			$unaOpcion = strtolower(trim(fgets(STDIN)));
			echo"\ningrese el nombre de el/la ".$unaOpcion." : \n";
			$nombre =strtolower(trim(fgets(STDIN)));
			echo"\ningrese el precio de el/la ".$unaOpcion." con pesos y centavos separados por un punto EJEMPLO 354.6: \n";
			$precio = trim(fgets(STDIN));
			echo "\ningrese la hora de inicio de el/la ".$unaOpcion." en dos partes, primero la hora y luego los minutos: \n";
			echo"\ningrese la hora del horario de inicio EJEMPLO 12\n";
			$hora = trim(fgets(STDIN));
			echo "\ningrese los minutos del horario de inicio de el/la ".$unaOpcion." EJEMPLO 30:\n";
			$minutos = trim(fgets(STDIN));
			echo "\ningrese la duracion en minutos: \n";
			$duracion = trim(fgets(STDIN));

			$agregado = $teatro->agregar_funcion($nombre, $precio, $hora, $minutos, $duracion, $unaOpcion);

			if($agregado){
				echo "\nfuncion agregada correctamente\n";
			}else{
				echo "\nla funcion ya existe el teatro\n";
			}
			echo"\ndesea agregar una funcion?: s(si) o n(no)\n\n";
			$confirmar = strtolower(trim(fgets(STDIN)));
		}while($confirmar != "n");

	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function modificar_nombre_funcion_teatro($teatro){
		/*variables: String $nombreFuncion, $nombreNuevo, boolean $modificado*/
		echo "\n";
		echo "\ningrese el nombre de la funcion a modificar: \n";
		$nombreFuncion =strtolower(trim(fgets(STDIN)));
		echo "\ningrese el nuevo nombre de la funcion: \n";
		$nombreNuevo = strtolower(trim(fgets(STDIN)));
		$modificado = $teatro->modificar_nombre_funcion($nombreFuncion, $nombreNuevo);
		if($modificado){
			echo"\nel nombre de la funcion fue modificado correctamente\n\n";
		}else{
			echo"\nla funcion NO fue modificada\n\n";
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function modificar_precio_funcion_teatro($teatro){
		/*variables: String $nombreFuncion, real $precioNuevo, boolean $modificado*/
		echo "\n";
		echo "\ningrese el nombre de la funcion a modificar: \n";
		$nombreFuncion = strtolower(trim(fgets(STDIN)));
		echo "\ningrese el nuevo precio de la funcion: \n";
		$precioNuevo = trim(fgets(STDIN));
		$modificado = $teatro->modificar_precio_funcion($nombreFuncion, $precioNuevo);
		if($modificado){
			echo"\nel precio de la funcion fue modificado correctamente\n\n";
		}else{
			echo"\nla funcion NO fue modificada\n\n";
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function borrar_funcion_teatro($teatro){
		/*variables: String $funcion, boolean $realizado*/
		echo "\n";
		echo"\ningrese el nombre de la funcion a borrar: \n";
		$nombreFuncion = strtolower(trim(fgets(STDIN)));
		$realizado = $teatro->borrar_funcion($nombreFuncion);
		if($realizado){
			echo"\nla funcion fue borrada correctamente: \n\n";
		}else{
			echo"\nla funcion no fue borrada\n\n";
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function obtener_costo_total_teatro($teatro){
		$nombre = $teatro->get_nombre();
		echo "\n\nEl costo total del Teatro ". $nombre." es de: $ ".$teatro->calcular_costo_total()."\n\n";
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	*@param Teatro $teatro
	*/
	function ver_datos($teatro){
		echo "\n\n $teatro \n";
	}
	//////////////////////////////////////////////MAIN////////////////////////////////////////////////////////

	do{
		$opcion = menu();
		switch ($opcion) {
			case 1: $teatro = crear_teatro();
				break;
			case 2: modificar_nombre_teatro($teatro);
				break;
			case 3: modificar_direccion_teatro($teatro);
				break;
			case 4: agregar_funciones_teatro($teatro);
				break;
			case 5: modificar_nombre_funcion_teatro($teatro);
				break;
			case 6: modificar_precio_funcion_teatro($teatro);
				break;
			case 7: borrar_funcion_teatro($teatro);
				break;
			case 8: obtener_costo_total_teatro($teatro);
				break;
			case 9: ver_datos($teatro);
				break;
			case 10: echo "\n----------------------------------------FIN DE PROGRAMA----------------------------------------\n";
				break;
			default: echo "ERROR";
				break;
		}
	} while($opcion!=10);


?>
