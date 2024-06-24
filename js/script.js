var codigo = "";
const urlBase = "./php/";

window.addEventListener("load", function (event) {
    xFecha = new Date();
    //console.log();

    if(document.getElementById("fechaCons")){
        document.getElementById("fechaCons").innerHTML = traducirFecha(xFecha.toDateString());
    }

    if(document.getElementById("error01")){
        if((document.getElementById("error01").textContent).trim() !== ""){
            $("#txtusuario").css("border", "1px solid #ff4646");
            //$("#error01").css("color", "#ff4646");
        }else{
            $("#txtusuario").css("border", "1px solid #ced4da");
        }
    }

    if(document.getElementById("error02")){
        if((document.getElementById("error02").textContent).trim() !== ""){
            $("#txtcontra").css("border", "1px solid #ff4646");
           // $("#error02").css("color", "#ff4646");
        }else{
            $("#txtcontra").css("border", "1px solid #ced4da");
        }
    }

    if(document.getElementById("txtusuario")){
        
        document.getElementById("txtusuario").addEventListener("keypress", (tecla) =>{
            
            $("#txtusuario").val( document.getElementById("txtusuario").value.toUpperCase());
            $("#txtusuario").css("border", "1px solid #ced4da");
        });
        /*document.querySelector("#txtusuario").addEventListener("change",function (){
            this.value = this.value.toUpperCase();
        });*/
    }

    if(document.getElementById("txtcontra")){
        document.getElementById("txtcontra").addEventListener("keypress", (tecla) =>{
            //console.log(tecla);
            $("#txtcontra").css("border", "1px solid #ced4da");
        });
    }

});

if(document.getElementById("btnConsulta")){
    document.getElementById("btnConsulta").addEventListener("click", function(e){
        e.preventDefault();
        consultarSaldo();
    });
}

/*document.getElementById("btnCerrarSesion").addEventListener("click", function(e){
    e.preventDefault();
    cerrarSesion();
});*/

function consultarSaldo(){  
    $(".pl").css("display", "block");
    codigo = $("#txtCodigo").val();
    let saldoBodega = $("#saldoBodega");
    let saldoGondola = $("#saldoGondola");
    let ventasDia = $("#ventasDia");
    let ventasTurno = $("#ventasTurno");
    let devolDia = $("#devolDia");
    let devolTurno = $("#devolTurno");
    let saldoDia = $("#saldoDia");
    let saldoTurno = $("#saldoTurno");
    let nombreProducto = $("#nombreProd");

    $("#divConsulta").css("display", "none");

    $.ajax({
        type: "GET",
        url: urlBase +"consulta.php?codigo="+codigo,
        dataType: "json",
        success: function (json) {
            $(".pl").css("display", "none");
            $("#divConsulta").css("display", "flex");
            $("#divConsulta").css("flex-direction", "column");
            
            nombreProducto.html(json["nombre"] + `<h4>` + json["refer"] + `</h4>`);

            saldoBodega.html("Inicial noche: " + json["saldoBodega"]);
            saldoGondola.html("Inicio de turno: " + json["saldoGondola"]);

            ventasDia.html("Vtas dia: "+json["ventasDia"]);
            ventasTurno.html("Vtas turno: "+json["ventasConteo"]);

            devolDia.html("Dev dia: " + json["devolDia"]);
            devolTurno.html("Dev turno: " + json["devolConteo"]);

            saldoDia.html("Saldo actual: " + json["restantesDia"]);
            saldoTurno.html("Saldo turno: " + json["restantesConteo"]);
            
            //ocultar o mostrar la informacion de conteo si el producto es de control
            let elementos = document.getElementsByClassName("informacion");
            for (let index = 0; index < elementos.length; index++) {
               //console.log(elementos[index]);
               elementos[index].style.display = (json["esControl"]== 0) ? "none" : "block";
                
            }
            

        }
    }).fail(function() {

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No se encontraron saldos de este codigo!'
          })
      });
}


