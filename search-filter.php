<!--HTML do filtro-->

    <form role="search" name="fbusca" method="get" id="fbusca" action="<?php echo esc_url(home_url('/')); ?>">

        <input type="text" name="s" id="s" placeholder="Procurando por algo?"  class="input_search" />
        <!-- Outros campos de pesquisa aqui -->

        <label for="start_date">Data Inicial:
            <input type="date" id="start_date" name="start_date">
        </label>

        <label for="end_date">Data Final:
            <input type="date" id="end_date" name="end_date">
        </label>

        <label for="taxonomy1">Por categoria: <br />
            <select id="taxonomy1" name="taxonomy1">
                <option value="">Selecione categoria</option>
                <?php
                $terms = get_terms('category'); // Pega os termos da taxonomia 'categorias'
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                }
                ?>
            </select>
        </label>

        <label for="taxonomy2">Por tag:<br />
            <select id="taxonomy2" name="taxonomy2">
                <option value="">Selecione tag</option>
                <?php
                $terms = get_terms('post_tag'); // Pega os termos da taxonomia 'tags'
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                }
                ?>
            </select>
        </label>

        <label for="taxonomy3">Por autor:<br />
            <select id="taxonomy3" name="taxonomy3">
                <option value="">Selecione o autor</option>
                <?php
                $terms = get_terms('autores'); // Pega os termos da taxonomia 'autores'
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                }
                ?>
            </select>
        </label>

        <input type="submit" value="Buscar">
    </form>

<!-- FUNCTION PARA FAZER O FILTRO POR TAXONOMIA E INTERVALO DE DATAS -->
<?php
function custom_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
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

            if (!empty($tax_query)) {
                $tax_query['relation'] = 'AND'; // Use 'AND' para requerer que as postagens correspondam a todas as taxonomias selecionadas
                $query->set('tax_query', $tax_query);
            }

            $query->set('date_query', $date_query);
        }
    }
}
add_action('pre_get_posts', 'custom_search_filter');
?>
