<?php
function wpbc_print()
{
    global $wpdb;
	global $post;
	$table_name = $wpdb->prefix . 'posts'; 
	
	$myposts = get_posts(array('post_type' => 'anwp_player'));
	    $args = array(
        'post_type' => 'anwp_player',
    	'orderby'          => 'date',
		'order'            => 'DESC',
        'numberposts' => -1,
    	'posts_per_page' => -1,
    );
    $search = '';
    if (isset($_GET['search'])) {
        $search = sanitize_text_field($_GET['search']);
      	$args['s'] =  $search;
    }
	$total_items = $wpdb->get_var("SELECT COUNT(id) FROM wp_posts WHERE post_type='anwp_player'");
    $posts = get_posts($args);
 ?>
 <div class="wrap">
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Jugadores</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Equipos</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Tabla de Posiciones</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		Todos los Jugadores
		<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Jugador</th>
      <th scope="col">Equipo</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
	  <?php foreach ($posts as $post) { $club = get_post(get_post_meta($post->ID, '_anwpfl_current_club', true)); ?>
		<tr>
		  <th scope="row"><?php echo $post->ID ?></th>
		  <td><?php echo $post->post_name ?></td>
		  <td><?php echo $club->post_title ?></td>
		  <td><a href="/wp-content/plugins/actualizacion-anwp/fpdf/print_jugador.php?id=<?php echo $post->ID ?>&jugador=<?php echo $post->post_title ?>" class="btn btn-dark">Imprimir</a></td>
		</tr>
	  <?php  } ?>
  </tbody>
</table>
	</div>
  	<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
	Todos los Equipos
	</div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
</div>

 </div>
<?php
}
?>
