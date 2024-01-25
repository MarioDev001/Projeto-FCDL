<?php

function receber_token_confirmacao($dados) {
    // Obtém o token_auth da requisição
    $token_auth = $dados['token_auth'];

    global $wpdb;

    $table_name = $wpdb->prefix . 'fcdl_sytem';

    // Obtém o identificador da tabela
    $identificador = 'user_dados';

    // Obtém os dados atuais da tabela com base no identificador
    $data_atual = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table_name WHERE identificador = %s", $identificador),
        ARRAY_A
    );

    if ($data_atual) {
        // Decodifica o JSON armazenado na coluna 'valor'
        $valor_atual = json_decode($data_atual['valor'], true);

        // Atualiza a chave $token_auth no JSON com o novo token
        $valor_atual['token_auth'] = $token_auth;
        $valor_atual['status'] = 'aceito';

        // Codifica novamente o JSON atualizado
        $novo_valor = json_encode($valor_atual);

        // Atualiza a tabela com os novos dados
        $wpdb->update(
            $table_name,
            array('valor' => $novo_valor),
            array('identificador' => $identificador),
            array('%s'),
            array('%s')
        );

        if ($wpdb->rows_affected > 0) {
            // Se a atualização for bem-sucedida, retornar uma mensagem de sucesso
            return new WP_REST_Response(array(
                "message" => "Token atualizado com sucesso!",
            ), 201);
        } else {
            // Se houver falha na atualização, retornar um erro interno do servidor
            return new WP_REST_Response(array(
                "error" => "Falha no servidor",
            ), 500);
        }
    } else {
        // Se o registro não for encontrado, retornar um erro
        return new WP_REST_Response(array(
            "error" => "Registro não encontrado para o identificador: $identificador",
        ), 404);
    }
}

function registrar_endpoint() {
    register_rest_route('fcdl_system/v1', '/receber_token_confirmacao', array(
        'methods' => 'POST',
        'callback' => 'receber_token_confirmacao',
        'permission_callback' => '__return_true', // Permitir acesso sem autenticação (pode ser ajustado conforme necessário)
    ));
}

add_action('rest_api_init', 'registrar_endpoint');
