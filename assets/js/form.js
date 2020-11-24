// Scripts JS
$(document).ready(function(){
	$("body").on('click',"#btnEnviar",function(){
		let validar = true;
		let nombre  = $("#name").val();
		let correo  = $("#email").val();
		let asunto = $("#subject").val();
		let descripcion  = $("#mensaje").val();
		let re = /^([a-z0-9_-])+([\.a-z0-9_-])*@([a-z0-9-])+(\.[a-z0-9-]+)*\.([a-z]{2,6})*$/i;
		$(".validate1").html('');
		$(".validate2").html('');
		$(".validate3").html('');
		$(".validate4").html('');
		if(nombre == ""){
			$(".validate1").html('Complete este campo');
			validar = false;
		}
		if(correo == ""){
			$(".validate2").html('Complete este campo');
			validar = false;
		}
		else if(!re.test(correo)){
			$(".validate2").html('Dirección email no válido');
			validar = false;	
		}
		if(asunto == ""){
			$(".validate3").html('Complete este campo');
			validar = false;
		}
		if(descripcion == ""){
			$(".validate4").html('Complete este campo');
			validar = false;
		}
		if(validar==true){
			$("#loader").attr('style','display:block;');
			// $("#btnEnviar").attr('disabled',true);
			$.post({
		        method: 'POST',
		        url: "funciones/request.php",
		        data: {action: "enviarCorreo",nombre:nombre,correo:correo,asunto:asunto,descripcion:descripcion},
		        success: function(html){
	        		$("#loader").attr('style','display:none;');
					$("#btnEnviar").attr('disabled',false);
					// console.log(html);
		            json = JSON.parse(html);
		            if(json.codigo==0){
		            	$(".error-message").html(json.mensaje).attr('style','display:block;');
		            }
		            else if(json.codigo==1){
		            	$(".error-message").html(json.mensaje).attr('style','display:block;');
		            }
		            else if(json.codigo==2){
            		$(".sent-message").html(json.mensaje).attr('style','display:block;');
		            	$("#name").val('');
						$("#email").val('');
						$("#mensaje").val('');
						$("#subject").val('');
						setTimeout(function(){
							$(".sent-message").html('').attr('style','display:none;');
						},5000)
		            }	
		            else{
	            		$(".error-message").html(json.mensaje).attr('style','display:block;');
		            }	
		        },
		        error:function(error){
            		$(".error-message").html(error).attr('style','display:block;');
		        }
			});
		}
	});
});