<?php


function solicitacao($dados) {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $nome = $dados['nome'];
    $email = $dados['email'];
    $site = $dados['site'];
    $token_site = $dados['token_site'];
    $current_date = current_time('Y-m-d H:i:s');

    // Formatando o nome (removendo espaços e caracteres especiais)
    $nome_formatado = preg_replace("/[^a-zA-Z0-9]/", "", $nome);

    // Adicionando números aleatórios ao final do nome (15 caracteres)
    $numeros_aleatorios = substr(str_shuffle("!@#$%^&*()-_+=abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 15);
    $code = $nome_formatado . $numeros_aleatorios;

    global $wpdb;

    $table_name = $wpdb->prefix . 'fcdl_sytem';

    $data = array(
        'identificador' => 'solicitacao',
        'valor' => json_encode(array(
            'nome' => $nome,
            'email' => $email,
            'site' => $site,
            'senha' => $code,
            'login' => $code,
            'data' => $current_date,
            'ip' => $user_ip,
            'status' => 'pendente'
        )),
    );

    $format = array(
        '%s', // para identificador (string)
        '%s', // para valor (string)
    );

    // Verificar se o e-mail já existe
    $query = $wpdb->prepare("SELECT valor FROM $table_name WHERE valor LIKE %s", '%' . $email . '%');
    $results = $wpdb->get_results($query);
    

    // Verificar se há resultados
    if (!empty($results)) {
        // Se houver resultados, retornar um erro indicando que o e-mail já existe
        return new WP_REST_Response(array(
            "error" => "Email existente",
        ), 406);
    }

    // Verificar o token de administração
    $veri_token = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE identificador = %s", 'token_admin'), ARRAY_A);
    $token_admin = json_decode($veri_token['valor'])->token;

    if ($token_admin !== $token_site) {
        // Se o token não corresponder, retornar um erro de autenticação
        return new WP_REST_Response(array(
            "error" => "Token de autenticação inválido",
        ), 407);
    }

    // Inserir os dados no banco de dados
    $result = $wpdb->insert($table_name, $data, $format);

    if ($result !== false) {
        // Se a inserção for bem-sucedida, retornar uma mensagem de sucesso
        return new WP_REST_Response(array(
            "message" => $token_admin,
            "codigo" => $data,
        ), 201);
    } else {
        // Se houver falha na inserção, retornar um erro interno do servidor
        return new WP_REST_Response(array(
            "error" => "Falha no servidor",
        ), 500);
    }
}


function receber_json_endpoint() {
    register_rest_route('fcdl_system/v1', '/solicitacao_user/', array(
        'methods' => 'POST',
        'callback' => 'solicitacao',
        'permission_callback' => '__return_true', // Permitir acesso sem autenticação (pode ser ajustado conforme necessário)
    ));
}

add_action('rest_api_init', 'receber_json_endpoint');