<?php 

function list_posts_search_shortcode() {
	global $wpdb;
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
    	//$args['meta_query'] =  array(
      	//array(
         //'key' => '_anwpfl_full_name',
         //'value' =>  $search,
         //'compare' => 'LIKE',
      	//));
      	$args['s'] =  $search;
    }
	$total_items = $wpdb->get_var("SELECT COUNT(id) FROM wp_posts WHERE post_type='anwp_player'");
    $posts = get_posts($args);
	
    $output = '<form method="get" action="' . get_permalink() . '">';
    $output .= '<input type="text" class="form-control" name="search" placeholder="Buscar" value="' . $search . '">';
    $output .= '<button type="submit" class="btn btn-primary">Buscar</button>  Total: '.count($posts);
    $output .= '</form>';
    if ($posts) {
        $output .= '<div class="table-responsive"><table class="table table-hover table-striped">';
        $output .= '<tr><th>Título</th><th>Carnet</th><th>Registro</th><th>Club</th></tr>';
        foreach ($posts as $post) {
			$club = get_post(get_post_meta($post->ID, '_anwpfl_current_club', true));
            $output .= '<tr>';
            $output .= '<td>'.$post->ID.'.- <a href="/player/' .$post->post_name. '">' . get_the_title($post->ID) . '</a><br><span class="label label-default">DB: '.get_post_meta($post->ID, '_anwpfl_custom_value_1', true).'</span></td>';
            $output .= '<td>' . get_post_meta($post->ID, '_anwpfl_full_name', true) . '</td>';
            $output .= '<td>' . get_the_date('j F Y', $post->ID) . '</td>';
            $output .= '<td>' . $club->post_title . '</td>';
            $output .= '</tr>';
        }
        $output .= '</table></div>';
    } else {
        $output .= '<p>No se encontraron resultados para la búsqueda de "' . $search . '".</p>';
    }
    return $output;
}
add_shortcode('list_posts_search', 'list_posts_search_shortcode');
?>