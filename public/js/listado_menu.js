let table_platillos, 
datos,
bandera_descargar = 0;

jQuery(function () {
    // ObtenerFecha();
    // window.location.hash="no-back-button";
    // window.location.hash="Again-No-back-button";//esta linea es necesaria para chrome
    // window.onhashchange=function(){window.location.hash="no-back-button";}
	buscar_sede();
	active();
});

function active() {
	$("#link_dashoard").removeClass('active');
	$("#link_comedor_gs").removeClass('active');
	$("#link_platillo_express").removeClass('active');
    $("#link_listado_menu").addClass('active');
	$("#link_listado_procesados").removeClass('active');
	$("#link_listado_finalizados").removeClass('active');
}

function buscar_sede(){
	let formData = new FormData(document.getElementById("form_listado_menu"));
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
			// MostrarInforme();
		}
	});
}

function cargargrid_platillos(){
	$('#mostrar_platillos').hide();
	$("#cargando_tabla").show();
	$.ajax({
		url:"listar_platillos",
		type:"get",
		success: function(resultado){
			console.log(resultado);
		if (resultado != '' && resultado != null) {
			$("#cargando_tabla").hide();
			$('#mostrar_platillos').show();
			listar_platillos(resultado);
		}else{
			Swal.fire( 
			'No hay datos que mostrar',
			'',
			'error'
			);
			$("#cargando_tabla").hide();
			$('#mostrar_platillos').hide();
			return false;
		}
		}
	});
}

function listar_platillos(datos){
    if(table_platillos != null && table_platillos != ''){
        table_platillos.clear().draw();
        table_platillos.destroy();
    }
    // $("#tabla_jugadores > tbody").html('');
    table_platillos = $("#tabla_platillos").DataTable({
        "order": [],
        "targets": "no-sort",
        "ordertable": false,
        data: datos,
        "columns":[
            {"data":"Comida"},
            {"data":"Precio"},
            {"data":"TipoComida"},
            {"data":"Calorias"},
            {"data":"Estatus"},
            {"data":"Ubicacion"},
            {"data":"usuario"},
            {"defaultContent":`<button id="editar_jugador_modal" class="btn btn-primary" data-toggle="modal" data-target="#editar_jugador">
                <i class='fa fa-edit'></i>
            </button>`}
        ],

        // createdRow: function(row, data, index){
        //     if (data.Img != '') {
        //         $("td", row).eq(5).html(`
        //             <img src="${data.Img}" class="imagenes_bd">
        //         `);
        //     }else{
        //         $("td", row).eq(5).html(`
        //             <h1><i class="fa-solid fa-eye-slash"></i></h1>
        //         `);
        //     }
        //     if (data.ImgQR != '') {
        //         $("td", row).eq(6).html(`
        //             <img src="${data.ImgQR}" class="imagenes_bd">
        //         `);
        //     }else{
        //         $("td", row).eq(6).html(`
        //             <h1><i class="fa-solid fa-eye-slash"></i></h1>
        //         `);
        //     }
        // },

        "columnDefs": [
            { width: "auto", targets: "_all" },
            {"className": "text-center", "targets": "_all"}
        ],

        fixedColumns: true,
    
        "language": idioma_espanol,

        dom: "<'row'<'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12'<'row'"
        +"<'col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 col-xxl-2'l>"
        +"<'col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4 botones_datatables'B>"
        +"<'col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6'f>>>>"
                +"<rt>"
                +"<'row'<'col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12'<'form-inline'"
                +"<'col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6'i>"
                +"<'col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 float-rigth'p>>>>",
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            {
            extend:'excelHtml5',
            text:'<i class="fa-solid fa-file-excel"></i>',
            titleAttr: 'Excel',
            filename: 'Listado_jugadores',
            autoFilter: true,
            exportOptions: {
                stripHtml: true,
                columns: [ 0, 1, 2, 3, 4]
            },
            sheetName: 'Listado jugadores',
            excelStyles: {                  // Add an excelStyles definition
                template: "green_medium",  // Apply the 'blue_medium' template
            }
            },
            { 
            extend: 'pdfHtml5',
            text:'<i class="fa-solid fa-file-pdf"></i>',
            titleAttr: 'PDF',
            title:'Listado_Jugadores',
            exportOptions: {
                stripHtml: true,
                columns: [ 0, 1, 2, 3, 4]
            },
            // messageTop: today_actual,
            download: 'open',
            filename: 'Listado_jugadores_pdf',
                customize:function(doc) {
                doc.styles.title = {
                    color: '#0xff525659',
                    fontSize: '20',
                    alignment: 'left'
                }
                doc.styles.message = {
                    color: 'black',
                    fontSize: '10',
                    alignment: 'right'
                }
                doc.styles.tableHeader = {
                    fillColor:'#0xff525659',
                    color:'white',
                    alignment:'left'
                }
                doc.styles.tableBodyEven = {
                    alignment: 'left'
                }
                doc.styles.tableBodyOdd = {
                    alignment: 'left'
                }
                doc.styles['td:nth-child(2)'] = { 
                    width: '100px'
                }
            }
            },
            {
            extend:'csvHtml5',
            text:'<i class="fa-solid fa-file-csv"></i>',
            titleAttr: 'CSV',
            filename: 'Listado_reservaciones_'+nombre_archivo,
            },
            {
            extend:'copyHtml5',
            text:'<i class="fa fa-clipboard" aria-hidden="true"></i>',
            titleAttr: 'Copiar',
            filename: 'Listado_reservaciones_'+nombre_archivo,
            },
            {
            extend:'print',
            text:'<i class="fa fa-print" aria-hidden="true"></i>',
            titleAttr: 'Imprimir',
            filename: 'Listado_reservaciones_'+nombre_archivo,
            },
        ],
        
        initComplete: function(settings, json) {
            $("#tabla_platillos").removeClass("hide");
            $("#tabla_platillos").show();
            $("#cargando_tabla").hide();
        }
    });
    obtener_data_jugadores_editar("#tabla_platillos tbody", table_platillos);
}

