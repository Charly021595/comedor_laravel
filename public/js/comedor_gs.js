var FechaHoy = "";
var FechaInicial = "";
var FechaFinal = "";
let fecha_completa = '';
var NumPreguntas = 0;
var NoAlimento = 0;
var NoDatosBioquimicos = 0;
let datos,
bandera_descargar = 0;

jQuery(function () {
    ObtenerFecha();
    // window.location.hash="no-back-button";
    // window.location.hash="Again-No-back-button";//esta linea es necesaria para chrome
    // window.onhashchange=function(){window.location.hash="no-back-button";}
	buscar_sede();
	active();
});

function active() {
	$("#link_dashoard").removeClass('active');
	$("#link_comedor_gs").addClass('active');
	$("#link_platillo_express").removeClass('active');
	$("#link_listado_procesados").removeClass('active');
	$("#link_listado_finalizados").removeClass('active');
}

function buscar_sede(){
	let formData = new FormData(document.getElementById("form_greenspot_comedor_semanal"));
	formData.append("dato", "valor");
	$.ajax({
		url: "get_sede",
		type: "post",
		data: formData,
		dataType: "html",
		cache: false,
		contentType: false,
		processData: false,
		success: function(result) {
			let data = JSON.parse(result);
			let sede = data.data.Sede;
			switch (sede) {
				case 'Torre TOP':
					$("#txtUbicacion").trigger("change").val(1);
				break;

				case 'Apodaca':
					$("#txtUbicacion").trigger("change").val(2);
				break;

				case 'Cienega':
					$("#txtUbicacion").trigger("change").val(3);
				break;
			
				default:
					$("#txtUbicacion").trigger("change").val(1);
				break;
			}
			MostrarInforme();
		}
	});
}

function ObtenerFecha(){
	var date = new Date();
	let dia = date.getDate(),
	mes = date.getMonth() + 1,
	year = date.getFullYear(),
	horas = date.getHours(),
	minutos = date.getMinutes(),
	segundos = date.getSeconds();
	fecha_completa =  year+'_'+mes+'_'+dia+'_'+horas+'_'+minutos+'_'+segundos;
	var currentDate = date.toISOString().substring(0,10);
	// $("#txtFechaSeleccionado").val(currentDate);
    $("#txtFechaDia").val(currentDate);
	let fecha_actual = moment(date).format('DD/MM/YYYY');
	$("#txtFechaSeleccionado").val(fecha_actual+' - '+fecha_actual);
}

