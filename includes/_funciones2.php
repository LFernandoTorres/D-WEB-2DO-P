<?php  
	require_once("_db.php");
	switch ($_POST["accion"]) {
		case 'login':
			login();
			break;
		
		default:
			# code...
			break;
	}
	function login(){
		global $mysqli;
		$correo = $_POST["correo"];
		$password = $_POST["password"];	
		$consulta = "SELECT * FROM usuarios WHERE correo_usr = '$correo'";
		$resultado = $mysqli->query($consulta);
		$fila = $resultado->fetch_assoc();
		
		if ($fila == 0) 
			{
				
				echo "El Correo no existe Codigo Error : 2";

			}

			 
		else if ($fila["password"] != $password) 
			{
				$consulta = "SELECT * FROM usuarios WHERE correo_usr = '$correo' AND password = '$password'";
				$resultado = $mysqli->query($consulta);
				$fila = $resultado->fetch_assoc();
		
				echo "El Password es incorrcto Codigo Error : 0";

				
			}
				else if($correo == $fila["correo_usr"] && $password == $fila["password"])
				{
			
					echo "El Correo y Password estan correctos : 1"	;
					
				}
			}
			// Conectar a Base de Datos.
			// Consultar a Base de Datos que exista el usuario.
			// Si el usuario no existe imprimir = 2
			// Si el usuario existe, conusltar que el password sea correcto.
			// Si el password no es correcto, imprimir codigo de erorres = 0.
			// Si el password es correcto imprimir = 1 
?>		
