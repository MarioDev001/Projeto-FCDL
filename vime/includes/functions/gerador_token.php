<?php 

global $wpdb;

$table_name = $wpdb->prefix . 'fcdl_sytem';

$query = $wpdb->prepare("SELECT identificador FROM $table_name WHERE identificador = %s", 'token_admin');

// Execute a consulta e obtenha o valor
$result = $wpdb->get_var($query);

$format = array(
    '%s', // para identificador (string)
    '%s', // para valor (string)
);

$token = substr(str_shuffle("!@#$%^&*()-_+=abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 80);

$data = array(
    'identificador' => 'token_admin',
    'valor' => json_encode(array(
        'token' => $token, 
    )), 
);

// Verifique se o resultado está vazio
if (empty($result)) {
    // A consulta não retornou nenhum resultado
    $result = $wpdb->insert($table_name, $data, $format);
}