function mostrarDetalle(modo){
	$(".pl").css("display", "block");
    $.ajax({
        type: "GET",
        url: urlBase+"saldoDetalle.php?codigo="+codigo+"&tipo="+modo,
        dataType: "json",
        success: function (response) {
            let ventas = response["ventas"];
            let devol = response["devol"];
            
            $("#tablaVentas").html("");
            $("#tablaDevol").html("");
            ventas.forEach(element => {
                var html = `<tr>` +
                                `<td>${element["pto_venta"]}</td>` +
                                `<td>${element["doc_fact"]}</td>` +
                                `<td>${element["num_fact"]}</td>` +
                                `<td>${element["cod_pos"]}</td>` +
                                `<td>${element["cantidad"]}</td>` +
                                `<td>${element["usuario"]}</td>` +
                                `<td>${element["fecha"]}</td>` +
                                `</tr>`;
                $("#tablaVentas").html($("#tablaVentas").html() + html);
            });

            devol.forEach(element => {
                var html = `<tr>` +
                                `<td>${element["pto_venta"]}</td>` +
                                `<td>${element["doc_fact"]}</td>` +
                                `<td>${element["num_fact"]}</td>` +
                                `<td>${element["cod_pos"]}</td>` +
                                `<td>${element["cantidad"]}</td>` +
                                `<td>${element["usuario"]}</td>` +
                                `<td>${element["fecha"]}</td>` +
                                `</tr>`;
                $("#tablaDevol").html($("#tablaDevol").html()+ html);
            });   
			$(".pl").css("display", "none");
        }
		
		
    });
	
	

    mostrarModal("myModal");
    //$('#myModal').modal('show');
}

function mostrarModal(id){
    $('#'+id).modal("show");
}

function cerrarModal(id){
    $("#"+id).modal("hide");
}


function nuevoConteo(){
    let nuevaCantidad = $("#nuevaCantidad").val();
    Swal.fire({
        title: 'Confirmacion de guardado',
        icon: 'warning',
        html:
          '¿Estas seguro de guardar el nuevo conteo? <br>' +
          'Esto reemplazará el conteo anterior y reiniciará el conteo de ventas dicho conteo',
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText:
          'Si, guardar',
        cancelButtonText:
          'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
            if(nuevaCantidad < 0){
                Swal.fire('La cantidad no puede ser menor a 0', '', 'warning');
                return;
            }

            $.ajax({
                type: "POST",
                url: urlBase+"nuevoConteo.php",
                data: {codigo: codigo, cantidad: nuevaCantidad},
                dataType: "json",
                success: function (response) {
                    //console.log(response); 
                    
                }
            });
            Swal.fire('Guardado!', '', 'success');
            cerrarModal("modalConteo"); 
        }
      })
}

function cerrarSesion(){
    //console.log(e);
    //e.preventDefault();
    $.get("./php/logout.php",function(e){
        window.location.href = "../";
    });
    
}

function traducirFecha(fecha){
    let fechaTrad = "";
    let dia = "";
    let mes = "";
    let diaFecha = fecha.substr(0,3);
    let mesFecha = fecha.substr(4,3);
    switch(diaFecha){
        case "Mon":
            dia = "Lun";
            break;
        case "Tue":
            dia = "Mar";
            break;
        case "Wed":
            dia = "Mie";
            break;
        case "Thu":
            dia = "Jue";
            break;
        case "Fri":
            dia = "Vie";
        break;
        case "Sat":
            dia = "Sab";
        break;
        case "Sun":
            dia = "Dom";
        break;
    }

    switch(mesFecha){
        case "Jan":
            mes = "Ene";
            break;
        case "Aug":
            mes = "Ago";
        break;
        case "Dec":
            mes = "Dic";
        break;
        default:
            mes = mesFecha;
            break;
    }

    fechaTrad = fecha.replace(diaFecha,dia+".");
    fechaTrad = fechaTrad.replace(mesFecha, mes);
    return fechaTrad;
    //console.log(fechaTrad);

}

function upperCaseF(a){
    setTimeout(function(){
        a.value = a.value.toUpperCase();
        if(a.value.length > 5){
            a.value = a.value.substr(0,6);
        }
        }, 1);
}