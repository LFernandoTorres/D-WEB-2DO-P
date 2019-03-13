<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v3.8.5">
  <title>Dashboard Template · Bootstrap</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
 <?php require_once("navbar.php");   ?>

      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Downloads</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
              <button type="button" class="btn btn-sm btn-outline-danger cancelar">Cancelar</button>
              <button type="button" class="btn btn-sm btn-outline-success" id="nuevo_registro">Nuevo</button>
            </div>
          </div>
        </div>
        <h2>Downloads</h2>
        <div class="table-responsive view" id="show_data">
          <table class="table table-striped table-sm" id="list-download">
            <thead>
              <tr>
                <th>Titulo</th>
                <th>Subtitulo</th>
                <th>Botón</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div id="insert_data" class="view">
            <form action="#" id="form_data" enctype="multipart/form_data">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="nombre">Titulo</label>
                  <input type="text" id="titulo" name="titulo" class="form-control">
                </div>
                <div class="form-group">
                  <label for="subtitulo">Subtitulo</label>
                  <input type="text" id="subtitulo" name="subtitulo" class="form-control">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="boton">Botón</label>
                  <input type="text" id="boton" name="boton" class="form-control">
                </div>
              </div>
            </div>
            <div class="form-group">
                  <input type="file" name="foto" id="foto">
                  <input type="hidden" name="ruta" id="ruta" readonly="readonly">
                </div>
                <div id="preview"></div>
              </div>
            <div class="row">
              <div class="col">
                <button type="button" class="btn btn-success" id="guardar_datos">Guardar</button>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <script>
    function change_view(vista = 'show_data'){
      $("#main").find(".view").each(function(){
        // $(this).addClass("d-none");
        $(this).slideUp('fast');
        let id = $(this).attr("id");
        if(vista == id){
          $(this).slideDown(300);
          // $(this).removeClass("d-none");
        }
      });

    }
    function consultar(){
      let obj = {
        "accion" : "consultar_download"
      };
      $.post("includes/_funciones.php", obj, function(respuesta){
        let template = ``;
        $.each(respuesta,function(i,e){
          template += `
          <tr>
          <td>${e.titulo_do}</td>
          <td>${e.subtitulo_do}</td>
          <td>${e.boton_do}</td>
          <td>
          <a href="#" data-id="${e.id_do}" class="ceditar_downloads">Editar</a>
          <a href="#" data-id="${e.id_do}" class="eliminar_downloads">Eliminar</a>
          </td>
          </tr>
          `;
        });
        $("#list-download tbody").html(template);
      },"JSON");
    }
    $(document).ready(function(){
      consultar();
      change_view();
    });
    $("#nuevo_registro").click(function(){
      change_view('insert_data');
    });

    $("#guardar_datos").click(function(guardar){
      let titulo = $("#titulo").val();
      let subtitulo = $("#subtitulo").val();
      let boton = $("#boton").val();
      let obj ={
        "accion" : "insertar_download",
        "titulo" : titulo,
        "subtitulo" : subtitulo,
        "boton" : boton
      }
      $("#form_data").find("input").each(function(){
        $(this).removeClass("has-error");
        if($(this).val() != ""){  
          obj[$(this).prop("name")] =  $(this).val();
        }else{
          $(this).addClass("has-error").focus();
          return false;
        }
      });
      if($(this).data("editar") == 1){
          obj["accion"] = "editar_downloads";
          obj["id"] = $(this).data("id");
          $(this).text("Guardar").data("editar",0);
          $("#form_data")[0].reset();
      }

      $.post("includes/_funciones.php", obj, function(respuesta){ 
        alert(respuesta);
       if (respuesta == "Se inserto download en la BD ") {
          change_view();
          consultar();
         }
        if (respuesta == "Se edito download correctamente") {
            change_view();
            consultar();
          } 
     }
     );
    });
//** ELIMINAR REGISTRO **//
$("#main").on("click",".eliminar_downloads",function(e){
e.preventDefault();
let confirmacion = confirm("Desea eliminar esta variable");
if(confirmacion){
let id = $(this).data('id');
obj = {
  "accion" : "eliminar_downloads",
  "id" : id
};
$.post("includes/_funciones.php", obj, function(respuesta){
alert(respuesta);
consultar();
});


}else{
  alert("El registro no se esta eliminado");
}


});

//** EDITAR **//
    $('#list-download').on("click",".ceditar_downloads", function(e){
        e.preventDefault();
    let id = $(this).data('id');
         obj = {
      "accion" : "ceditar_downloads",
      "id" : id
    };
    $("#form_data")[0].reset();
    change_view('insert_data');
    $("#guardar_datos").text("Editar").data("editar",1).data("id", id);
    $.post('includes/_funciones.php', obj, function(r){
      $("#titulo").val(r.titulo_do);
      $("#subtitulo").val(r.subtitulo_do);
      $("#boton").val(r.boton_do);      
        }, "JSON");

        consultar();          
            });

///// FUNCTION PHOTO  //////
    $("#foto").on("change", function (e) {
      let formDatos = new FormData($("#form_data")[0]);
      formDatos.append("accion", "carga_foto");
      $.ajax({
        url: "includes/_funciones.php",
        type: "POST",
        data: formDatos,
        contentType: false,
        processData: false,
        success: function (datos) {
          let respuesta = JSON.parse(datos);
          if(respuesta.status == 0){
            alert("No se cargó la foto");
          }
          let template = `
          <img src="${respuesta.archivo}" alt="" class="img-fluid" />
          `;
          $("#ruta").val(respuesta.archivo);
          $("#preview").html(template);
        }
      });
    });


//** CANCELAR **//
    $("#main").find(".cancelar").click(function(){
      change_view();
         $("#form_data")[0].reset();
      if ($("#guardar_datos").data("editar") == 1) {
        $("#guardar_datos").text("Guardar").data("editar",0);
      }
    });
  </script>
</body>
</html>