function MostrarInforme(){
	let Fecha = $("#txtFechaSeleccionado").val(),
	txtUbicacion = $("#txtUbicacion").val();
	$("#txtNumeroEmpleado").val('');
	let ID = "";
	let RowID = 0;
	if (Fecha == "") {
		Swal.fire( 
			"El campo fecha no puede ir vacío",
			'',
			'info'
		);
		return false;
	}
	let formData = new FormData(document.getElementById("form_greenspot_comedor_semanal"));
  	formData.append("dato", "valor");
	formData.append("ubicacion", txtUbicacion);
	$("#filrado_empleado").hide();
	$("#input_text_no_empleado").hide();
	$("#EspacioTabla").hide();
	$("#div_tabla").hide();
	$("#div_fake").show();
	$("#div_fake_dos").show();
	$("#div_cargando").show();
	$.ajax({
		url: "listar_comida_gs",
		type: "post",
		data: formData,
		dataType: "html",
		cache: false,
		contentType: false,
		processData: false,
		success: function(result) {
			data = JSON.parse(result);
			if (data.status == "success") {
				$("#div_cargando").hide();
				$("#div_fake").hide();
				$("#div_fake_dos").hide();
				$("#filrado_empleado").show();
				$("#input_text_no_empleado").show();
				$("#boton_descarga_excel").show();
				$("#div_tabla").show();
				$("#EspacioTabla").show();
				$("#ContenidoListados").find("tr").remove();
				datos = data.data;
				for(var i=0; i < datos.length; i++){
					if(ID != datos[i].IdPedido){
						ID = datos[i].IdPedido;
						var tablacontenido ="<tr id='tr_"+datos[i].IdPedido+"'>";
						tablacontenido +="<td  id='IDPedido"+datos[i].IdPedido+"' data-label= 'No. Orden'>"+datos[i].IdPedido+"</td>"
						tablacontenido +="<td  id='IDNoEmpleado"+datos[i].IdPedido+"' data-label= 'No. Empleado'>"+datos[i].NoEmpleado+"</td>"
						tablacontenido +="<td  id='IDNomEmpleado"+datos[i].IdPedido+"'' data-label= 'Empleado'>"+datos[i].NombreEmpleado+"</td>"
						tablacontenido +="<td data-label= 'No. Platillo'>"+datos[i].Cantidad+"</td>"
						tablacontenido +="<td data-label= 'Platillo'>"+datos[i].Platillo+"</td>"
						// tablacontenido +="<td data-label= 'Comentarios' style='display:none;'>"+datos[i].Comentarios+"</td>"
						// tablacontenido +="<td data-label= 'Kcal' style='display:none;'>"+datos[i].Kcal+"</td>"
						tablacontenido +="<td data-label= 'Precio'>"+parseFloat(datos[i].Precio).toFixed(2)+"</td>"
						tablacontenido +="<td data-label= 'Total'>"+parseFloat(datos[i].total).toFixed(2)+"</td>"
						switch (datos[i]['Ubicacion']) {
							case "1":
								tablacontenido +="<td data-label= 'Ubicación'>Torre TOP</td>"
							break;

							case "2":
								tablacontenido +="<td data-label= 'Ubicación'>Apodaca</td>"
							break;

							case "3":
								tablacontenido +="<td data-label= 'Ubicación'>Cienega</td>"
							break;
						
							default:
								tablacontenido +="<td data-label= 'Ubicación'></td>"
							break;
						}
						tablacontenido += "<td data-label= 'FechaPedido' >"+datos[i].FechaPedido+"</td>"
						if (datos[i].EstatusEnviado == 0) {
							tablacontenido += "<td data-label= 'Estatus Enviado' >No Enviado</td>"
						}else if (datos[i].EstatusEnviado == 1) {
							tablacontenido += "<td data-label= 'Estatus Enviado' >Enviado</td>"
						}else if (datos[i].EstatusEnviado == 2) {
							tablacontenido += "<td data-label= 'Estatus Enviado' >Procesado en Nomina</td>"
						}else{
							tablacontenido += "<td data-label= 'Estatus Enviado' ></td>"
						}
						if (datos[i].EstatusComedor == 0) {
							tablacontenido += "<td data-label= 'Estatus Comedor'>Pendiente de Procesar</td>"
						}else if (datos[i].EstatusComedor == 1) {
							tablacontenido += "<td data-label= 'Estatus Comedor'>Entregado</td>"
						}else{
							tablacontenido += "<td data-label= 'Estatus Comedor'>Rechazado</td>"
						}
						tablacontenido += "<td data-label='' ><button id='btn_confirmar_green_pedido_"+datos[i].id+"' class='btn mi_btn_success'  onclick='ConfirmacionEstatusAlimentoGreen("+JSON.stringify(datos[i].IdPedido)+","+1+")'>Confirmar</button></td>"
						tablacontenido += "<td data-label='' ><button id='btn_rechazar_green_pedido_"+datos[i].id+"' class='btn btn-danger' onclick='RechazarEstatusAlimentoGreen("+JSON.stringify(datos[i].IdPedido)+","+2+")'>Eliminar</button></td>"
						
						tablacontenido +="</tr>";
						$('#ContenidoListados').append(tablacontenido);
						if (datos[i].EstatusComedor == 2) {
							$('#tr_'+datos[i].IdPedido).addClass("clase_rechazada");
						}
						deshabilitar_botones_green(datos[i].id, datos[i].EstatusComedor);
						RowID = 0;
						
					}else{
						RowID= RowID +1;
						var RowFinal = RowID +1;
						$("#IDPedido" +datos[i].IdPedido ).attr('rowspan', RowFinal);
						$("#IDNoEmpleado" +datos[i].IdPedido ).attr('rowspan', RowFinal);
						$("#IDNomEmpleado" +datos[i].IdPedido ).attr('rowspan', RowFinal);
						var tablacontenidoD ="<tr><td data-label= 'No. Platillo'>"+datos[i].Cantidad+"</td>"
						tablacontenidoD +="<td data-label= 'Platillo'>"+datos[i].Platillo+"</td>"
						// tablacontenidoD +="<td data-label= 'Comentarios' style='display:none;'>"+datos[i].Comentarios+"</td>"
						// tablacontenidoD +="<td data-label= 'Kcal' style='display:none;'>"+datos[i].Kcal+"</td>"
						tablacontenidoD +="<td data-label= 'Precio'>"+parseFloat(datos[i].Precio).toFixed(2)+"</td>"
						tablacontenidoD +="<td data-label= 'Total'>"+parseFloat(datos[i].total).toFixed(2)+"</td>"
						tablacontenidoD +="<td data-label= 'Ubicación'></td>"
						tablacontenidoD +="<td></td>"
						tablacontenidoD +="<td></td>"
						tablacontenidoD +="<td></td>"
						tablacontenidoD +="<td></td>"
						tablacontenidoD +="<td></td></tr>"
						$('#ContenidoListados').append(tablacontenidoD);
					}
				}
				if (bandera_descargar == 1) {
					DescargarTabla();
					bandera_descargar = 0;	
				}
			}else if (data.status == "error_fecha") {
				$("#div_cargando").hide();
				$("#EspacioTabla").hide();
				$("#div_tabla").hide();
				$("#boton_descarga_excel").hide();
				$("#filrado_empleado").hide();
				$("#div_fake").show();
				$("#div_fake_dos").show();
				Swal.fire( 
					data.message,
					'',
					'info'
				);
			}else{
				$("#div_cargando").hide();
				$("#EspacioTabla").hide();
				$("#div_tabla").hide();
				$("#boton_descarga_excel").hide();
				$("#filrado_empleado").hide();
				$("#div_fake").show();
				$("#div_fake_dos").show();
				Swal.fire( 
					data.message,
					'',
					'error'
				);
			}
		}	
	});
}

