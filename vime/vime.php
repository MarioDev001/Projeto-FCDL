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
function check_for_plugin_update() {
    $current_version = '1.0.1'; // Versão atual do seu plugin
    $json_url = 'https://raw.githubusercontent.com/MarioDev001/Projeto-FCDL/main/update-manifest.json'; // URL do manifesto no GitHub

    $response = wp_remote_get($json_url);
    if (is_wp_error($response)) {
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if (version_compare($current_version, $data->version, '<')) {
        // Passa os dados para a função show_update_notice
        add_action('admin_notices', function () use ($data) {
            show_update_notice($data);
        });
    }
}

function show_update_notice($data) {
    echo '<div class="notice notice-info is-dismissible"><p>Uma nova versão do Seu Plugin está disponível. <a href="' . $data->download_url . '">Atualize agora</a>.</p></div>';
}

add_action('admin_init', 'check_for_plugin_update');


