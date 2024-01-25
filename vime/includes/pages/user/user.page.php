<?php

// Função que será executada quando a primeira página do submenu for acessada
function users() {
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'fcdl_sytem';


    $identificador = 'user_dados';
    $query = $wpdb->prepare("SELECT valor FROM $table_name WHERE identificador LIKE %s", '%' . $wpdb->esc_like($identificador) . '%');

    // Obtendo a linha da tabela
    $data = $wpdb->get_row($query);

    $decodedData = json_decode($data->valor);
    $urlSite = $decodedData ? $decodedData->url_site : "";
    $tokenSite = $decodedData ?  $decodedData->token_site : "";
    $nome = $decodedData ? $decodedData->nome : "";
    $email = $decodedData ? $decodedData->email : "";
    $token_auth = $decodedData ? $decodedData->token_auth : "";
    $dominio = $decodedData ? $decodedData->dominio : "";
    $token_status = $decodedData ? $decodedData->status : "";
    $copias = $decodedData ? $decodedData->copias : "";
    
    $copiaFormat = stripslashes($copias);
    $copiaFini =json_decode($copiaFormat);
    $url_do_dominio = home_url();

    $post = $copiaFini[0] === 'post' ? true : '';
    $pagina = $copiaFini[1] === 'pagina' ? true : '';
    

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
        .div_input{
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .div_input h3{
            font-size: 14px;
            margin: 0;
        }
        .input_check{
            margin: 0 !important;
        }
        .div_check{
            display: flex;
            gap: 10px;
            flex-direction: column;
        }
        .div_pcao{
            margin-top: 30px;
        }
        .salve_defi{
            display: flex;
            gap: 30px;
        }
        .status{
            font-weight: 700;
            color: red;
        }
        .info_site .div_input{
            margin-bottom: 10px;
        }
        #log{
            display: none;
        }
        .divLogs{
            background-color: #f2f2f4;
            padding: 5px;
            border-radius: 8px;
            width: 50%;
        }
        .divLogs p{
            color: red;
            margin: 5px 0;
        }
        .solicitacaoMessage{
            font-size: 14px;
            color: red;
            margin: 0;
        }

        .spinner2 {
            border: 4px solid rgba(0,0,0,.1);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border-left-color: #09f;
            animation: spin 1s ease infinite;
            display: none;
        }
        #loadingSpinner{
            display: flex;
            align-items: center;
        }
        #loadingSpinner .ativo{
            display: block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
    </style>
    <main id="main_fcdl" class="fcdl_plugin">
        <div class="title_fcdl">
            <h1>Configuração de Usuário</h1>
        </div>
        <div class="menu_fcdl">
            <div><a href="">Painel</a></div>
            <div><a href="">Configuração</a></div>
            <div><a href="">Dashbord</a></div>
        </div>
        <div class="painel">
            <div id="log">
                <div>
                    <h2>Logs de solicitação</h2>
                </div>
                <div class="divLogs">
                    <p class="message"></p>
                </div>
                
            </div>
            <div class="info_site">
                <div>
                    <h2>Informações do site para ser extraido os dados:</h2>
                </div>
                <div class="div_input">
                    <label for="url_font">Url do site fonte:</label for="">
                    <input value="<?php echo esc_html($urlSite); ?>" id="url_font" name="url_font" type="text">
                </div>
                <div class="div_input">
                    <label for="token_font">Token do site fonte:</label for="">
                    <input value="<?php echo esc_html($tokenSite); ?>" name="token_font" id="token_font" type="text">
                </div>
            </div>
            <div>
                <div class="div_pcao">

                    <h2>Configuração de copia</h2>
                    <div class="div_check">
                        <div class="div_input">
                            <input class="input_check" name="post" id="post" type="checkbox" <?php echo $post ? esc_html('checked') : '' ?>>
                            <label  for="post">Post</label>
                        </div>
                        <div class="div_input">
                            <input class="input_check" name="pagina" id="pagina" type="checkbox" <?php echo $pagina ? esc_html('checked') : '' ?>>
                            <label for="pagina">Página</label>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="div_pcao">
                    <h2>Configuração de dados para solicitação de copia</h2>
                    <div class="div_check">
                        <div class="div_input">
                            <label for="nome">Usuário</label>
                            <input value="<?php echo esc_html($nome); ?>" class="input_check" name="nome" id="nome" type="text">
                        </div>
                        <div class="div_input">
                            <label for="email">E-mail</label>
                            <input value="<?php echo esc_html($email); ?>" class="input_check" name="email" id="email" type="text">
                        </div>
                        <div class="div_input">
                            <label for="dominio">Url do Domínio atual: </label>
                            <input placeholder="https://meuDominio.com" value="<?php echo isset($dominio) ? esc_html($dominio) : esc_html($url_do_dominio); ?>" class="input_check" name="dominio" id="dominio" type="text" required>
                        </div>
                        <div>
                        <?php 
                            if($token_status === 'solicitado'){
                                echo ' <div class="div_input">
                                <label for="token">Token Auth</label>
                                <p class="solicitacaoMessage">Solicitação em análise</p>
                            </div>';
                            } elseif ($token_status === 'aceito') {
                                echo '<div class="div_input">
                                        <h2 style="color:green;" class="soli ' . $token_auth . '" >Solicitação Autorizada!</h2 >
                                    </div>';
                            } 
                        ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div>
                <div class="div_pcao salve_defi">
                    <div>
                        <button class="salvar-dados-user">Enviar Solicitação</button>
                    </div>
                    <div id="loadingSpinner">
                        <div class="spinner2"></div>
                        <div class="message"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="config">
            <div>
                <div class="div_pcao">
                    <h2>Configuração de atualização</h2>
                    <div class="div_check">
                        <div class="div_input">
                            <label for="tempo_intervalo">Intervalo de tempo para atualizaçãao em minutos:</label>
                            <input value="60" class="tempo_intervalo" name="tempo_intervalo" id="tempo_intervalo" type="number">
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function realizarPost() {
            var statuSpinner = document.querySelector("#loadingSpinner .spinner2");
            var statusMessage = document.querySelector("#loadingSpinner .message");
            if(statusMessage.classList.contains("ativo")){
                statusMessage.innerText = ""
                statusMessage.classList.remove("ativo");
            } 
  
            var url_site = document.getElementById("url_font").value;

            // Dados a serem enviados
            const dados = {
                nome: document.getElementById("nome").value,
                email: document.getElementById("email").value,
                site: document.getElementById("dominio").value, 
                token_site: document.getElementById("token_font").value,
            };

            // URL de destino
            const url = url_site.replace(" ", '') + '/wp-json/fcdl_system/v1/solicitacao_user';

            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json"); 

            // Configurar a requisição
            const opcoes = {
                method: 'POST',
                headers: myHeaders,
                body: JSON.stringify(dados),
                redirect: 'follow'
            };
            statuSpinner.classList.add('ativo')
            console.log(url)
            // Executar a requisição e obter a resposta
            fetch(url, opcoes)
            .then(response => {
                statuSpinner.classList.remove('ativo');

                // Verificar se a resposta foi bem-sucedida (código de status HTTP 2xx)
                statusMessage.classList.add('ativo');

                if (!response.ok) {
                    if (response.status === 406) {
                        // Código de status 406 - Erro no email
                        statusMessage.innerText = "Error no Email: Por favor, verifique ou tente outro endereço";
                    } else {
                        statusMessage.innerText = "Error: Por favor Verifique os campos";
                    }
                } else {
                    statusMessage.innerText = "Solicitação bem-sucedida: Aguarde o token de autorização.";
                    return response.json();
                }
            })
            .catch(error => {
                statuSpinner.classList.remove('ativo');
                statusMessage.innerText = "Error no Token: Token inválido.";
                console.log(error);
            });
        }



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



        function verificao_classe(remove = false){
            remove ? document.querySelectorAll('.'+classeAtiva).forEach(ele => ele.style.display = "none"): 
                document.querySelectorAll('.'+classeAtiva).forEach(ele => ele.style.display = "flex")
        }                  
        verificao_classe()
        
        

        jQuery(document).ready(function($) {
            
            $('.salvar-dados-user').on('click', function() {
                
                realizarPost()
                var url_site = document.getElementById("url_font");
                var token_site = document.getElementById("token_font");
                var nome = document.getElementById("nome");
                var email = document.getElementById("email");
                var dominio = document.getElementById("dominio");
                var elementosChecados = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'));

                var arrayDeCopia = elementosChecados.map(function(elemento) {
                    return elemento.id;
                });

                var dadosUsuario = {
                    action: 'salvar_dados_user',
                    url_site: url_site.value,
                    token_site: token_site.value,
                    nome: nome.value,
                    email: email.value,
                    dominio: dominio.value,
                    copias: JSON.stringify(arrayDeCopia),
                };
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: dadosUsuario,
                    success: function(response) {
                        console.log(JSON.parse(response).Error)
                        
                        try {
                            var responseData = JSON.parse(response);
                                console.log(responseData);
                        } catch (error) {
                            // Se houver um erro ao analisar a resposta JSON, exiba o erro
                            console.error('Erro ao analisar resposta JSON:', error);
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