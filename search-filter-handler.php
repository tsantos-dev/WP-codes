<!-- searchform.php -->
<form role="search" name="fbusca" method="get" id="fbusca" action="<?php echo esc_url(home_url('/')); ?>">

    <input type="text" name="s" id="s" placeholder="Procurando por algo?"  class="input_search" value="<?php the_search_query(); ?>" />
    <!-- Outros campos de pesquisa aqui -->

    <label for="start_date">Data Inicial:</label>
    <input type="date" id="start_date" name="start_date">

    <label for="end_date">Data Final:</label>
    <input type="date" id="end_date" name="end_date">

    <label for="categoria">Por categoria:</label>
    <select id="categoria" name="categoria">
        <option value="">Selecione a categoria</option>
        <?php
        $terms = get_terms('category'); 
        foreach ($terms as $term) {
            echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
        }
        ?>
    </select>

    <label for="tag">Por Tag:</label>
    <select id="tag" name="tag">
        <option value="">Selecione Tag</option>
        <?php
        $terms = get_terms('post_tag');
        foreach ($terms as $term) {
            echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
        }
        ?>
    </select>

    <label for="autores">Por autor:</label>
    <select id="autores" name="autores">
        <option value="">Selecione o autor</option>
        <?php
        $terms = get_terms('autores');
        foreach ($terms as $term) {
            echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
        }
        ?>
    </select>

    <input type="submit" value="Buscar">

    <div id="search-results"></div>
</form>

<!-- search-ajax.js -->
<script>
jQuery(document).ready(function($) {
    $('#fbusca').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            type: 'GET',
            url: ajax_object.ajax_url,
            data: formData,
            success: function(response) {
                // Atualize a área de exibição de resultados com os dados recebidos
                $('#search-results').html(response);
            }
        });
    });
});
</script>

<!-- functions.php -->
<?php
function custom_search_ajax_handler() {
    $start_date = sanitize_text_field($_GET['start_date']);
    $end_date = sanitize_text_field($_GET['end_date']);

    // Certifique-se de que as datas estejam no formato correto (YYYY-MM-DD)
    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));

    // Adicione cláusulas de filtro de taxonomia se selecionadas
    for ($i = 1; $i <= 3; $i++) {
        $taxonomy_key = 'tax' . $i;
        if (!empty($_GET[$taxonomy_key])) {
            $taxonomy_value = sanitize_text_field($_GET[$taxonomy_key]);
            $tax_query[] = array(
                'taxonomy' => $taxonomy_key,
                'field' => 'slug',
                'terms' => $taxonomy_value,
            );
        }
    }

    // Adicione uma cláusula de filtro de data da postagem à consulta
    $date_query = array(
        array(
            'after'     => $start_date,
            'before'    => $end_date,
            'inclusive' => true,
        ),
    );

    $query_args = array(
        'post_type' => 'post', // Substitua 'post' pelo tipo de postagem que deseja pesquisar
        'posts_per_page' => -1, // Ou defina o número desejado de postagens por página
        'date_query' => $date_query,
    );

    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND'; // Use 'AND' para requerer que as postagens correspondam a todas as taxonomias selecionadas
        $query_args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($query_args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Exiba o conteúdo da postagem aqui
            the_title('<h2>', '</h2>');
            the_content();
        endwhile;
        wp_reset_postdata();
    else :
        echo 'Nenhum resultado encontrado.';
    endif;

    die(); // Encerre a execução do script após retornar os resultados
}

add_action('wp_ajax_custom_search', 'custom_search_ajax_handler');
add_action('wp_ajax_nopriv_custom_search', 'custom_search_ajax_handler');


//carrega o script JavaScript e configura as variáveis AJAX para que a solicitação funcione corretamente
function enqueue_search_ajax_script() {
    wp_enqueue_script('search-ajax', get_template_directory_uri() . '/js/search-ajax.js', array('jquery'), '1.0', true);

    // Configurar as variáveis AJAX para a solicitação
    wp_localize_script('search-ajax', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_search_ajax_script');

?>