function deshabilitar_botones_green(id, estatus_comedor){
	if (estatus_comedor == 0) {
		$("#btn_confirmar_green_pedido_"+id).removeAttr("disabled, disabled");
		$("#btn_confirmar_green_pedido_"+id).removeClass("deshabilitar");
		$('#btn_confirmar_green_pedido_'+id).attr("disabled", false);
		$("#btn_rechazar_green_pedido_"+id).removeAttr("disabled, disabled");
		$("#btn_rechazar_green_pedido_"+id).removeClass("deshabilitar");
		$('#btn_rechazar_green_pedido_'+id).attr("disabled", false);
	}else if(estatus_comedor == 1 || estatus_comedor == 2){
		$("#btn_confirmar_green_pedido_"+id).addClass("deshabilitar");
		$('#btn_confirmar_green_pedido_'+id).attr("disabled", true);
		$("#btn_rechazar_green_pedido_"+id).addClass("deshabilitar");
		$('#btn_rechazar_green_pedido_'+id).attr("disabled", true);
	} 
}

function ConfirmacionEstatusAlimentoGreen(id_pedido, estatus_comedor){
	let token = $('input[name=_token]').val();
	Swal.fire({
		title: '¿Quieres confirmar el pedido?',
		icon: 'info',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Confirmar',
		cancelButtonText: 'Cancelar'
	  }).then((res) => {
		if (res.isConfirmed) {$.ajax({
			url: "cambiar_estatus_pedido",
			type: "post",
			data: {"_token": token, "id_pedido":id_pedido, "estatus_comedor":estatus_comedor},
			success: function(result) {
				data = result;
				if (data.estatus == 'success') {
					Swal.fire(
						'Confirmado',
						'Tu platillo se confirmo.',
						'success'
					  ).then(function(){
						  MostrarInforme();
					  });
				}else{
					if (res.isConfirmed) {
						Swal.fire( 
							data.mensaje,
							'',
							'error'
						);
					}
				}
			}
		});
		}	
	});
}

// function RechazarEstatusAlimentoGreen(id_pedido, estatus_comedor){
// 	Swal.fire({
// 		title: '¿Quieres rechazar el pedido?',
// 		icon: 'info',
// 		showCancelButton: true,
// 		confirmButtonColor: '#3085d6',
// 		cancelButtonColor: '#d33',
// 		confirmButtonText: 'Rechazar',
// 		cancelButtonText: 'Cancelar'
// 	  }).then((res) => {
// 		if (res.isConfirmed) {
// 			$.ajax({
// 				url: "../../utileria.php",
// 				type: "post",
// 				data: {"param":12, "id_pedido":id_pedido, "estatus_comedor":estatus_comedor},
// 				success: function(result) {
// 					data = JSON.parse(result);
// 					if (data.estatus == 'success') {
// 						Swal.fire(
// 							'Rechazado',
// 							'Tu platillo fue Rechazado.',
// 							'success'
// 						  ).then(function(){
// 							  MostrarInforme();
// 						  });
// 					}else{
// 						if (res.isConfirmed) {
// 							Swal.fire( 
// 								data.mensaje,
// 								'',
// 								'error'
// 							);
// 						}
// 					}
// 				}
// 			});
// 		}	
// 	});
// }

