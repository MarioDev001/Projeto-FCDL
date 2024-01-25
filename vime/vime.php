<?php
/*
Plugin Name: Vime FCDL Plugin
Description: Descrição do seu plugin.
Version: 1.0.0
Author: Seu Nome
*/


header("Access-Control-Allow-Origin: *"); // Permite solicitações de qualquer origem (não seguro para produção)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Cabeçalhos permitidos
header("Content-Type: application/json"); // Define o tipo de conteúdo como JSON


// functions de configuracao do ambiente
include_once(plugin_dir_path(__FILE__) . 'includes/functions/dbconfig.php');
include_once(plugin_dir_path(__FILE__) . 'includes/functions/gerador_token.php');

// rotas de api
include_once(plugin_dir_path(__FILE__) . 'includes/apis/solicitacao_usuario.php');
include_once(plugin_dir_path(__FILE__) . 'includes/apis/salvar_dados_user.php');
include_once(plugin_dir_path(__FILE__) . 'includes/apis/receber_token_site_pai.php');

include_once(plugin_dir_path(__FILE__) . 'includes/apis/aceitar_user.php');
include_once(plugin_dir_path(__FILE__) . 'includes/apis/rejeitar_solicitacao_user.php');

include_once(plugin_dir_path(__FILE__) . 'includes/pages/index.php');

// Adicione a função de desinstalação
register_uninstall_hook(__FILE__, 'desinstalar_plugin');

// Função de desinstalação
function desinstalar_plugin() {
    global $wpdb;

    // Defina o nome da tabela do seu plugin
    $table_name = $wpdb->prefix . 'fcdl_sytem';

    // SQL para excluir a tabela
    $sql = "DROP TABLE IF EXISTS $table_name;";

    // Execute a consulta SQL
    $wpdb->query($sql);

    // Você pode adicionar mais lógica de desinstalação aqui, se necessário
}

function realizarPost($url, $dados) {
    // Configurar a requisição
    $opcoes = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => json_encode($dados),
        ],
    ];

    // Criar o contexto da requisição
    $contexto = stream_context_create($opcoes);

    // Executar a requisição e obter a resposta
    $resposta = file_get_contents($url, false, $contexto);

    // Retornar a resposta (pode ser tratada conforme necessário)
    return $resposta;
}

// Dados a serem enviados
$dados = [
    "nome" => "test3",
    "email" => "$email5",
    "site" => home_url(),
    "token_site" => "U9Dvqy0%+m32PNw4RITYfVGg1HbC#l-EuzQ@F^iL!6J)SeM*c7oj5W=$8tAaKkpZ_xsBXrO&d(hn",
];

// URL de destino
$url = "https://vime.digital/fcdlfilho/wp-json/fcdl_system/v1/solicitacao_user";

// Realizar o POST
$resposta = realizarPost($url, $dados);

// Imprimir a resposta (pode ser tratada conforme necessário)
echo $resposta;
?>