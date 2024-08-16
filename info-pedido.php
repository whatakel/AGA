<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $curl = curl_init();

    /***** obtem o token de acesso *****/

    $erro           = false;
    $mensagem_erro  = '';

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://aga.totem.app.br/_custom/api/v1/auth',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
                "usuario": "testeapi"
                ,"senha": "54d12332466253425d"
            }',
        CURLOPT_HTTPHEADER => array(
            'x-api-secret: d4cfc2a8e0bbaa1234d89c64e85c59a5db63a8e7d4b1af8a6c4e87a8b4d95a64',
            'x-api-public: BkwAK4oPuGiF7JRQFM97seT92RJ0o5lGGX83rBNHbHDid2cMmAmTOOyeSXGCKidhZjalTBK3PRLaUyIJP26KQxYEngDkJyEYmhnN5CYHkkeOS7BeJYbxUEalspAEx1ec',
            'x-api-key: CnHSOyfrROIIQZsTzCIbmWBL8KAfl6tgFa0cO0ozO9YWTROBL0dAideAIJWLytdoC4p8LZgUUyQ4B6po4C3g7FCZc5t8xS830ImgmSN6TAeJ4EGgMuD5TASDcIMS04a',
            'Content-Type: application/json'
        ),
    ));

    $response   = curl_exec($curl);
    $err        = curl_error($curl);
    $httpcode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($err) {
        $erro = true;
        $mensagem_erro = 'Erro obten��o do token: '.$httpcode . ' - ' . $err;
    } else {
        if (
            $httpcode != 200
            && $httpcode != 201
        ) {
            $erro = true;
            $mensagem_erro = 'Erro obten��o da token HTTP: ' . $httpcode;
        }
    }

    if($erro) {
        echo $mensagem_erro;
        exit;
    }

    $resposta = json_decode($response);

    //echo '<pre>'; print_r($resposta); echo '</pre>';

    $token        = $resposta->data->access_token;
    $token_tipo   = $resposta->data->token_type;


    /***** obtem os dados dos pedidos *****/

    $erro           = false;
    $mensagem_erro  = '';

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://aga.totem.app.br/_custom/api/v1/pedidos/'. ($_GET['codigo'] ?? ''),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'x-api-secret: d4cfc2a8e0bbaa1234d89c64e85c59a5db63a8e7d4b1af8a6c4e87a8b4d95a64',
            'x-api-public: BkwAK4oPuGiF7JRQFM97seT92RJ0o5lGGX83rBNHbHDid2cMmAmTOOyeSXGCKidhZjalTBK3PRLaUyIJP26KQxYEngDkJyEYmhnN5CYHkkeOS7BeJYbxUEalspAEx1ec',
            'Authorization: '.$token_tipo.' '.$token,
            'Content-Type: application/json'
        ),
    ));

    $response   = curl_exec($curl);
    $err        = curl_error($curl);
    $httpcode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($err) {
        $erro = true;
        $mensagem_erro = 'Erro obtenção dos pedidos: '.$httpcode . ' - ' . $err;
    } else {
        if (
            $httpcode != 200
            && $httpcode != 201
        ) {
            $erro = true;
            $mensagem_erro = 'Erro obtenção dos pedidos HTTP: ' . $httpcode;
        }
    }

    if($erro) {
        echo $mensagem_erro;
        exit;
    }

    $resposta = json_decode($response);

    
    // $pedido = $resposta->data[0];
   // echo '<pre>'; print_r($pedido); echo '</pre>';

    curl_close($curl);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protótipo tela Gestão de Pedidos</title>

    <script src="https://kit.fontawesome.com/edb1b9d001.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plataforma.css">
</head>