$("#txtNumeroEmpleado").on('change',function(e){
	let Fecha = $("#txtFechaSeleccionado").val(),
	numero_empleado = $(this).val().toLowerCase(),
	txtUbicacion = $("#txtUbicacion").val();
	let ID = "";
	let RowID = 0;
	regex = /^[a-zA-Z ]+$/;
	this.value = (this.value + '').replace(/[^0-9]/g, '');
	if (regex.test(numero_empleado)) {
		return false;
	}
	if (Fecha == "") {
		Swal.fire( 
			"El campo fecha no puede ir vacío",
			'',
			'info'
		);
		return false;
	}
	if (numero_empleado.length <= 3 && numero_empleado != '') {
		return false;	
	}
	let formData = new FormData(document.getElementById("form_greenspot_comedor_semanal"));
  	formData.append("dato", "valor");
	formData.append("numero_empleado", numero_empleado);
	formData.append("ubicacion", txtUbicacion);
	$("#EspacioTabla").hide();
	$("#div_tabla").hide();
	$("#div_cargando").show();
	$.ajax({
		url: "listar_comida_gs",
		type: "post",
		data: formData,
		dataType: "html",
		cache: false,
		contentType: false,
		processData: false,
		success: function(result) {
			data = JSON.parse(result);
			if (data.estatus == "success") {
				$("#loading_comedor").hide();
				$("#boton_descarga_excel").show();
				$("#EspacioTabla").show();
				$("#ContenidoListados").find("tr").remove();
				datos = data.data;
				for(var i=0; i < datos.length; i++){
					if(ID != datos[i].IdPedido){
						ID = datos[i].IdPedido;
						var tablacontenido ="<tr>";
						tablacontenido +="<td  id='IDPedido"+datos[i].IdPedido+"' data-label= 'No. Orden'>"+datos[i].IdPedido+"</td>"
						tablacontenido +="<td  id='IDNoEmpleado"+datos[i].IdPedido+"' data-label= 'No. Empleado'>"+datos[i].NoEmpleado+"</td>"
						tablacontenido +="<td  id='IDNomEmpleado"+datos[i].IdPedido+"'' data-label= 'Empleado'>"+datos[i].NombreEmpleado+"</td>"
						tablacontenido +="<td data-label= 'No. Platillo'>"+datos[i].NoPlatillo+"</td>"
						tablacontenido +="<td data-label= 'Platillo'>"+datos[i].Platillo+"</td>"
						// tablacontenido +="<td data-label= 'Comentarios' style='display:none;'>"+datos[i].Comentarios+"</td>"
						// tablacontenido +="<td data-label= 'Kcal' style='display:none;'>"+datos[i].Kcal+"</td>"
						tablacontenido +="<td data-label= 'Precio'>"+parseFloat(datos[i].Precio).toFixed(2)+"</td>"
						tablacontenido +="<td data-label= 'Total'>"+parseFloat(datos[i].total).toFixed(2)+"</td>"
						switch (datos[i]['Ubicacion']) {
							case 1:
								tablacontenido +="<td data-label= 'Ubicación'>Torre TOP</td>"
							break;

							case 2:
								tablacontenido +="<td data-label= 'Ubicación'>Apodaca</td>"
							break;

							case 3:
								tablacontenido +="<td data-label= 'Ubicación'>Cienega</td>"
							break;
						
							default:
								tablacontenido +="<td data-label= 'Ubicación'></td>"
							break;
						}
						tablacontenido += "<td data-label= 'FechaPedido' >"+datos[i].FechaPedido+"</td>"
						if (datos[i].EstatusEnviado == 0) {
							tablacontenido += "<td data-label= 'Estatus Enviado' >No Enviado</td>"
						}else if (datos[i].EstatusEnviado == 1) {
							tablacontenido += "<td data-label= 'Estatus Enviado' >Enviado</td>"
						}else if (datos[i].EstatusEnviado == 2) {
							tablacontenido += "<td data-label= 'Estatus Enviado' >Procesado en Nomina</td>"
						}else{
							tablacontenido += "<td data-label= 'Estatus Enviado' ></td>"
						}
						if (datos[i].EstatusComedor == 0) {
							tablacontenido += "<td data-label= 'Estatus Comedor'>Pendiente de Procesar</td>"
						}else if (datos[i].EstatusComedor == 1) {
							tablacontenido += "<td data-label= 'Estatus Comedor'>Entregado</td>"
						}else{
							tablacontenido += "<td data-label= 'Estatus Comedor'>Rechazado</td>"
						}
						tablacontenido += "<td data-label='' ><button id='btn_confirmar_green_pedido_"+datos[i].id+"' class='btn mi_btn_success'  onclick='ConfirmacionEstatusAlimentoGreen("+JSON.stringify(datos[i].IdPedido)+","+1+")'>Confirmar</button></td>"
						tablacontenido += "<td data-label='' ><button id='btn_rechazar_green_pedido_"+datos[i].id+"' class='btn btn-danger' onclick='RechazarEstatusAlimentoGreen("+JSON.stringify(datos[i].IdPedido)+","+2+")'>Eliminar</button></td>"
						
						tablacontenido +="</tr>";
						$('#ContenidoListados').append(tablacontenido);
						deshabilitar_botones_green(datos[i].id, datos[i].EstatusComedor);
						RowID = 0;
					}else{
						RowID= RowID +1;
						var RowFinal = RowID +1;
						$("#IDPedido" +datos[i].IdPedido ).attr('rowspan', RowFinal);
						$("#IDNoEmpleado" +datos[i].IdPedido ).attr('rowspan', RowFinal);
						$("#IDNomEmpleado" +datos[i].IdPedido ).attr('rowspan', RowFinal);
						var tablacontenidoD ="<tr><td data-label= 'No. Platillo'>"+datos[i].NoPlatillo+"</td>"
						tablacontenidoD +="<td data-label= 'Platillo'>"+datos[i].Platillo+"</td>"
						// tablacontenidoD +="<td data-label= 'Comentarios'>"+datos[i].Comentarios+"</td>"
						// tablacontenidoD +="<td data-label= 'Kcal' style='display:none;'>"+datos[i].Kcal+"</td>"
						tablacontenidoD +="<td data-label= 'Precio'>"+parseFloat(datos[i].Precio).toFixed(2)+"</td>"
						tablacontenidoD +="<td data-label= 'Total'>"+parseFloat(datos[i].total).toFixed(2)+"</td>"
						tablacontenidoD +="<td data-label= 'Ubicación'></td>"
						tablacontenidoD +="<td></td>"
						tablacontenidoD +="<td></td>"
						tablacontenidoD +="<td></td>"
						tablacontenidoD +="<td></td><td></td></tr>"
						$('#ContenidoListados').append(tablacontenidoD);
					}
				}
			}else if (data.estatus == "error_fecha") {
				Swal.fire( 
					"Existe error en ña fecha",
					'',
					'info'
				);
				$("#loading_comedor").hide();
				$("#EspacioTabla").hide();
				$("#div_tabla").hide();
				$("#boton_descarga_excel").hide();
			}else{
				Swal.fire( 
					"No coincide el número de empleado con ningun registro",
					'',
					'info'
				);
				$("#loading_comedor").hide();
				$("#EspacioTabla").hide();
				$("#div_tabla").hide();
				$("#boton_descarga_excel").hide();
			}
		}	
	});
});

