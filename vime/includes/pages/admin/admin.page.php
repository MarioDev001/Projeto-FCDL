<?php

// Função que será executada quando a primeira página do submenu for acessada
function admin() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'fcdl_sytem';

    // Obtendo dados da tabela
    $query = $wpdb->prepare("SELECT valor FROM $table_name WHERE identificador = %s", 'solicitacao');
    $token = $wpdb->prepare("SELECT valor FROM $table_name WHERE identificador = %s", 'token_admin');
    $results_token = $wpdb->get_results($token);
    $token_admin_value = json_decode($results_token[0]->valor)->token;
    $results = $wpdb->get_results($query);
    ?>
    <style>
        .fcdl_plugin button{
            background-color: #244494;
            padding: 5px  15px;
            border-radius: 8px;
            border: none;
            color: #fff;
            cursor: pointer;
            height: 35px;

        }
        .fcdl_plugin{
            font-family: Inter, system-ui;
            padding: 0 20px 0 0;
        }
        .fcdl_plugin h2{
            font-size: 16px;
        }
        .title_fcdl{
            font-size: 1.2em;
            padding: 0;
            font-weight: var(--uip-text-bold);
            color: var(--uip-text-color-emphasis);
        }
        .menu_fcdl{
            display: flex;
            gap: 5px;
            width: 100%;
            border-bottom: 1px solid #c3c4c7;
            margin-bottom: 30px;
        }
        .menu_fcdl div{
            background: #e7e7e9;
            padding: 7px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        .menu_fcdl a{
            color: #111;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
        }
        .token_fcdl{
            height: 35px;
            width: 50%;
        }

        
        .solicitacao_checks p{
            font-size: 14px;
        }
        .solicitacao_type{
            display: flex;
            align-items: self-end;
            justify-content: start;
            flex-direction: row-reverse;
            margin: 5px 0;
        }
        
        .controller{
            display: flex;
            gap: 15px;
        }
        .solicitacao_container{
            width: 100%;
            height: 100%;
            background-color: #e7e7e9;
            margin-right: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 30px;
        }

        .solicitacao_user{
            background-color: #fff;
            border-radius: 8px;
            width: 100%;
            height: 200px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .solicitacao_data{
            width: 200px;
        }
        .solicitacao_dados{
            width: 40%;
        }

        .solicitacao_data h2{
            font-weight: 400;
            color: #111;
            margin: 0;
            font-size: 16px;
        }
        .solicitacao_dados h2{
            font-weight: 600;
            color: #111;
            margin: 0;
            font-size: 15px;
        }

        .solicitacao_data span{
            font-weight: 600;
            font-size: 14px;
        }
        .solicitacao_dados span {
            font-weight: 400;
            font-size: 16px;
        }
        .solicitacao_data p{
            margin: 0;
        }

        .solicitacao_menu{
            margin-bottom: 50px;
        }
        .solicitacao{
            
            padding-top: 30px;
        }
        .concluido, .rejeitado, .pendente{
            display: none;
        }
    </style>
    <style>

    </style>
    <main id="main_fcdl" class="fcdl_plugin">
        <div class="title_fcdl">
            <h1>Sistema FCDL</h1>
        </div>
        <div class="menu_fcdl">
            <div><a href="">Configuração</a></div>
            <div><a href="">Dashbord</a></div>
        </div>
        <div class="config">
            <div class="token_container">
                <h2>Token de acesso para copia</h2>
                <div class="gerado_input">
                    <input value="<?php echo  $token_admin_value; ?>" class="token_fcdl" type="text" readonly>
                    <button class="copiar_token">Copiar</button>
                </div>
            </div>
            <div class="solicitacao">
                <h2>Solicitação de Copias</h2>
                <div class="menu_fcdl solicitacao_menu">
                    <div><a id="pendente" class="btn_solicitacoes" href="">Pendente</a></div>
                    <div><a id="concluido" class="btn_solicitacoes" href="">Concluido</a></div>
                    <div><a id="rejeitado" class="btn_solicitacoes" href="">Rejeitado</a></div>
                </div>
                <div class="solicitacao_checks">
                    <div class="solicitacao_container">
                        <?php 
                            // Exibindo os dados no formato HTML
                            foreach ($results as $result) {
                                $data = json_decode($result->valor, true);

                                echo '<div id="' . esc_html($data['email']) . '" class="solicitacao_user ' .  esc_html($data['status']) . '">';
                                echo '<div class="dashicons-example"><span class="dashicons dashicons-admin-users"></span></div>';
                                echo '<div class="solicitacao_data">';
                                echo '<p>Data: <span>' . date('d/m/Y H:i', strtotime($data['data'])) . '</span></p>';
                                echo '</div>';
                                echo '<div class="solicitacao_dados">';
                                echo '<h2 class="infos">Nome:<span> '. esc_html($data['nome']) . '</span> </h2>';
                                echo '<h2 class="infos">Email:<span> '. esc_html($data['email']) . '</span> </h2>';
                                echo '<h2 class="infos url">Site:<span> '. esc_html($data['site']) . '</span> </h2>';
                                echo '<h2 class="infos">Login:<span> '. esc_html($data['login']) . '</span> </h2>';
                                echo '<h2 class="infos">Ip:<span> '. esc_html($data['ip']) . '</span> </h2>';
                                echo '</div>';
                                echo '<div class="controller">';
                                echo '<button>Visualizar</button>';
                                echo '<button data-url="' . esc_html($data['site']) . '" data-id="' . esc_html($data['login']) . '" class="aceitar-btn">Aceitar</button>';
                                echo '<button data-id="' . esc_html($data['login']) . '" class="rejeitar-btn">Rejeitar</button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                    
                </div>
            </div>
            <div class="dashbord">
                
            </div>
        </div>
    </main>
    <script>
        var main = document.querySelector("#main_fcdl");
        var classeAtiva = "pendente";

        document.querySelectorAll(".btn_solicitacoes").forEach((btnSolitacao) => {
            var id = btnSolitacao.id
            btnSolitacao.addEventListener("click", (event) => {
                console.log(classeAtiva)
                event.preventDefault();
                verificao_classe(true)
                classeAtiva = id;
                console.log(classeAtiva)
                verificao_classe()
            })
            
        })

        //botão de copiar o token

        document.querySelector('.copiar_token').addEventListener("click", () =>{
            document.querySelector(".token_fcdl").select();
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
        })

        function verificao_classe(remove = false){
            remove ? document.querySelectorAll('.'+classeAtiva).forEach(ele => ele.style.display = "none"): 
                document.querySelectorAll('.'+classeAtiva).forEach(ele => ele.style.display = "flex")
        }                  
        verificao_classe()
        
        function enviar_token_usuario(id, urlUser) {
            const url = urlUser.replace(" ", '').replace(" ", '') + '/wp-json/fcdl_system/v1/receber_token_confirmacao';
            const dados = {
                token_site: id,
            };
            console.log(url)
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json"); 

            // Configurar a requisição
            const opcoes = {
                method: 'POST',
                headers: myHeaders,
                body: JSON.stringify(dados)
            };

            // Executar a requisição e obter a resposta
            fetch(url, opcoes)
            .then(response => {
                // Verificar se a resposta foi bem-sucedida (código de status HTTP 2xx)
                if (!response.ok) {
                    
                    throw new Error('Erro na solicitação: ' + response.status);
                }
                console.log(response.message)
                
            })

            .catch(error => {
                console.log('Error na url: por favor, verifique.' + error)
            });
        }


        jQuery(document).ready(function($) {
            $('.aceitar-btn').on('click', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                enviar_token_usuario(id,url)
                
            });
        });

        jQuery(document).ready(function($) {
            $('.rejeitar-btn').on('click', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'rejeitar_solicitacao',
                        id: id,
                    },
                    success: function(response) {
                        // Parse JSON da resposta
                        var responseData = JSON.parse(response);

                        // Verificar se há um campo 'error' e se seu valor é igual a 'error'
                        if (responseData.hasOwnProperty('error') && responseData.error === 'error') {
                            // Faça algo se for um erro
                            console.error('Ocorreu um erro:', responseData);
                        } else {
                            var divSoli = document.querySelector('.'+CSS.escape(id))
                            divSoli.classList.include('concluido') ? classList.classList.remove('concluido'): null;
                            divSoli.classList.include('pendente') ? divSoli.classList.remove('pendente') :  null;
                            divSoli.classList.add("rejeitado")
                            divSoli.style.display = "none";
                            console.log(responseData);
                        }
                    },
                    error: function(error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            });
        });
    </script>
    <?php
}