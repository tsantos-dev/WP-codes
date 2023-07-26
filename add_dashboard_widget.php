<?php
// Add a widget in WordPress Dashboard
function wpc_dashboard_widget_function() {
    // Entering the text between the quotes
    echo "<p>
    Aqui você pode inserir a informação que desejar incluindo tags HTML.
    </p>";
}
function wpc_add_dashboard_widgets() {
    wp_add_dashboard_widget('wp_dashboard_widget', 'Edição do site - lembretes', 'wpc_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'wpc_add_dashboard_widgets' );
