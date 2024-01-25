<?php 

include_once(plugin_dir_path(__FILE__) . 'user/user.page.php');
include_once(plugin_dir_path(__FILE__) . 'admin/admin.page.php');

// Função para adicionar as entradas de menu e submenu
function meu_plugin_adicionar_menu() {
    add_menu_page(
        'Configurações System FCDL', // Título da página
        'Configurações System FCDL', // Nome no menu
        'manage_options', // Capacidade necessária para acessar a página
        'admin', // Slug da página
        'admin', // Função a ser chamada para exibir a página
        'dashicons-groups' // Ícone do menu (consulte https://developer.wordpress.org/resource/dashicons/)
    );

    add_submenu_page(
        'admin', // Slug da página principal
        'Users', // Título da página de submenu
        'Users', // Nome no menu
        'manage_options', // Capacidade necessária para acessar a página
        'users', // Slug da página de submenu
        'users' // Função a ser chamada para exibir a página de submenu
    );
}

// Adicionar as entradas de menu e submenu no gancho admin_menu
add_action('admin_menu', 'meu_plugin_adicionar_menu');