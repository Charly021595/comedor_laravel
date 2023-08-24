let NoAlimento = 0;
let NoDatosBioquimicos = 0;

jQuery(function () {
	active();
});

function active() {
	$("#link_dashoard").addClass('active');
	$("#link_comedor_gs").removeClass('active');
	$("#link_platillo_express").removeClass('active');
	$("#link_listado_procesados").removeClass('active');
	$("#link_listado_finalizados").removeClass('active');
}

function BuscarEmpleadoLogeado(){
	var fechaActualL = new Date(); //Fecha actual
	var fechaActual2 = moment(fechaActualL).format("YYYY-MM-DD");
	$("#txtFechaPedido").val(fechaActual2);
	var empleado = $("#txtNumEmpleado").val()
	if(empleado.replace(/\s/g,"") != ""){
		
		//LimpiarCampos();
		$.ajax({
            type: "POST",
            data: {
                param: 1,
				empleado: empleado 
            },
            url: "utileria.php",
            dataType: 'JSON',
             success: function(data) {
				if(data.length){
					for(i=0;i<data.length;i++){
						var FechaAr =  "Fecha: "+ fechaActual2;
						$("#NombreCont2").text(data[i]['Nombre']);
						$("#NombreCont").text(data[i]['Nombre']);
						$("#tipo_empleado").val(data[i]['Tipo_Empleado']);
						$("#Fecha2").text(FechaAr);
						$("#txtNombreEmpleadoLogeado").val(data[i]['Nombre']);
					}
				}
				
			}
		});
	
	}else{
		Swal.fire('Favor de Agregar un numero de empleado.', "","info");
		CerrarSesion();
	}
}

function ValidarPlatillos(){
	let platillos = parseInt($("#txtNumPlatillo").val());
	
	if (platillos > 1) {
		Swal.fire('Solo puedes pedir un platillo de comida.', "","info");
		$("#txtNumPlatillo").val(1);
		platillos = parseInt($("#txtNumPlatillo").val());
	}
	
	if(platillos < 1 || platillos == '' || isNaN(platillos)){
		$("#txtNumPlatillo").val(1);
		platillos = parseInt($("#txtNumPlatillo").val());
	}
	let Precio = parseFloat($("#txtPrecioPlatillo").val());
	let Calculo = Precio * platillos;
	$("#txtTotalPlatillo").val(parseFloat(Calculo).toFixed(2));
}

function ValidarPlatillosGR(){
	let platillos = parseInt($("#txtNumPlatilloGR").val());
	let NomPlatillo = $("#txtProductoSeleccionadoGR").val();
	if(NomPlatillo !="0"){
		if(platillos < 1 || platillos == '' || isNaN(platillos)){
			$("#txtNumPlatilloGR").val(1);
			platillos = parseInt($("#txtNumPlatilloGR").val());
		}
		let Precio = parseFloat($("#txtPrecioGR").val());
		let Calculo = Precio * platillos;
		$("#txtPrecioTotal").val(parseFloat(Calculo).toFixed(2));
	}else{
		$("#txtNumPlatilloGR").val(1);
		$("#txtPrecioGR").val("0.00");
		$("#txtCaloriasGR").val("");
		$("#txtPrecioTotal").val("0.00");
	}
}

