<?php
function wpbc_contacts_page_handler()
{
    global $wpdb;

    $table = new Custom_Table_Example_List_Table();
    $table->prepare_items();

	
	
    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'wpbc'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2> Asientos <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=wpbc_create');?>"><?php _e('Add new', 'wpbc')?></a>
		
		    <form id="contacts-table" method="post">
                  <input type="hidden" name="page" value="" />
                  <?php $table->search_box('search', 'search_id'); ?>
            </form>
    </h2>
    <?php echo $message; ?>

	
    <form id="contacts-table" method="POST">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
		<!-- The second value will be selected initially -->


        <?php $table->display() ?>
    </form>




</div>
<?php
}


function wpbc_contacts_form_page_handler()
{
    global $wpdb;
    //$table_name = $wpdb->prefix . 'cte'; 
	$table_name = $wpdb->prefix . 'posts'; 
	
    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'post_title'      => '',
    );


    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        
        $item = shortcode_atts($default, $_REQUEST);     

        $item_valid = wpbc_validate_contact($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved', 'wpbc');
                } else {
                    $notice = __('There was an error while saving item', 'wpbc');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated', 'wpbc');
                } else {
                    $notice = __('There was an error while updating item', 'wpbc');
                }
            }
        } else {
            
            $notice = $item_valid;
        }
    }
    else {
        
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_posts WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'wpbc');
            }
        }
    }

    
    //add_meta_box('contacts_form_meta_box', 'Datos del Asiento', 'wpbc_contacts_form_meta_box_handler', 'contact', 'normal', 'default');
    //add_meta_box('contacts_form_meta_box', 'Datos del Asiento', 'wpbc_contacts_form_meta_box_handler','contact', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2>Nuevo Asiento <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=finanzas');?>"><?php _e('back to list', 'wpbc')?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
       <!-- <input type="text" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>-->
        
		

		
		<div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    
                    <input type="submit" value="<?php _e('Save', 'wpbc')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function wpbc_contacts_form_meta_box_handler($item)
{
    ?>
<tbody >
		
	<div class="formdatabc">		
		
    <form >
		
		<div class="form2bc">
        <p>			
		    <label for="post_title">Titulo</label>
		<br>	
            <input id="post_title" name="post_title" type="text" value="<?php echo esc_attr($item['post_title'])?>"
                    required>
		</p>
		<p>	
            <label for="post_name">Slug</label>
		<br>
		    <input id="post_name" name="post_name" type="text" value="<?php echo esc_attr($item['post_name'])?>">
        </p>
		</div>	

		<div class="form2bc">
        <p>			
			<label for="post_date">Fecha y Hora</label>
			<br>
			<input id="post_date" name="post_date" type="text" value="<?php echo date('Y-m-d H:i:s') ?>">
		</p>
		<p>	
            <label for="post_type">Tipo</label>
		<br>
		    <input id="post_type" name="post_type" type="text" value="anwp_finanza">
        </p>
		</div>	
		
		</form>
		</div>
</tbody>
<?php
}
function wpbc_contacts_form_ver_handler($item)
{
	global $wpdb;
	$message = '';
    $notice = '';
	$item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_posts WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'wpbc');
            }
        }else{
			$message = "Faltan parametros para la consulta";
		}
	 ?>
    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>
		 <label for="name"><?php echo esc_attr($item['ID']) ?></label>
		<label for="name"><?php echo esc_attr($item['post_title']) ?></label>
<div class="wrap">
		<h2>Welcome To My Plugin</h2>
	</div>
	<?php
}