$("#txtNumEmpleadoLogeado").on('change',function(e){
	$("#div_mostrar_tabla_pedido").hide();
	let fechaActualL = new Date(); //Fecha actual
	let fechaActual2 = moment(fechaActualL).format("YYYY-MM-DD"),
	token = $('input[name=_token]').val();
	$("#txtFechaPedido").val(fechaActual2);
	let empleado = $("#txtNumEmpleadoLogeado").val();
	if(empleado.replace(/\s/g,"") != ""){
		$.ajax({
            type: "POST",
			data: {"_token": token, "empleado":empleado},
            url: "get_datos_usuario",
            dataType: 'JSON',
             success: function(result) {
				if (result.status == 'success') {
					let data = result.data;
					let FechaAr =  "Fecha: "+ fechaActual2; 
					$("#txtFechaDia").val(fechaActual2);
					$("#txtNombreEmpleadoLogeado").val(data.nombre);
					$("#tipo_empleado").val(data.Turno);
					$("#txtTipoPlatillo").trigger("change").val("0");
					$("#txtProductoSeleccionadoGR").trigger("change").val(0);
					$("#txtNumPlatilloGR").val(0);
					$("#txtPrecioTotal").val(0);
					$("#txtComentariosGR").val("");
					$("#txtTipoPlatillo").trigger("change").val(4);
					TipoPlatillo();
					$("#ListadoComidaGr").html("");
				}else{
					Swal.fire( 
						'El número de empleado ingresado no existe.',
						'',
						'error'
					);
					$("#txtFechaDia").val("");
					$("#txtNombreEmpleadoLogeado").val("");
					$("#txtNumEmpleadoLogeado").val("");
				}
			}
		});
	}else{
		Swal.fire( 
			'Favor de Agregar un numero de empleado.',
			'',
			'error'
		);
		//CerrarSesion();
		$("#txtFechaDia").val("");
		$("#txtNombreEmpleadoLogeado").val("");
		$("#txtNumEmpleadoLogeado").val("");
	}
});