$("#solicitar_comida").on("click", function(e){
	let	NoPlatillos = $("#txtNumPlatillo").val(),
	TipoPlatillo = $("#txtTipoPlatillo").val(),
	Ubicacion = $("#txtUbicacion").val(),
	Total = $("#txtTotalPlatillo").val(),
	Precio= $("#txtPrecioPlatillo").val(),
	Tipo_Empleado = $("#tipo_empleado").val(),
	CantidadArreglo = "",
	arrayListadoComida = [],
	data_comida_express;
	$("#solicitar_comida").prop("disabled", true);

	e.preventDefault();
	
	let arrayListadoGreenSpot = {};
	arrayListadoPlatilloUnico = {},
	fechaActualL = new Date(), //Fecha actual
	FechaDeOrden = moment(fechaActualL).format("YYYY-MM-DD HH:mm:ss");
	
	if(TipoPlatillo == "4"){
		arrayListadoGreenSpot = GuardarListadoGreenSpot();
		CantidadArreglo = arrayListadoGreenSpot.length; 
	}else if(TipoPlatillo == "3"){
		TotalFormato = parseFloat( $("#txtTotalPlatillo").val()).toFixed(2);
		data_comida_express = {
			Precio: $("#txtPrecioPlatillo").val(),
			NoPlatillos: $("#txtNumPlatillo").val(),
			TipoPlatillo: TipoPlatillo,
			Platillo: $('select[name="txtTipoPlatillo"] option:selected').text(),
			Total: TotalFormato,
			FechaPedido: FechaDeOrden,
			Comentario: $("#txtComentarioPlatillo").val()
		}; 
		
		arrayListadoComida.push(data_comida_express);
		arrayListadoPlatilloUnico = arrayListadoComida;
	}else{
		Swal.fire('Debes seleccionar tipo de platillo', "","info");
		$("#solicitar_comida").prop("disabled", false);
        return false;
	}
	if (NoPlatillos == "") {
        Swal.fire('El número de platillos es requerido', "","info");
		$("#solicitar_comida").prop("disabled", false);
        return false;
    }
	if (TipoPlatillo != "4" && TipoPlatillo != "3") {
        Swal.fire('Tipo de platillo no soportado', "","info");
		$("#solicitar_comida").prop("disabled", false);
        return false;
    }
	if (Ubicacion == "0") {
        Swal.fire('La ubicación es obligatoria', "","info");
		$("#solicitar_comida").prop("disabled", false);
        return false;
    }
	if (Total == "0.00" && TipoPlatillo != "4") {
        Swal.fire('El saldo total no puede ser 0.00', "","info");
		$("#solicitar_comida").prop("disabled", false);
        return false;
    }
	if (CantidadArreglo === 0 && CantidadArreglo === "" && TipoPlatillo == "4") {
        Swal.fire('No tienes platillos ingresados', "","info");
		$("#solicitar_comida").prop("disabled", false);
        return false;
    }
	let formData = new FormData(document.getElementById("form_solicitar_platillos"));
	formData.append("dato", "valor");
	formData.append("FechaDeOrden", FechaDeOrden);
	formData.append("arrayListadoGreenSpot",  JSON.stringify(arrayListadoGreenSpot));
	formData.append("arrayListadoPlatilloUnico", JSON.stringify(arrayListadoPlatilloUnico));
	formData.append("pedidoporcomedor", 0);
	$.ajax({
		url: "guardar_platillo",
		type: "post",
		data: formData,
		dataType: "html",
		cache: false,
		contentType: false,
		processData: false,
		success: function(result) {
			datos = JSON.parse(result);
			console.log(datos);
			if (datos.estatus === "success") {
				Swal.fire('El pedido de la comida ha sido guardado correctamente.', "Pedido de comida Guardado.","success")
				.then(function(){
					location.reload();
				});
			}else if(datos.estatus === "pedido_duplicado"){
				Swal.fire('Solo se puede realizar un pedido al día.', "","info");
				$("#solicitar_comida").prop("disabled", false);
			}else{
				Swal.fire('Ocurrio un problema al levantar tu pedido intentalo mas tarde.', "","error");
				console.log(datos);
				$("#solicitar_comida").prop("disabled", false);
			}
		}
	});
});

// function GuardarOrden(){
// 	debugger;
// 	let	NoPlatillos = $("#txtNumPlatillo").val(),
// 	TipoPlatillo = $("#txtTipoPlatillo").val(),
// 	Ubicacion = $("#txtUbicacion").val(),
// 	Total = $("#txtTotalPlatillo").val(),
// 	Precio= $("#txtPrecioPlatillo").val(),
// 	Tipo_Empleado = $("#tipo_empleado").val();
// 	$("#GuardarOrden").prop("disabled", true);
	
// 	let arrayListadoGreenSpot = {};
// 	arrayListadoPlatilloUnico = {},
// 	fechaActualL = new Date(), //Fecha actual
// 	FechaDeOrden = moment(fechaActualL).format("YYYY-MM-DD HH:mm:ss");
	
// 	if(TipoPlatillo== "4"){
// 		arrayListadoGreenSpot = GuardarListadoGreenSpot();
// 		CantidadArreglo = arrayListadoGreenSpot.length; 
// 	}else{
// 		TotalFormato = parseFloat( $("#txtTotalPlatillo").val()).toFixed(2);
// 		arrayListadoComida = [];
// 		 Array = {};
			
// 		Array.Precio = $("#txtPrecioPlatillo").val();
// 		Array.NoPlatillos = $("#txtNumPlatillo").val();
// 		Array.TipoPlatillo = TipoPlatillo;
// 		Array.Platillo = $('select[name="txtTipoPlatillo"] option:selected').text();
// 		Array.Total = TotalFormato;
// 		Array.FechaPedido = FechaDeOrden;
// 		Array.Comentario = $("#txtComentarioPlatillo").val();
		
