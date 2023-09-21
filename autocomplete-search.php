// HTML da busca
<div class="search_bar">
    <form action="/" method="get" autocomplete="off">
        <input type="text" name="s" placeholder="Search Code..." id="keyword" class="input_search" onkeyup="mukto_search_fetch()">
        <button>
            Search
        </button>
    </form>
    <div class="search_result" id="datafetch">
        <ul>
            <li>Please wait..</li>
        </ul>
    </div>
</div>

//  jQuery para que os resultados da pesquisa sejam exibidos apenas quando você tiver mais de 2 caracteres no campo de pesquisa
<script>
$("input#keyword").keyup(function() {
      if ($(this).val().length > 2) {
        $("#datafetch").show();
      } else {
        $("#datafetch").hide();
      }
    });
</script>

// CSS para deixar o box dos resultados ocultos inicialmente
<style>
div.search_result {
  display: none;
}
</style>

// WordPress Ajax Search without plugin
// #### functions.php
// Este código irá interagir com HTML para atingir nosso objetivo de criar wp Ajax Search sem plugin.

<?php 
/*
 ==================
 Ajax Search
======================	 
*/
// add the ajax fetch js
add_action( 'wp_footer', 'ajax_fetch' );
function ajax_fetch() {
?>
<script type="text/javascript">
function mukto_search_fetch(){

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'post',
        data: { action: 'data_fetch', keyword: jQuery('#keyword').val() },
        success: function(data) {
            jQuery('#datafetch').html( data );
        }
    });

}
</script>

<?php
}

// the ajax function
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');
function data_fetch(){

    $the_query = new WP_Query( array( 'posts_per_page' => -1, 's' => esc_attr( $_POST['keyword'] ), 'post_type' => array('page','post') ) );
    if( $the_query->have_posts() ) :
        echo '<ul>';
        while( $the_query->have_posts() ): $the_query->the_post(); ?>

            <li><a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a></li>

        <?php endwhile;
       echo '</ul>';
        wp_reset_postdata();  
    endif;

    die();
}

// Este código mostrará o resultado da página e postagem , mas se desejar, você também pode ativá-lo para o seu tipo de postagem personalizada

/**
 * This function modifies the main WordPress query to include an array of 
 * post types instead of the default 'post' post type.
 *
 * @param object $query The main WordPress query.
 */
function mukto_post_type_include( $query ) {
    if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
        $query->set( 'post_type', array( 'post', 'page', 'custom_post_type' ) );
    }
}
add_action( 'pre_get_posts', 'mukto_post_type_include' );
