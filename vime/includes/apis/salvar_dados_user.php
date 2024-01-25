<?php 


include_once(plugin_dir_path(__FILE__) . './registro_solicitacao.php');


// No seu plugin, adicione isso ao seu arquivo PHP principal ou apropriado
add_action('wp_ajax_salvar_dados_user', 'salvar_dados_user');

function salvar_dados_user() {
    $url_site = isset($_POST['url_site']) ? $_POST['url_site'] : false;
    $token_site = isset($_POST['token_site']) ? $_POST['token_site'] : false;
    $nome = isset($_POST['nome']) ? $_POST['nome'] : false;
    $email= isset($_POST['email']) ? $_POST['email'] : false;
    $dominio= isset($_POST['dominio']) ? $_POST['dominio'] : false;
    $token_auth = '';
    $copias = $_POST['copias'];
    $current_date = current_time('Y-m-d H:i:s');

    $identificador = 'user_dados';

    if ($url_site && $token_site && $nome && $email) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'fcdl_sytem';

        $format = array(
            '%s', // para identificador (string)
            '%s', // para valor (string)
        );
        
        $data = array(
            'valor' => json_encode(array(
                'url_site' => $url_site, 
                'token_site' => $token_site, 
                'nome' => $nome, 
                'email' => $email, 
                'token_auth' => $token_auth,
                'dominio' => $dominio,
                'copias' => $copias, 
                'current_date' => $current_date,  
                'status' => "solicitado",  
            )), 
        );

        // Verifica se o registro já existe
        $registro_existente = $wpdb->get_row("SELECT * FROM $table_name WHERE identificador = '$identificador'");

        if ($registro_existente) {
            // Registro já existe, então você pode atualizar os dados
            $result = $wpdb->update(
                $table_name,
                $data,  // Seus novos dados
                array('identificador' => $identificador),
                $format
            );

            
            registro_solicitacao($url_site, "aberto");
            echo json_encode(['success' => "atualizado"]);
    
            wp_die();
        } 

        $result = $wpdb->insert(
            $table_name,
            array_merge($data, array('identificador' => $identificador)),  // Adiciona o identificador aos novos dados
            $format
        );

        if ($result) {
            registro_solicitacao($url_site, "aberto");
            echo json_encode(['success' => "criado"]);
            
            wp_die();
        } 

        echo json_encode(['error' => $wpdb->last_error]);
        wp_die();
    } else {
        echo json_encode(['error' => [$url_site, $token_site, $nome, $email]]);
    }

    wp_die(); 
}