// 		arrayListadoComida.push(Array);
		
// 		arrayListadoPlatilloUnico = arrayListadoComida;
// 	}
// 	if (NoPlatillos == "") {
//         Swal.fire('El número de platillos es requerido', "","info");
// 		$("#GuardarOrden").prop("disabled", false);
//         return false;
//     }
// 	if (TipoPlatillo != "4" && TipoPlatillo != "3") {
//         Swal.fire('Tipo de platillo no soportado', "","info");
// 		$("#GuardarOrden").prop("disabled", false);
//         return false;
//     }
// 	if (Ubicacion == "0") {
//         Swal.fire('La ubicación es obligatoria', "","info");
// 		$("#GuardarOrden").prop("disabled", false);
//         return false;
//     }
// 	if (Tipo_Empleado == "0") {
//         Swal.fire('El tipo de empleado es obligatorio', "","info");
// 		$("#GuardarOrden").prop("disabled", false);
//         return false;
//     }
// 	if (CantidadArreglo == 0) {
//         Swal.fire('No tienes platillos ingresados', "","info");
// 		$("#GuardarOrden").prop("disabled", false);
//         return false;
//     }
// 	$.ajax({
// 		type: "POST",
// 		data: {"TipoPlatillo":TipoPlatillo, "Ubicacion": Ubicacion, "Tipo_Empleado":Tipo_Empleado, "FechaDeOrden":FechaDeOrden, 
// 		"arrayListadoGreenSpot":arrayListadoGreenSpot, "arrayListadoPlatilloUnico":arrayListadoPlatilloUnico, "pedidoporcomedor":0},
// 		url: "guardar_platillo",
// 		success: function(result) {
// 			datos = JSON.parse(result);
// 			console.log(datos);
// 			if (datos.estatus === "success") {
// 				Swal.fire('El pedido de la comida ha sido guardado correctamente.', "Pedido de comida Guardado.","success")
// 				.then(function(){
// 					location.reload();
// 				});
// 			}else if(datos.estatus === "pedido_duplicado"){
// 				Swal.fire('Solo se puede realizar un pedido al día.', "","info");
// 				$("#GuardarOrden").prop("disabled", false);
// 			}else{
// 				Swal.fire('Ocurrio un problema al levantar tu pedido intentalo mas tarde.', "","error");
// 				console.log(data.mensaje);
// 				$("#GuardarOrden").prop("disabled", false);
// 			}
// 		}
// 	});
// }

function MostarAlertas(){
    $('#ModalAviso').modal('show');
}

function CerrarPedido(){
	location.reload();
}
//
function AgregarComidaGr(){
	let Platillo = $('select[name="txtProductoSeleccionadoGR"] option:selected').text();
	let IdPlatillo = $("#txtProductoSeleccionadoGR").val();
	let Kcal = $("#txtCaloriasGR").val();
	let Cantidad = $("#txtNumPlatilloGR").val();
	let PrecioTotal = $("#txtPrecioTotal").val();
	let PrecioUnitario = $("#txtPrecioGR").val();
	let tipoplatillo = $("#txtTipoPlatilloGR").val();
	//let ComentariosGR = $("#txtComentariosGR").val();
	let ComentariosGR =document.getElementById("txtComentariosGR").value;
	if(Platillo !="0" && PrecioTotal != "0.00"){
			NoAlimento = NoAlimento +1;
			let NuevoAlimento = '<tr id="Alimento'+NoAlimento+'">';
			//style="display:none"
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Posición" style="display:none" id="tdPosiciónAlimento'+NoAlimento+'">'+NoAlimento+'</td>';
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Id. Platillo" style="display:none" id ="tdIdPlatillo'+NoAlimento+'">'+IdPlatillo+'</td>';
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Platillo" id ="tdPlatillo'+NoAlimento+'">'+Platillo+'</td>';
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Comentario" style="display:none" id ="tdComentarioPlatillo'+NoAlimento+'" >'+ComentariosGR+'</td>';
			
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Tipo Platillo" style="display:none" id ="tdTipoPlatillo'+NoAlimento+'">'+tipoplatillo+'</td>';
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Kcal." style="display:none" id ="tdKcalPlatillo'+NoAlimento+'">'+Kcal+'</td>';
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Cantidad" id ="tdCantidadPlatillo'+NoAlimento+'">'+Cantidad+'</td>';
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Precios" style="display:none" id ="tdPrecioUnitarioPlatillo'+NoAlimento+'">'+PrecioUnitario+'</td>';
			NuevoAlimento =  NuevoAlimento + '<td data-label= "Total"  style="display:none" id ="tdPrecioTotalPlatillo'+NoAlimento+'">'+PrecioTotal+'</td>';
			NuevoAlimento =  NuevoAlimento + "<td data-label='' ><button onclick='ConfirmacionEliminaAlimento("+NoAlimento+")'>Eliminar</button></td></tr>"   
			$('#ListadoComidaGr').append(NuevoAlimento);
			
	}else{
		Swal.fire('Favor de agregar un platillo.', "","info");
	}		
	LimpiarCampos();	
}

