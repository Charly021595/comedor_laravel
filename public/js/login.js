function Login(){
	var usuario = $("#username").val();
	var pasword = $("#password").val();
	if(usuario.replace(/\s/g,"") != "" && pasword.replace(/\s/g,"") != ""){
		$.ajax({
			type: "POST",
			//async: false,
			data: {
			  param: 6,
			  username: usuario,
			  password: pasword
			  
			},
			
			url: "./utileria.php", 
		    dataType: 'JSON',
			success: function(data) {
				$('.cargando').hide(); // Oculta la imagen de cargando 
				if(data.length){
					for(i=0;i<data.length;i++){
						if(data[i]['NoEmpleado'] !=""){
							window.location='dashboard.php';
						}else{
							 document.getElementById('mensaje').innerHTML = '';
							 $('#mensaje').append("<pre>No se encontró el usuario, verifique los datos.</pre>");
						}
					}
					
				}
				else{
					 //No se encontró el usuario, verifique los datos.
					 document.getElementById('mensaje').innerHTML = '';
					 $('#mensaje').append("<pre>No se encontró el usuario, verifique los datos.</pre>");
					 $("#username").val("");
					 $("#password").val("");
				}
				
			}
		});
		
	}else{
		// $('#mensaje').append("<pre>Favor de validar usuario o contraseña</pre>");
	}
	
}

function Validar() {
	//var e = document.getElementById("password").value;
	var elInput = document.getElementById('password');
	elInput.addEventListener('keyup', function(e) {
	  var keycode = e.keyCode || e.which;
	  if (keycode == 13) {
		Login();
	  }
	});
}

function CerrarSesion(){
	$.ajax({
			type: "POST",
			//async: false,
			data: {
			  param: 7
			},
			
			url: "./utileria.php", 
		    dataType: 'JSON',
			success: function(data) {
				$('.cargando').hide(); // Oculta la imagen de cargando 
				if(data.length){
					window.location='index.php';
				}
				
				
			}
		});
	
}

$(function(){

  $('.validanumericos').keypress(function(e) {
	if(isNaN(this.value + String.fromCharCode(e.charCode))) 
     return false;
  })
  .on("cut copy paste",function(e){
	e.preventDefault();
  });

});