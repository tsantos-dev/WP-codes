<?php
//functions
add_action("admin_init", "pdf_init");
add_action('save_post', 'save_pdf_link');
function pdf_init(){
    add_meta_box("my-pdf", "Documento PDF", "pdf_link", "post", "normal", "low");
    }
function pdf_link(){
    global $post;
    $custom  = get_post_custom($post->ID);
    $link    = $custom["link"][0];
    $count   = 0;
    echo '<p>Selecione a lei que está relacionado ao item</p>';
    echo '<div class="link_header">';
    $query_pdf_args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'application/pdf',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
        );
    $query_pdf = new WP_Query( $query_pdf_args );
    $pdf = array();

    
    echo '<select name="link">';
    echo '<option class="pdf_select">SELECIONE o pdf</option>';
    foreach ( $query_pdf->posts as $file) {
        if($link == $pdf[]= $file->guid){
            echo '<option value="'.$pdf[]= $file->guid.'" selected="true">'.$pdf[]= $file->post_title.'</option>';
        }else{
            echo '<option value="'.$pdf[]= $file->guid.'">'.$pdf[]= $file->post_title.'</option>';
        }
        $count++;
    }
    echo '</select><br /></div>';
    echo '<div class="pdf_count"><span>PDF\'s encontrados:</span> <b>'.$count.'</b></div>';
    echo '<div class="pdf_shortcode"><span>Shortcode para inserir no texto: </span><b>[pdf_embbed]</b></div>';
}
function save_pdf_link(){
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ return $post->ID; }
    update_post_meta($post->ID, "link", $_POST["link"]);
}
add_action( 'admin_head', 'pdf_css' );
function pdf_css() {
    echo '<style type="text/css">
    .pdf_select{
        font-weight:bold;
        background:#e5e5e5;
        }
    .pdf_count{
        font-size:9px;
        color:#0066ff;
        text-transform:uppercase;
        background:#f3f3f3;
        border-top:solid 1px #e5e5e5;
        padding:6px 6px 6px 12px;
        margin:0px -6px -8px -6px;
        -moz-border-radius:0px 0px 6px 6px;
        -webkit-border-radius:0px 0px 6px 6px;
        border-radius:0px 0px 6px 6px;
        }
    .pdf_count span{color:#666;}
    .pdf_shortcode{
		font-size:9px;
        color:#0066ff;
		background:#f3f3f3;
        border-top:solid 1px #e5e5e5;
        padding:6px 6px 6px 12px;
        margin:0px -6px -8px -6px;
        -moz-border-radius:0px 0px 6px 6px;
        -webkit-border-radius:0px 0px 6px 6px;
        border-radius:0px 0px 6px 6px;}
        </style>';
}
function pdf_file_url(){
    global $wp_query;
    $custom = get_post_custom($wp_query->post->ID);
    if($custom['link'][0] != ''){
        echo $custom['link'][0];
    }
}

//shortcode para embbedar o arquivo no the_content
function pdf_shortcode($atts, $content = null) {
	$pdf = get_post_meta( get_the_ID(), 'link' );
	extract(shortcode_atts(array(
		"id" => 'pdf_embbeded',
		"url" => $pdf[0],
		"width" => '100%',
		"height" => '743px'
	), $atts));
	return '<iframe id="' . $id . '" src="http://docs.google.com/viewer?url=' . $url . '&embedded=true" style="width:' . $width . '; height:' . $height . ';" frameborder="0"></iframe>';
}
add_shortcode('pdf_embbed', 'pdf_shortcode');
?>

<!-- inserir no local de exibição do link para o PDF -->
<a href="<?php pdf_file_url(); ?>">Baixar PDF</a>
