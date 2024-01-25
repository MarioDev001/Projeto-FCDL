<?php 

function registro_solicitacao($url, $status) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'fcdl_sytem';

    $format = array(
        '%s', // para identificador (string)
        '%s', // para valor (string)
    );


    $data = array(
        'valor' => json_encode(array(
            'url_site' => $url, 
            'status' => $status, 
            'data' => current_time('Y-m-d H:i:s'), 
        )), 
    );

    $wpdb->insert(
        $table_name,
        array_merge($data, array('identificador' => "status_solicitacao")),  // Adiciona o identificador aos novos dados
        $format
    );


}