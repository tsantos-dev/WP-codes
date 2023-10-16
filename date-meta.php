<!-- ################## functions.php ################## -->
<?php
function add_custom_meta_box() {
	add_meta_box(
		'data_evento', // Unique ID
		__('Data do evento', 'meta_box_data'),    // Meta Box title
		'meta_box_datepicker_html',    // Callback function
		'eventos',                     // The selected post type
        'side'
	);
}

add_action( 'add_meta_boxes', 'add_custom_meta_box' );

function meta_box_datepicker_html( $post ) {

	$custom_date = get_post_meta( $post->ID, '_custom_date_meta_key', true );


	?>

	<label for="custom_date">Data do evento: </label>
	<input name="custom_date" type="date" value="<?php echo esc_attr($custom_date); ?>">

    <?php

}

function meta_box_datepicker_save( $post_id ) {
    if ( array_key_exists( 'custom_date', $_POST ) ) {
       update_post_meta(
          $post_id,
          '_custom_date_meta_key',
          $_POST['custom_date']
       );
    }
 }
 
 add_action( 'save_post', 'meta_box_datepicker_save' );
?>

<!-- ################## post ################## -->
<?php
	$custom_date = get_post_meta( $post->ID, '_custom_date_meta_key', true );

	//https://www.w3schools.com/php/func_date_date_format.asp
    $dateformatstring = "d | M";
	$unixtimestamp = strtotime($custom_date);

?>


<a href="<?php echo get_permalink(get_the_ID());?>">
<div class="evento lm-post">
	<div class="data">
		<?php echo date_i18n($dateformatstring, $unixtimestamp); ?>
	</div>
	<div class="titulo">
		<?php the_title();?>
	</div>
</div>
</a>