function ConfirmacionEliminaAlimento(NoAlimento){
	var NoAlimentos = NoAlimento;
		Swal.fire({
			title: '¿Deseas eliminar el registro?',
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Eliminar'
		  }).then((res) => {
			if (res.isConfirmed) {
				Swal.fire({
					title: 'Eliminado',
					icon: 'success',
					text: 'Tu platillo se eliminó.',
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Continuar'
				}).then(function(){
					ElimarAlimento(NoAlimentos);
				});
			}else{
				return false;
			}
		});
}

function ElimarAlimento(NoAlimentos){
	$("#Alimento" + NoAlimentos).remove();
	var Lineas = $("#ListadoComidaGr tr").length;
	if(Lineas == 0){
		NoAlimento=0;
	}
}

function TipoPlatillo(){
	$("#div_comida_express").hide();
	$("#div_comida_especial").hide();
	let txtUbicacion = $("#txtUbicacion").val();
	let tipoplatillo = $("#txtTipoPlatillo").val();
	$("#txtProductoSeleccionadoGR").empty();
	$("#ListadoComidaGr").find("tr").remove();
	 LimpiarCampos();
	if(tipoplatillo !="4"){
		$("#div_comida_express").show();

		let seleccionar = "<option value='0'> Seleccionar Platillo</option>";
		$('#txtProductoSeleccionadoGR').append(seleccionar);
		if(tipoplatillo =="0"){
			$("#DivCantidad").css("display", "none");
		}
        $("#txtNumPlatillo").val("1");
        $("#txtTotalPlatillo").val("47.50");
	}else{
		$("#div_comida_especial").show();
		$("#txtNumPlatillo").val("0");
		$.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
            type: "POST",
            data: {"tipoplatillo": tipoplatillo, "txtUbicacion": txtUbicacion},
            url: "tipo_platillo",
             success: function(result) {
				let data = result.data;
				$('#txtProductoSeleccionadoGR').html('');
				if(data.length){
					let seleccionar = "<option>Seleccione el Platillo</option>"
					for(i=0;i<data.length;i++){
						seleccionar += "<option value='"+data[i]['IdComida']+"'>"+data[i]['Comida']+"</option>";
					}
					$('#txtProductoSeleccionadoGR').append(seleccionar);
				}
			}
		});
	}
}

function LimpiarCampos(){
	$("#txtTipoPlatilloGR").val("");
	$("#txtPrecioTotal").val("0.00");
	$("#txtPrecioGR").val("0.00");
	$("#txtCaloriasGR").val("");
	$("#txtProductoSeleccionadoGR").val(0);
	$("#txtNumPlatilloGR").val(1);
	$("#txtComentariosGR").val("");
	$("#txtTotalPlatillo").val("0.00");
	$("#txtComentarioPlatillo").val("");
}

function InfoPlatillo(){
	let InfoPlatillo = $("#txtProductoSeleccionadoGR").val();
	
	$("#txtComentariosGR").val("");
	$("#txtNumPlatilloGR").val(1);
	$("#txtPrecioGR").val("0.00");
	$("#txtCaloriasGR").val("");
	$("#txtPrecioTotal").val("0.00");
	var TipoPlatillo = $("#txtTipoPlatillo").val();
	$("#txtTipoPlatilloGR").val(TipoPlatillo);
	
	
	if(InfoPlatillo !="0" && InfoPlatillo != null){
		$.ajax({
			type: "POST",
            data: {"InfoPlatillo": InfoPlatillo},
            url: "info_platillo",
             success: function(result) {
				let data = result.data
				if(data.length){
					
					for(i=0; i<data.length; i++){
						$("#txtPrecioGR").val(data[i]['Precio']);
						$("#txtCaloriasGR").val(data[i]['Calorias']);
					}
					ValidarPlatillosGR();
				}
			}
		});
	}else{
		$("#txtNumPlatilloGR").val(1);
		$("#txtPrecioGR").val("");
		$("#txtCaloriasGR").val("");
		$("#txtPrecioTotal").val("0.00");
		$("#txtTipoPlatilloGR").val("");
		$("#txtComentariosGR").val("");
	}
}

