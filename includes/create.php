<?php
function wpbc_create()
{
    global $wpdb;
	global $post;
	$table_name = $wpdb->prefix . 'posts'; 

    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
			// Create post object
			$my_post = array(
			  'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
				'post_type'      => 'anwp_finanza',
			  'post_name'  => $_POST['post_name'],
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			);

			// Insert the post into the database
			wp_insert_post( $my_post );
			 ?>
				<h2>new</h2>
			<?php
        }
	
	$myposts = get_posts(array('post_type' => 'anwp_club'));
 ?>
<div class="">
<form method="post">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
<div class="container-fluid">
	<h2>Nuevo Asiento </h2>
	<div class="row">
		<div class="col-sm-9">
			<div class="form-group">				
				<label for="post_title">Equipos</label>
				<select class="form-control" id="miclass">
					<option>Elije una opcion</option>
					 <?php foreach ( $myposts as $post ) :?>
						<option><?php the_title(); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<table class="table" id="milist">
				    <thead>
						<th>Jugador</th>
						<th>Enero</th>
						<th>Febrero</th>
						<th>Marzo</th>
						<th>Abril</th>
						<th>Mayo</th>
						<th>Junio</th>
						<th>Julio</th>
						<th>Agosto</th>
					</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
</form>
</div>
	<?php
}
?>
