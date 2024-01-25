<?php 

// No seu plugin, adicione isso ao seu arquivo PHP principal ou apropriado
add_action('wp_ajax_aceitar_solicitacao', 'aceitar_solicitacao_callback');

function aceitar_solicitacao_callback() {
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

            $nome = $valor['nome'];
            $email = $valor['email'];
            $senha = $valor['senha']; // Use 'nome' ou 'email' para o user_login e user_pass
            $site = $valor['site'];

            // Criar um novo usuário como assinante
            $novo_usuario = wp_insert_user(array(
                'first_name' => $nome,
                'user_login' => $senha, // Use 'nome' ou 'email' aqui
                'user_pass' => $senha,
                'user_email' => $email,
                'user_url' => $site,
                'role' => 'subscriber', 
            ));

            // Verificar se houve algum erro durante a criação do usuário
            if (is_wp_error($novo_usuario)) {
                echo json_encode(['error' => $novo_usuario->get_error_message()]);
                wp_die();
            }

            $valor['status'] = 'concluido';

            $json_modificado = json_encode($valor);

            // Atualize a tabela com o novo valor
            $wpdb->query($wpdb->prepare("UPDATE $table_name SET valor = %s WHERE valor = %s", $json_modificado, $result->valor));
        }

        echo json_encode(['success' => $senha]);
    } 

    wp_die(); 
}