function GuardarListadoGreenSpot() {
    let arrayListadoComida = [];
        $("#ListadoComidaGr tr").each(function(index, value) {
            let Posicion, IdPlatillo, Platillo, Comentario, TipoPlatillo, KCal, Cantidad, Precios, Total;
            $(this).children("td").each(function(index2) {
                switch (index2) {
                    case 0:
						 Posicion = $(this).text();
					break;
					case 1:
						 IdPlatillo = $(this).text();
                    break;
					case 2:
						 Platillo = $(this).text();
                    break;
					case 3:
						Comentario = $(this).text();
                    break;
					case 4:
						 TipoPlatillo = $(this).text();
                    break;
					case 5:
						 KCal = $(this).text();
                    break;
					case 6:
						Cantidad = $(this).text();
                    break;
					case 7:
						Precios = $(this).text();
                    break;
					case 8:
						Total = $(this).text();
                    break;
                }
            });
            //$('#txtPercepcionLiq').val(TotalRefacc);
            let Array = {};
			Array.Posicion = Posicion;
            Array.IdPlatillo = IdPlatillo;
			Array.Platillo = Platillo;
			Array.Comentario = Comentario;
			Array.TipoPlatillo = TipoPlatillo;
			Array.KCal = KCal;
			Array.Cantidad = Cantidad;
			Array.Precios = Precios;
			Array.Total = Total;
            arrayListadoComida.push(Array);
        });
        return arrayListadoComida;
}

$("#txtUbicacion").on('change',function(e){
    let txtUbicacion = $("#txtUbicacion").val();
	let tipoplatillo = $("#txtTipoPlatillo").val();
	$("#txtProductoSeleccionadoGR").empty();
	$("#ListadoComidaGr").find("tr").remove();
	 LimpiarCampos();
	if(tipoplatillo !="4"){
		$("#ComidaGR").css("display", "none");
		$("#DivCantidad").css("display", "");
		// $("#DivTotal").css("display", "");
		// $("#DivPrecio").css("display", "");
		// $("#DivComentario").css("display", "");
		
		let seleccionar = "<option value='0'> Seleccionar Platillo</option>";
		$('#txtProductoSeleccionadoGR').append(seleccionar);
		if(tipoplatillo =="0"){
			$("#DivCantidad").css("display", "none");
			// $("#DivTotal").css("display", "none");
			// $("#DivPrecio").css("display", "none");
			// $("#DivComentario").css("display", "none");
		}
	$("#txtNumPlatillo").val("1");
	$("#txtTotalPlatillo").val("49.00");
	}else{
		$("#ComidaGR").css("display", "");
		$("#DivCantidad").css("display", "none");
		// $("#DivTotal").css("display", "none");
		// $("#DivPrecio").css("display", "none");
		// $("#DivComentario").css("display", "none");
		$("#txtNumPlatillo").val("0");
		
		$.ajax({
            data: {"tipoplatillo": tipoplatillo, "txtUbicacion": txtUbicacion},
            url: "tipo_platillo",
            type: "POST",
             success: function(result) {
				let data = result.data;
				$('#txtProductoSeleccionadoGR').html('');
				if(data.length){
					let seleccionar = "<option>SSeleccione el Platillo</option>"
					for(i=0; i<data.length; i++){
						seleccionar += "<option value='"+data[i]['IdComida']+"'>"+data[i]['Comida']+"</option>";	
					}
					$('#txtProductoSeleccionadoGR').append(seleccionar);
				}
			}
		});
	}
});

$("#txtNumPlatillo").on("keyup", function() {
	let cantidad_platillos = $(this).val().toLowerCase();
	regex = /^[a-zA-Z ]+$/;
	this.value = (this.value + '').replace(/[^0-9]/g, '');
	if (regex.test(cantidad_platillos)) {
		ValidarPlatillos();
		return false;
	}
	if (cantidad_platillos.length > 1) {
		Swal.fire('Solo puedes pedir un platillo de comida.', "","info");
		ValidarPlatillos();
		return false;	
	}
	ValidarPlatillos();
});