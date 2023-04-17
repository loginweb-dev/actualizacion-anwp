<?php
function wpbc_asiento()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
// 		echo $_POST["type"];
		$my_post = array(
			'post_title'    => "asiento".date("h:i:sa"),
			'post_type'      => 'anwp_finanza',
			'post_name'  => "asiento".date("h:i:sa"),
			'post_status'   => 'publish',
			'post_author'   => $current_user->ID,
			'post_content' => $_POST["notas"]
		);

		// Insert the post into the database
		$new = wp_insert_post( $my_post );
		add_post_meta( $new, '_type', $_POST["type"], true );
		add_post_meta( $new, '_monto', $_POST["monto"], true );
		add_post_meta( $new, '_cliente', $_POST["cliente"], true );
	}
	if(isset($_POST['delete'])){
		wp_delete_post($_POST['delete'], true);
	}
	$tab1 = 'show active';
	$tab2 = '';
	$tab3 = '';
	if(isset($_POST['miplanilla'])){
		$equipop = get_post($_POST['miplanilla']);
		$tab3 = 'show active';
		$tab2 = '';
		$tab1 = '';
		
	}
	
	$myposts = get_posts(array('post_type' => 'anwp_finanza'));
	$myclubs = get_posts(array('post_type' => 'anwp_club'));
	$current_user = wp_get_current_user();
 ?>
 <div class="wrap">
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Asientos</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Nuevo</button>
  </li>
	<li class="nav-item" role="presentation">
    	<button class="nav-link" id="planilla-tab" data-toggle="tab" data-target="#planilla" type="button" role="tab" aria-controls="planilla" aria-selected="false">Planillas</button>
 	</li>
</ul>
<div class="tab-content" id="myTabContent">
<div class="tab-pane fade <?php echo $tab1; ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
	<br>
		<h4 class="text-left">Todos los Asientos</h4>
	<input type="text" class="form-control" placeholder="Buscar">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
		<th scope="col">Cliente</th>
		<th scope="col">Notas</th>
      <th scope="col">Tipo</th>
      <th scope="col">Fecha</th>
      <th scope="col">Monto</th>
		<th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
	  <?php foreach ($myposts as $post) { $club = get_post(get_post_meta($post->ID, '_anwpfl_current_club', true)); ?>
		<tr>
		  <td scope="row"><?php echo $post->ID ?></td>
			<td scope="row"><?php echo get_post_meta($post->ID, '_cliente', true) ?></td>
			<td><?php echo $post->post_content ?></td>
		  <td>
			  <?php 
	 			$mitype = get_post_meta($post->ID, '_type', true)=='ingreso' ? 'success' : 'warning';
			  ?>
			  <h5><span class="badge badge-<?php echo $mitype ?>">
				  <?php echo get_post_meta($post->ID, '_type', true)?>
				  </span></h5>
			</td>
			<td><?php echo get_the_date('j F Y', $post->ID) ?></td>
			 <td><?php echo get_post_meta($post->ID, '_monto', true) ?></td>
		  <td>
			   <form method="post">
			  <a href="/wp-content/plugins/actualizacion-anwp/fpdf/main.php?id=<?php echo $post->ID ?>" class="btn btn-dark btn-sm" title="Imprimir">-</a>
			 
				  <input type="hidden" class="form-control" id="delete" name="delete" placeholder="" value="<?php echo $post->ID ?>">
			  	<button type="submit" class="btn btn-danger btn-sm" title="Eliminar">-</button>
			  </form>
			</td>
			
		</tr>
	  <?php  } ?>
  </tbody>
</table>
	</div>
  	<div class="tab-pane fade <?php echo $tab2; ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
		<br>
		<h4 class="text-left">Nuevo Asiento</h4>
		<hr>
<form method="post">
	<div class="row">
	  <div class="form-group col-sm-6">
		<label for="exampleFormControlSelect1">Tipo</label>
		<select class="form-control" id="type" name="type">
		  <option value="ingreso">Ingreso</option>
		  <option value="egreso">Egreso</option>
		</select>
	  </div>
		<div class="form-group col-sm-6">
		<label for="">Monto en Bs.</label>
		<input type="number" class="form-control" id="monto" name="monto" placeholder="" value="0" required>
	  </div>
		
	<div class="form-group col-sm-6">
		<label for="">Editor</label>
		<input type="text" class="form-control" id="editor" name="editor" placeholder="" value="<?php echo $current_user->display_name ?>" readonly>
	  </div>
		
	<div class="form-group col-sm-6">
		<label for="">Cliente o Proveedor</label>
		<input type="text" class="form-control" id="cliente" name="cliente" placeholder="nombre completo del cliente" value="" required>
	  </div>
		
	  <div class="form-group col-sm-12">
		<label for="exampleFormControlTextarea1">Nota o Resumen</label>
		<textarea class="form-control" id="notas" rows="8" name="notas" required></textarea>
	  </div>
		<div class="form-group col-sm-6">
		 <button type="submit" class="btn btn-dark">Guardar</button>
 		</div>
	</div>

</form>
	</div>
	
	<div class="tab-pane fade <?php echo $tab3; ?>" id="planilla" role="tabpanel" aria-labelledby="planilla-tab">
		<br>
		<h4>Planilla de Jugadores</h4>
			<div class="form-group">				
				<form method="post">
					<select class="form-control" id="miplanilla" name="miplanilla" onchange="this.form.submit()">
						<option>Elije una opcion</option>
						 <?php foreach ( $myclubs as $post ) :?>
							<option value="<?php echo $post->ID ?>"><?php echo $post->post_title ?></option>
						<?php endforeach; ?>
					</select>
				</form>
			</div>
		<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
<!-- 					<th>ID</th> -->
					<th>Jugador</th>
					<th>Enero</th>
					<th>Febrero</th>
					<th>Marzo</th>
					<th>Abril</th>
					<th>junio</th>
					<th>Julio</th>
					<th>Agosto</th>
					<th>Septie</th>
					<th>Octubre</th>
					<th>Noviem</th>
					<th>Diciem</th>
				</tr>
			</thead>
			<tbody>
			
						<?php 
							$meta = get_post_meta($equipop->ID, '_anwpfl_squad', true);
							//echo $meta[4];
							$temporada = get_term_by('id', $meta[4], 'anwp_season');
							//echo $temporada->name;
							$parseme = json_decode( $meta, true );
							//echo  '<pre>'; print_r($parseme); echo '</pre>';
							foreach (  $parseme['s:4'] as $array ) {
								
							$jugador = get_post($array['id']);
							$clubaux = get_post(get_post_meta($jugador->ID, '_anwpfl_current_club', true));
							?>
								<tr>
<!-- 									<td><?php echo $jugador->ID ?></td> -->
									<td>
										<a href="/wp-content/plugins/actualizacion-anwp/fpdf/print_jugador.php?id=<?php echo $jugador->ID ?>">
										<?php echo $jugador->post_title ?>
										</a>
										<br>
										<small>- <?php echo $clubaux->post_title ?></small>
										<br>
										<small>- <?php echo $temporada->name ?></small>		
									</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
								</tr>
							<?php 
							}
						?>
			</tbody>
		</table>
		</div>
	</div>	
</div>

 </div>
<?php
}
?>