<body style="padding: 0; margin: 0;">

                <div class="detalhes-pedido box-border rounded column col-12">
                    <div class="titulo-pagina ctn-detalhes d-flex gap-2 gap-xl-3 p-2 d-flex flex-wrap justify-content-between">
                        <div class="col-auto d-flex flex-row gap-1 ">
                            <div>
                                <h6 class="px-2 m-0 d-flex">#000000 / #000000</h6>
                                <p class="data-pedido px-2 m-0">21/06/2024 às 10:37</p>
                            </div>
                            <div
                                class="acoes-pedidos col-auto d-none d-lg-flex d-flex gap-2 gap-xl-3 align-items-center">
                                <i class="pedido-action funcao-pedido fa-solid fa-sheet-plastic" title="Protocolo" data-bs-toggle="modal" data-bs-target="#modal-protocolo"></i>
                                <span class="m-0 p-0">/</span>
                                <i class="pedido-action funcao-pedido fa-solid fa-print"
                                    title="Imprimir cópia do pedido"></i>
                            </div>
                        </div>
                        <div
                            class="col-auto acoes-pedidos acoes-principais d-none d-lg-flex gap-2 gap-xl-3 align-items-center">
                            <a href=""><i class="status-lista status-done fa-solid fa-thumbs-up"
                                    title="Confirmado"></i></a>
                            <span class="m-0 p-0">/</span>
                            <a href=""><i class="status-lista fa-solid fa-cart-flatbed"
                                    title="Prazo de entrega"></i></a>
                            <span class="m-0 p-0">/</span>
                            <a href=""><i class="status-lista fa-solid fa-boxes-packing"
                                    title="Imprimir folha de separação"></i></a>
                            <span class="m-0 p-0">/</span>
                            <a href=""><i class="status-lista fa-solid fa-scale-balanced"
                                    title="Ajuste de peso"></i></a>
                            <span class="m-0 p-0">/</span>
                            <a href=""><i class="status-lista fa-solid fa-cash-register"
                                    title="Faturar + Enviar"></i></a>
                            <span class="m-0 p-0">/</span>
                            <a href=""><i class="status-lista fa-solid fa-rotate-left" title="Reabrir venda"></i></a>
                            <span class="m-0 p-0">/</span>
                            <a href=""><i class="status-lista fa-solid fa-lock-open"
                                    title="Devolver para o caixa"></i></a>
                        </div>
                        <div class="d-flex d-flex gap-2 gap-xl-3 col-auto m-0 justify-content-between">
                            <div class="acoes-pedidos col-auto d-none d-lg-flex gap-2 gap-xl-3 align-items-center">
                                <a href=""><i class="pedido-action fa-solid fa-pen" title="Editar pedido"></i></a>
                            </div>
                            <!-- Botão para chamar a próxima ação do pedido 
                                 Atributos apenas para modal de selecionar data de entrega-->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#data-entrega"
                                class="btn btn-sm btn-salvar btn-primary col-auto d-flex align-items-center gap-1">
                                <i class="fa-solid fa-thumbs-up"></i>
                                <p class="m-0">Confirmar</p>
                            </button>
                        </div>
                    </div>


                    <!-- Tela confirmação pedido -->
                                                                          
                    <div class="ctn-pedido confirmar-pedido m-3">
                        <div class="info-cliente row">
                            <div class="container form-group">
                                <div class="row my-3">
                                    <div class="col-6 col-lg">
                                        <label for="Cliente">Cliente</label>
                                        <div class="input-pedido input-group date datepicker" data-date-autoclose="true"
                                            data-date-format="dd-mm-yyyy">
                                            <input class="form-control" type="text">
                                            <button class="input-gp-btn fa-solid fa-magnifying-glass" style="background:var(--gray-background)"></button>    
                                        </div>
                                    </div>
                                    <div class="input-pedido col-6 col-lg input-incluir">
                                        <label for="Faturado por">Faturado por</label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="input-pedido col-6 col-lg input-incluir">
                                        <label for="Venda">Pagamento</label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="input-pedido col-6 col-lg">
                                        <label for="Status">Status</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="container form-group form-observacao">
                                <div class="row">
                                    <div class="mb-3 col-8 col-lg-10">
                                        <label for="obs-dist">Observações do cliente</label>
                                        <input type="text-area" class="input-pedido form-control"></input>
                                    </div>
                                    <div class="mb-3 mb-lg-0 col-4 col-lg-2" style="margin:0 auto">
                                        <label for="Total">Total R$</label>
                                        <input type="text" class="input-pedido col-2 form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center row">
                            <div class="container p-0 col-12 form-group tabela-produtos">
                                <div class="ctn-lista-pedidos box-border">
                                    <div>
                                        <h6 class="titulo-pagina p-2 itens-pedido-tabela">Itens do Pedido</h6>
                                    </div>
                                    <div class="div_scroll">
                                        <div id="listagem_itens_pedidos">
                                            <div>
                                                <tboby></tboby>
                                                <table class="table table-striped tabela-produtos-th">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-12">Produto</th>
                                                            <th class="col tabela-produto px-3">Qtde.</th>
                                                            <th class="col tabela-produto-valor">Valor</th>
                                                            <th class="col tabela-produto-subtotal">SubTotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tabela-produtos-edicao">
                                                        <tr>
                                                            <td>MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                            <td><input class="form-control text-center" type="number"></td>
                                                            <td>29,90</td>
                                                            <td>598,00</td>
                                                        </tr>                                                                                                               
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
<script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="plugins/jquery-3.7.1.min.js"></script>
<script src="script.js"></script>
</body>

</html>