function CargarPedido(){
	$("#div_mostrar_tabla_pedido").hide();
	$("#txtNumEmpleadoLogeado").val("");
	$("#txtNombreEmpleadoLogeado").val("");
	$("#txtTipoPlatillo").val("0");
	TipoPlatillo();
	$("#txtNumPlatillo").val("1");
	$("#txtComentarioPlatillo").val("");
	LimpiarCampos();
	$('#ModalCargaEvidenciaVisual').modal('show');
	let ubicacion = $("#txtUbicacion").val();
	// if (ubicacion == 1) {
		$("#txtTipoPlatillo").trigger("change").val(4);
		TipoPlatillo();
	// }
}

function TipoPlatillo(){
	let txtUbicacion = $("#txtUbicacion").val(),
	//token = $('#token').val();
	token = $('input[name=_token]').val();
	var tipoplatillo = $("#txtTipoPlatillo").val();
	$("#txtProductoSeleccionadoGR").empty();
	$("#ListadoComidaGr").find("tr").remove();
	 LimpiarCampos();
	if(tipoplatillo !="4"){
		$("#ComidaGR").css("display", "none");
		$("#DivCantidad").css("display", "");
		$("#DivTotal").css("display", "");
		// $("#DivPrecio").css("display", "");
		$("#DivComentario").css("display", "");
		
		
		var seleccionar = "<option value='0'> Seleccionar Platillo</option>";
		$('#txtProductoSeleccionadoGR').append(seleccionar);
		if(tipoplatillo =="0"){
			$("#DivCantidad").css("display", "none");
			$("#DivTotal").css("display", "none");
			// $("#DivPrecio").css("display", "none");
			$("#DivComentario").css("display", "none");
		}
	$("#txtNumPlatillo").val("1");
	$("#txtTotalPlatillo").val("49.00");
	}
	else{
		$("#ComidaGR").css("display", "");
		$("#DivCantidad").css("display", "none");
		$("#DivTotal").css("display", "none");
		// $("#DivPrecio").css("display", "none");
		$("#DivComentario").css("display", "none");
		$("#txtNumPlatillo").val("0");
		
		$.ajax({
            type: "POST",
            data: {"_token": token, "tipoplatillo": tipoplatillo, "txtUbicacion": txtUbicacion},
            url: "tipo_platillo",
             success: function(result) {;
				let data = result.data;
				$('#txtProductoSeleccionadoGR').html('');
				if(data.length){
					let seleccionar = "<option value='0'>Seleccione el Platillo</option>"
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
	//txtProductoSeleccionadoGR
	let InfoPlatillo_select = $("#txtProductoSeleccionadoGR").val(),
	token = $('input[name=_token]').val();
	//
	$("#txtComentariosGR").val("");
	$("#txtNumPlatilloGR").val(1);
	$("#txtPrecioGR").val("0.00");
	$("#txtCaloriasGR").val("");
	$("#txtPrecioTotal").val("0.00");
	var TipoPlatillo = $("#txtTipoPlatillo").val();
	$("#txtTipoPlatilloGR").val(TipoPlatillo);
	
	//
	if(InfoPlatillo_select !="0" && InfoPlatillo_select != null){
		$.ajax({
			type: "POST",
            data: {"_token": token, "InfoPlatillo": InfoPlatillo_select},
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

function AgregarComidaGr(){
	NoAlimento = 0;
	var Platillo = $('select[name="txtProductoSeleccionadoGR"] option:selected').text();
	var IdPlatillo = $("#txtProductoSeleccionadoGR").val();
	var Kcal = $("#txtCaloriasGR").val();
	var Cantidad = $("#txtNumPlatilloGR").val();
	var PrecioTotal = $("#txtPrecioTotal").val();
	var PrecioUnitario = $("#txtPrecioGR").val();
	var tipoplatillo = $("#txtTipoPlatilloGR").val();
	//var ComentariosGR = $("#txtComentariosGR").val();
	var ComentariosGR =document.getElementById("txtComentariosGR").value;
	if(Platillo !="0" && PrecioTotal != "0.00"){
		$("#div_mostrar_tabla_pedido").show();
		NoAlimento = NoAlimento +1;
		var NuevoAlimento = '<tr id="Alimento'+NoAlimento+'">';
		//style="display:none"
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Posición" style="display:none" id="tdPosiciónAlimento'+NoAlimento+'">'+NoAlimento+'</td>';
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Id. Platillo" style="display:none" id ="tdIdPlatillo'+NoAlimento+'">'+IdPlatillo+'</td>';
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Platillo" id ="tdPlatillo'+NoAlimento+'">'+Platillo+'</td>';
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Comentario" style="display:none" id ="tdComentarioPlatillo'+NoAlimento+'" >'+ComentariosGR+'</td>';
		
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Tipo Platillo" style="display:none" id ="tdTipoPlatillo'+NoAlimento+'">'+tipoplatillo+'</td>';
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Kcal." id ="tdKcalPlatillo'+NoAlimento+'" style="display:none;">'+Kcal+'</td>';
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Cantidad" id ="tdCantidadPlatillo'+NoAlimento+'">'+Cantidad+'</td>';
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Precios" id ="tdPrecioUnitarioPlatillo'+NoAlimento+'" style="display:none">'+PrecioUnitario+'</td>';
		NuevoAlimento =  NuevoAlimento + '<td data-label= "Total" id ="tdPrecioTotalPlatillo'+NoAlimento+'" style="display:none">'+PrecioTotal+'</td>';
		NuevoAlimento =  NuevoAlimento + "<td data-label='' ><button onclick='ConfirmacionEliminaAlimento("+NoAlimento+")'>Eliminar</button></td></tr>"   
		$('#ListadoComidaGr').append(NuevoAlimento);
		LimpiarCampos();
			
	}else{
		// $("#div_mostrar_tabla_pedido").hide();
		Swal.fire( 
			'Favor de agregar un platillo.',
			'',
			'info'
		);
	}			
}

function ConfirmacionEliminaAlimento(NoAlimento){
	var NoAlimentos = NoAlimento;
		Swal.fire({
			title: '¿Deseas eliminar el registro?',
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Eliminar',
			cancelButtonText: 'Cancelar'
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

function DescargarTabla(){
	$("#TablaComedor").table2excel({
		filename: "Comedor_Green_Spot_"+fecha_completa+'.xls'
	});
}

$("#btn_nomina").on("click", function(e){
	$("#btn_nomina").addClass("deshabilitar");
  	$('#btn_nomina').attr("disabled", true);
	let fecha = $('#txtFechaSeleccionado').val(),
	numero_empleado = $('#txtNumeroEmpleado').val(),
	token = $('input[name=_token]').val();
	$.ajax({
		url: "nomina",
		type: "post",
		data: {"_token": token, "daterange":fecha, "numero_empleado":numero_empleado},
		success: function(result) {
			console.log(result);
			if (result.estatus == "success"){
				enviar_nomina(result);
			}else{
				Swal.fire(result.message, "","info");
				$("#btn_nomina").removeAttr("disabled, disabled");
				$("#btn_nomina").removeClass("deshabilitar");
				$('#btn_nomina').attr("disabled", false);
			}
		}
	}); 
});

function enviar_nomina(resultados){
	let datos2 = resultados,
	token = $('input[name=_token]').val();
	for (let i = 0; i < datos.length; i++) {
		if (datos[i].EstatusComedor == 0) {
			Swal.fire('Sin Envio', "este pedido no puede ser enviado a nomina porque no esta confirmado o rechazado, No. Orden: "+datos[i].IdPedido,"info");
			$("#btn_nomina").removeAttr("disabled, disabled");
			$("#btn_nomina").removeClass("deshabilitar");
			$('#btn_nomina').attr("disabled", false);
			return;
		}
		if (datos[i].EstatusEnviado == 1) {
			let index = i;	
			if (index > -1) {
				datos.splice(index, 1);
			 }
		}
	}
	$.ajax({
		url: "enviar_nomina",
		type: "post",
		data: {"_token": token, "datos":datos2, "estatus_enviado":1},
		success: function(result) {
			data = result;
			if (data.estatus == "success"){
				Swal.fire('Estatus Envio', "El estatus enviado se actualizo correctamente", "success");
				bandera_descargar = 1;
				$("#btn_nomina").removeAttr("disabled, disabled");
				$("#btn_nomina").removeClass("deshabilitar");
				$('#btn_nomina').attr("disabled", false);
				MostrarInforme();
			}else{
				Swal.fire('No hay Registros pendientes de pago', "","info");
				$("#btn_nomina").removeAttr("disabled, disabled");
				$("#btn_nomina").removeClass("deshabilitar");
				$('#btn_nomina').attr("disabled", false);
			}
		}
	});
}

$("#solicitar_comida").on("click", function(e){
	let TipoPlatillo = $("#txtTipoPlatillo").val(),
	Ubicacion = $("#txtUbicacion").val(),
	Total = $("#txtTotalPlatillo").val(),
	Precio = $("#txtPrecioPlatillo").val(),
	CantidadArreglo = "",
	arrayListadoComida = [];
	$("#solicitar_comida").prop("disabled", true);
	//
	e.preventDefault();
	
	let arrayListadoGreenSpot = {};
	fechaActualL = new Date(), //Fecha actual
	FechaDeOrden = moment(fechaActualL).format("YYYY-MM-DD HH:mm:ss");
	dia_actual = moment(fechaActualL).format('DD'),
	nombre_dia_actual = moment(fechaActualL).format('dddd');
	switch (nombre_dia_actual) {
		case 'Saturday':
			dia_inicial = dia_actual - 1;
			FechaDeOrden = moment(fechaActualL).format("YYYY-MM-"+dia_inicial+" HH:mm:ss");
		break;
		case 'Sunday':
			dia_inicial = dia_actual - 2;
			FechaDeOrden = moment(fechaActualL).format("YYYY-MM-"+dia_inicial+" HH:mm:ss");
		break;
	
		default:
		break;
	}
	//
	if(TipoPlatillo == "4"){
		arrayListadoGreenSpot = GuardarListadoGreenSpot();
		CantidadArreglo = arrayListadoGreenSpot.length; 
	}else{
		Swal.fire('Debes seleccionar tipo de platillo', "","info");
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
	let formData = new FormData(document.getElementById("form_solicitar_platillos_gs"));
	formData.append("dato", "valor");
	formData.append("TipoPlatillo", TipoPlatillo);
	formData.append("Ubicacion", Ubicacion);
	formData.append("FechaDeOrden", FechaDeOrden);
	formData.append("arrayListadoGreenSpot",  JSON.stringify(arrayListadoGreenSpot));
	formData.append("pedidoporcomedor", 1);
	$.ajax({
		url: "guardar_platillo_gs",
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
				$("#solicitar_comida").prop("disabled", false);
			}
		}
	});
});

function GuardarListadoGreenSpot() {
    var arrayListadoComida = [];
        $("#ListadoComidaGr tr").each(function(index, value) {
            var Posicion, IdPlatillo, Platillo, Comentario, TipoPlatillo, KCal, Cantidad, Precios, Total;
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
            var Array = {};
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

$('input[name="daterange"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
        format: 'DD/MM/YYYY',
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],      
    }
  });
  
$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
$(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
});

$('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {

});

$("#btnClearDate").on("click", function(){
$('input[name="daterange"]').val('');
});

$('input[name="daterange"]').val('');

$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
$(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
});

$('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
});

$("#btnClearDate").on("click", function(){
$('input[name="daterange"]').val('');
});

$("#txtUbicacion").on('change',function(e){
    let txtUbicacion = $("#txtUbicacion").val();
	var tipoplatillo = $("#txtTipoPlatillo").val();
	$("#txtProductoSeleccionadoGR").empty();
	$("#ListadoComidaGr").find("tr").remove();
	 LimpiarCampos();
	if(tipoplatillo !="4"){
		$("#ComidaGR").css("display", "none");
		$("#DivCantidad").css("display", "");
		// $("#DivTotal").css("display", "");
		// $("#DivPrecio").css("display", "");
		// $("#DivComentario").css("display", "");
		
		var seleccionar = "<option value='0'> Seleccionar Platillo</option>";
		// $('#txtProductoSeleccionadoGR').append(seleccionar);
		if(tipoplatillo =="0"){
			$("#DivCantidad").css("display", "none");
			// $("#DivTotal").css("display", "none");
			// $("#DivPrecio").css("display", "none");
			// $("#DivComentario").css("display", "none");
		}
	$("#txtNumPlatillo").val("1");
	$("#txtTotalPlatillo").val("49.00");
	}
	else{
		$("#ComidaGR").css("display", "");
		$("#DivCantidad").css("display", "none");
		// $("#DivTotal").css("display", "none");
		// $("#DivPrecio").css("display", "none");
		// $("#DivComentario").css("display", "none");
		$("#txtNumPlatillo").val("0");
		
		$.ajax({
            type: "POST",
            data: {
                param: 4,
				tipoplatillo: tipoplatillo,
				txtUbicacion: txtUbicacion
            },
            url: "utileria.php",
            dataType: 'JSON',
             success: function(data) {
				$("#txtProductoSeleccionadoGR").html("");
				if(data.length){
					var seleccionar = "<option value='0'>Seleccione el Platillo</option>"
					for(i=0;i<data.length;i++){
						//$("#NombreCont2").text(data[i]['Nombre']);
						//$("#NombreCont").text(data[i]['Nombre']);
						
							seleccionar += "<option value='"+data[i]['IdComida']+"'>"+data[i]['Comida']+"</option>";
							
						
					}
					$('#txtProductoSeleccionadoGR').append(seleccionar);
				}
			}
		});
	}
});
