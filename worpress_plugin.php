<?php
/*
Plugin Name: PruebaForStradata
Plugin URI: 
Description: Prueba para stradata.
Version: 1.0
Author: Esteban Villa R.
Author URI: http://estebanvilla.ml
License: 
License URI: 
*/

add_action('pre_get_search_form','upload_file');

function upload_file(){


//Upload file form
	handle_post_file();
	echo 'Bienvenido al sitio para la prueba técnica de STRADATA, plugin para Wordpress realizado por Esteban Villa Ramirez';
	echo '<a href="">Ver codigo fuente </a> de plugin ';
	echo '<h2>Seleccioné el archívo</h2>';
	echo '<form action="#"method="post" enctype="multipart/form-data"><input type="file" id="file" name="file">';
	echo '<button type="submit"><i class="fa fa-cloud-upload"></i>Adjuntar</button>';

	echo '</form>';

	echo '</script>';


}
function handle_post_file(){
//Save upload file

	if(isset($_FILES['file'])){
		$file 	= $_FILES['file'];
		$dir = wp_upload_dir();
		$path = $dir['path'];
		$tmp = $file['tmp_name'];
		$newName = "clientes.txt";
		$pathname = $path."/".$newName;

if(file_exists($pathname)){
	array_map('unlink', glob($path."/*.txt"));
}

		if(move_uploaded_file($tmp, $pathname)){
			echo '<script>alert("Archivo subido correctamente");</script>';
		};
	}
}

add_action('the_content','get_csv');


function get_csv() {


	$dir =wp_upload_dir();
	$path = $dir['path'];
	$fp = $path."/clientes.txt";

if(is_file($fp)):

	$file = new SplFileObject($fp);
	while (!$file->eof()) {
		$contTxt =  $file->fgets();

	}

	$cont = explode(";", $contTxt);   
	$data = array();



	foreach ($cont as $k => $v) {



		if($v == 'Nombres' 
			|| $v == 'Apellidos' 
			|| $v == 'Tipo de Contacto'
			|| $v == 'Identificación'
			|| $v == 'Alerta'
			|| $v == 'Edad'
			|| $v == 'Fecha Creación'
			|| $v == 'Ciudad de nacimiento'
			|| $v == 'Tipo Identificación'
			|| $v == 'INCONSISTENCIA'
			|| $v == 'RL'
			|| $v == 'Alerta'
			){

		}
	else {
		$data[] = $v;

	}

}


//all users
$usuarios = array();
foreach($data as $i => $val){
	if(($i % 8) === 0 ){

		$usuarios[] = array(

			'nombre' => $val,
			'apellido' => $data[$i+1],
			'pwd' => $data[$i+3],
			'tipo_contacto' => $data[$i+2],
			'tipo_identificacion' => $data[$i+4],
			'edad'  => $data[$i+5],
			'fecha'  => $data[$i+6],
			'ciudad'  => $data[$i+7],
			); 
	}

}


//Export pdf
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>';

echo "<script>

function demoFromHTML() {
    var pdf = new jsPDF('p', 'pt', 'letter');
    source = $('#table_wrapper')[0];

    specialElementHandlers = {
        '#bypassme': function (element, renderer) {
            return true
        }
    };
    margins = {
        top: 80,
        bottom: 60,
        left: 10,
        width: 700
    };
    pdf.fromHTML(
    source, 
    margins.left, 
    margins.top, { 
        'width': margins.width, 
        'elementHandlers': specialElementHandlers
    },

    function (dispose) {
        pdf.save('Clientes.pdf');
    }, margins);
}
</script>";

//Export excel
echo '<script>

jQuery(document).ready(function() {
	jQuery("#btnExport").click(function(e) {
		e.preventDefault();

//getting data from our table
		var data_type = "data:application/vnd.ms-excel";
		var table_div = document.getElementById("table_wrapper");
		var table_html = table_div.outerHTML.replace(/ /g, "%20");

		var a = document.createElement("a");
		a.href = data_type + ", " + table_html;
		a.download = "clientes_" + Math.floor((Math.random() * 9999999) + 1000000) + ".xls";
		a.click();
	});
});
</script>';


//table
$tab = '<div id="table_wrapper">
<table id="list">
	<button id="btnExport"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Exportar a Excel</button>
	<button onclick="javascript:demoFromHTML();"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>Exportar a PDF</button>
	<thead>
		<th>Nombre</th>
		<th>Apellido</th>
		<th>Identificación - Contraseña</th>
		<th>Tipo de contacto</th>
		<th>Tipo de identificación</th>
		<th>Edad</th>
		<th>Fecha</th>
		<th>Cuidad</th>
	</thead>
	<tbody>';

		foreach($usuarios as $j => $usuario){

//create usuer on db
/*
$website = "#";
$userdata = array(
'user_login'  =>  $usuario['nombre'],
'user_url'    =>  $website,
'user_pass'   =>  $usuario['pwd']  
);
*/
//	$user_id = wp_insert_user( $userdata ) ;
/*
if ( ! is_wp_error( $user_id ) ) {
//	echo "Total de usuarios creados : ". $user_id;
}

*/

$tab .= '<tr>

<td>'.$usuario['nombre'].'</td>
<td>'.$usuario['apellido'].'</td>
<td>'.$usuario['pwd'].'</td>
<td>'.$usuario['tipo_contacto'].'</td>
<td>'.$usuario['tipo_identificacion'].'</td>
<td>'.$usuario['edad'].'</td>
<td>'.$usuario['fecha'].'</td>
<td>'.$usuario['ciudad'].'</td>
</tr>
';


}

$tab .= '
</tbody>
</table></div>';

/* ------ Show table -------*/
echo $tab;




endif;



}


