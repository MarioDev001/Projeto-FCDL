<?php 

function criar_tabela_fcdl() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Defina suas instruções SQL para criar tabelas
    $sql = "
        CREATE TABLE {$wpdb->prefix}fcdl_sytem (
            id INT NOT NULL AUTO_INCREMENT,
            identificador VARCHAR(255) NOT NULL,
            valor LONGTEXT NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;
    ";

    // Execute a instrução SQL
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

criar_tabela_fcdl();
