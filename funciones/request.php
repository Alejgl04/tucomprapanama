<?php
ini_set('memory_limit', '-1');
require ($_SERVER['DOCUMENT_ROOT'].'/funciones/phpmailermaster/src/Exception.php');
require ($_SERVER['DOCUMENT_ROOT'].'/funciones/phpmailermaster/src/PHPMailer.php');
require ($_SERVER['DOCUMENT_ROOT'].'/funciones/phpmailermaster/src/SMTP.php');
// require ('phpmailermaster/src/Exception.php');
// require ('./phpmailermaster/src/PHPMailer.php');
// require ('./phpmailermaster/src/SMTP.php');
if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST["action"])) {
		$action = $_POST["action"];
		switch ($action) {
			case 'enviarCorreo':
			$nombre = $_POST['nombre'];
		    $correo = $_POST['correo'];
		    $asunto = $_POST['asunto'];
		    $descripcion = $_POST['descripcion'];
		    $codigo = "";
		    $mensaje= "";
		    if(empty($nombre) || empty($correo) || empty($descripcion) || empty($asunto)){
		        $codigo = 0;
		        $mensaje= "Complete todos los campos";
		    }
		    else if(!valida_email($correo)){
		        $codigo = 1;
		        $mensaje= "Correo electrónico no válido";
		    }
		    else{
		        $toemails = array();
		        $toemails[] = array(
		                        'email' => 'logistica@tucomprapanama.com',
		                        // 'email' => '',
		                        'name' => 'Servicio al cliente'
		                    );      
		        $mail = new PHPMailer\PHPMailer\PHPMailer();
		        $subject = 'Nuevo mensaje - Envios tucompraprepanama.com';
		        $mail->SetFrom( $correo , $nombre );
		        // $mail->addCC($email);
		        $mail->AddReplyTo( $correo, $nombre );
		        foreach( $toemails as $toemail ) {
		            $mail->AddAddress( $toemail['email'] , $toemail['name'] );
		        }
		        $mail->Subject = $subject;
		        $mail->isHTML(true);
		        $variable = file_get_contents("templates/notification.html");
		        $variable = str_replace("%%nombre%%", $nombre, $variable);
		        $variable = str_replace("%%correo%%", $correo, $variable);
		        $variable = str_replace("%%asunto%%", $asunto, $variable);
		        $variable = str_replace("%%descripcion%%", $descripcion, $variable);

		        $mail->Body = $variable;
		        $sendEmail = $mail->send();
		        if( $sendEmail == true ):   
		            $codigo = 2;
		            $mensaje= "La información ha sido enviada exitosamente";
		        else:
		            $codigo = 3;
		            $mensaje= "Ocurrio un error inesperado, intente nuevamente";
		        endif;
		    }
		    $array = array('codigo'=>$codigo,'mensaje'=>$mensaje);
		    echo json_encode($array,JSON_UNESCAPED_UNICODE);
			break;
			default:
				
			break;
		}
	}
}
function valida_email($email){ //Validar el correo
    if (preg_match('/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/', $email)) return true; 
    else return false; 
}
?>