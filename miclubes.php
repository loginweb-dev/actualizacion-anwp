<?php 

function list_posts_search_shortcode2() {
    $args = array(
        'post_type' => 'anwp_club',
    	'orderby'          => 'date',
		'order'            => 'DESC',
        'numberposts' => -1,
    	'posts_per_page' => -1,
    );
    $search = '';
    if (isset($_GET['search'])) {
        $search = sanitize_text_field($_GET['search']);
        $args['s'] = $search;
    }
	global $wpdb;
	$total_items = $wpdb->get_var("SELECT COUNT(id) FROM wp_posts WHERE post_type='anwp_club'");
	
    $posts = get_posts($args);
    $output = '<form method="get" action="' . get_permalink() . '">';
    $output .= '<input type="text" class="form-control" name="search" placeholder="Buscar" value="' . $search . '">';
    $output .= '<button type="submit" class="btn btn-primary">Buscar</button> Total: '.count($posts);
    $output .= '</form>';
    if ($posts) {
        $output .= '<div class="table-responsive"><table class="table table-hover table-striped">';
        $output .= '<tr><th>Título</th><th>Registro</th></tr>';
        foreach ($posts as $post) {
            $output .= '<tr>';
            $output .= '<td>'.$post->ID.'.- <a href="/club/' .$post->post_name. '">' . get_the_title($post->ID) . '</a><br><span class="label label-default">DB: '.get_post_meta($post->ID, '_anwpfl_custom_value_1', true).'</span></td>';
            $output .= '<td>' . get_the_date('j F Y', $post->ID) . '</td>';
            $output .= '</tr>';
        }
        $output .= '</table></div>';
    } else {
        $output .= '<p>No se encontraron resultados para la búsqueda de "' . $search . '".</p>';
    }
    return $output;
}
add_shortcode('miclubes', 'list_posts_search_shortcode2');



?>