$('#listado').click(function() {
    $('#registro_platillo').fadeOut('slow');
	$('#listado_platillos').fadeIn('slow');
	cargargrid_platillos();
});

$("#lblImagenComida").click(function() {
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});
	});
});

$("#registrar_platillo").on("click", function(e){
	let nombre_comida = $("#nombre_comida").val(),
	precio_comida = $("#precio_comida").val(),
	tipo_comida = $("#tipo_comida").val(),
	calorias = $("#calorias").val(),
	estatus_comida = $("#estatus_comida").val(),
	ubicacion_comida = $("#ubicacion_comida").val(),
	imagen_comida = $("#imagen_comida").val();
	$("#registrar_platillo").prop("disabled", true);
	e.preventDefault();
	
	if (nombre_comida == "") {
        Swal.fire('El nombre del platillo no puede ir vacío', "","info");
		$("#registrar_platillo").prop("disabled", false);
        return false;
    }
	if (precio_comida == "" || precio_comida == "0") {
        Swal.fire('El precio del platillo no puede ir vacío u 0', "","info");
		$("#registrar_platillo").prop("disabled", false);
        return false;
    }
	if (tipo_comida == "") {
        Swal.fire('El tipo de comida no puede ir vacío', "","info");
		$("#registrar_platillo").prop("disabled", false);
        return false;
    }
	if (calorias == '') {
        Swal.fire('Las calorias no pueden ir vacias', "","info");
		$("#registrar_platillo").prop("disabled", false);
        return false;
    }
	if (estatus_comida == '') {
        Swal.fire('El estatus no pueden ir vacío', "","info");
		$("#registrar_platillo").prop("disabled", false);
        return false;
    }
	if (ubicacion_comida == '') {
        Swal.fire('La ubicación no pueden ir vacia', "","info");
		$("#registrar_platillo").prop("disabled", false);
        return false;
    }
	let formData = new FormData(document.getElementById("form_listado_menu"));
	formData.append("dato", "valor");
	$.ajax({
		url: "guardar_nuevo_platillo",
		type: "post",
		data: formData,
		dataType: "html",
		cache: false,
		contentType: false,
		processData: false,
		success: function(result) {
			datos = JSON.parse(result);
			console.log(datos);
			if (datos.status === "success") {
				Swal.fire('El platillo se guardo correctamente.', "Platillo de comida guardado.","success");
			}else if(datos.status === "platillo duplicado"){
				Swal.fire('Este platillo ya fue ingresado.', "","info");
				$("#registrar_platillo").prop("disabled", false);
			}else{
				Swal.fire('Ocurrio un problema al registrar tu platillo intentalo mas tarde.', "","error");
				$("#registrar_platillo").prop("disabled", false);
			}
		}
	});
});

let idioma_espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}