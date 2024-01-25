<?php 

// No seu plugin, adicione isso ao seu arquivo PHP principal ou apropriado
add_action('wp_ajax_rejeitar_solicitacao', 'rejeitar_solicitacao_callback');

function rejeitar_solicitacao_callback() {
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']); 

        global $wpdb;

        $table_name = $wpdb->prefix . 'fcdl_sytem';

        // Obtendo dados da tabela
        $query = $wpdb->prepare("SELECT valor FROM $table_name WHERE valor LIKE %s", '%' . $id . '%');
        $results = $wpdb->get_results($query);

        if (empty($results)) {
            echo json_encode(['error' => 'Nenhum resultado encontrado']);
            wp_die();
        }

        foreach ($results as $result) {
            $valor = json_decode($result->valor, true);
            // altera o status no banco de dados
            $valor['status'] = 'rejeitado';

            $json_modificado = json_encode($valor);

            // Atualize a tabela com o novo valor
            $wpdb->query($wpdb->prepare("UPDATE $table_name SET valor = %s WHERE valor = %s", $json_modificado, $result->valor));
        }

        echo json_encode(['success' => 'Solicitação rejeitada']);
    } 

    wp_die(); 
}
