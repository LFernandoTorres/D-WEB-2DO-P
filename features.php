
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
          <h1 class="h2">Features</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
              <button type="button" class="btn btn-sm btn-outline-danger cancelar">Cancelar</button>
              <button type="button" class="btn btn-sm btn-outline-success" id="nuevo_registro">Nuevo</button>
            </div>
          </div>
        </div>
        <h2>Features</h2>
        <div class="table-responsive view" id="show_data">
          <table class="table table-striped table-sm" id="list-features">
            <thead>
              <tr>
                <th>Ruta Imagen</th>
                <th>Titulo</th>
                <th>Subtitulo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div id="insert_data" class="view">
          <form action="#" id="form_data">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="imagen">Ruta Imagen</label>
                  <input type="text" id="imagen" name="imagen" class="form-control">
                </div>
                <div class="form-group">
                  <label for="titulo">Titulo</label>
                  <input type="text" id="titulo" name="titulo" class="form-control">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="subtitulo">Subtitulo</label>
                  <input type="text" id="subtitulo" name="subtitulo" class="form-control">
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
        "accion" : "consultar_features"
      };
      $.post("includes/_funciones.php", obj, function(respuesta){
        let template = ``;
        $.each(respuesta,function(i,e){
          template += `
          <tr>
          <td>${e.img_fe}</td>
          <td>${e.titulo_fe}</td>
          <td>${e.subtitulo_fe}</td>
          <td>
          <a href="#" data-id="${e.id_fe}" class="ceditar_features">Editar</a>
          <a href="#" data-id="${e.id_fe} class="eliminar_feature">Eliminar</a>
          </td>
          </tr>
          `;
        });
        $("#list-features tbody").html(template);
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
     // Funcion para guardar Datos
      let imagen = $("#imagen").val();
      let titulo = $("#titulo").val();
      let subtitulo = $("#subtitulo").val();
      // Inicializar el objetos
      let obj ={
        "accion" : "insertar_features",
        "imagen" : imagen,
        "titulo" : titulo,
        "subtitulo" : subtitulo
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
          obj["accion"] = "editar_features";
          obj["id"] = $(this).data("id");
          $(this).text("Guardar").data("editar",0);
          $("#form_data")[0].reset();
      }

      $.post("includes/_funciones.php", obj, function(respuesta){ 
        alert(respuesta);
       if (respuesta == "Se inserto Feature en la BD ") {
          change_view();
          consultar();
         }
        if (respuesta == "Se edito Feature correctamente") {
            change_view();
            consultar();

      } 
     }
     );
    });

//** ELIMINAR REGISTRO **//
$("#main").on("click",".eliminar_feature",function(e){
e.preventDefault();
let confirmacion = confirm("Desea eliminar esta variable");
if(confirmacion){
let id = $(this).data('id');
obj = {
  "accion" : "eliminar_feature",
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


//** EDITAR **//
    $('#list-features').on("click",".ceditar_features", function(e){
        e.preventDefault();
    let id = $(this).data('id');
         obj = {
      "accion" : "ceditar_features",
      "id" : id
    };
    $("#form_data")[0].reset();
    change_view('insert_data');
    $("#guardar_datos").text("Editar").data("editar",1).data("id", id);
    $.post('includes/_funciones.php', obj, function(r){
      $("#imagen").val(r.img_fe);
      $("#titulo").val(r.titulo_fe);
      $("#subtitulo").val(r.subtitulo_fe);    
        }, "JSON");

        consultar();          
            });


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