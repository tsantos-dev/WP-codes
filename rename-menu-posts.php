<?php
function change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Items';
    $submenu['edit.php'][5][0] = 'Items';
    $submenu['edit.php'][10][0] = 'Adicionar Item';
    $submenu['edit.php'][16][0] = 'Tags';
    echo '';
}
function change_post_object() {
    global $wp_post_types;
    $labels = $wp_post_types['post']-&gt;labels;
    $labels-&gt;name = 'Items';
    $labels-&gt;singular_name = 'Item';
    $labels-&gt;add_new = 'Adicionar Item';
    $labels-&gt;add_new_item = 'Adicionar Item';
    $labels-&gt;edit_item = 'Editar Item';
    $labels-&gt;new_item = 'Item';
    $labels-&gt;view_item = 'Ver Item';
    $labels-&gt;search_items = 'Buscar Itens';
    $labels-&gt;not_found = 'Nenhum Item encontrado';
    $labels-&gt;not_found_in_trash = 'Nenhum Item encontrado no Lixo';
    $labels-&gt;all_items = 'Todos Itens';
    $labels-&gt;menu_name = 'Itens';
    $labels-&gt;name_admin_bar = 'Itens';
}

add_action( 'admin_menu', 'change_post_label' );
add_action( 'init', 'change_post_object' );
