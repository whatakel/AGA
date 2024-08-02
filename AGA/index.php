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
        $mensagem_erro = 'Erro obtenção do token: '.$httpcode . ' - ' . $err;
    } else {
        if (
               $httpcode != 200
            && $httpcode != 201
        ) {
            $erro = true;
            $mensagem_erro = 'Erro obtenção da token HTTP: ' . $httpcode;
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
        CURLOPT_URL => 'https://aga.totem.app.br/_custom/api/v1/pedidos/DataInicial>=2024-07-01',
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

    $pedidos = $resposta->data;
    //echo '<pre>'; print_r($pedidos); echo '</pre>';

    curl_close($curl);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protótipo tela Gestão de Pedidos</title>

	<link rel="stylesheet" href="plugins/fontawesome/css/all.min.css" type="text/css"/>
	<link rel="stylesheet" href="plugins/fontawesome/css/font-awesome-animation.min.css" type="text/css" />
    <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plataforma.css">
</head>

<body class="">
    <nav class="header">

    </nav>
    <div class="barra-lateral">

    </div>

    <!-- Container principal -->
    <div class="main-container p-md-2 p-lg-3">
        <div class="gestao-pedidos mb-2 box-border rounded col collapse" id="filtroExpand">
            <div class="gestao-filtros p-2">
                <div class="row row-gestao-filtros d-flex justify-content-between flex-column flex-lg-row align-content-bottom align-items-center">
                    <div class="col-md">
                        <div class="form-group">
                            <label for="Status">Status</label>
                            <div class="input-group  input-status">
                                <select class="form-control">
                                    <option value="" disabled hidden selected=""></option>
                                    <option value="Confirmado">Confirmado</option>
                                    <option value="Preparando">Preparando</option>
                                    <option value="Faturando">Faturando</option>
                                    <option value="Entregando">Entregando</option>
                                    <option value="Entregue">Entregue</option>
                                    <option value="Todos">Todos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="Faturado por">Faturado por</label>
                            <div class="input-group  input-status">
                                <select class="form-control">
                                    <option value="" disabled hidden selected=""></option>
                                    <option value="Confirmado">American</option>
                                    <option value="Preparando">Bella Vista</option>
                                    <option value="Preparando">Frigonovak</option>
                                    <option value="Preparando">Todos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group" style="margin-bottom: 4px !important;">
                            <label for="Proposta">Proposta</label>
                            <div class="input-group">
                                <input class="form-control input-sm" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="Cliente">Cliente</label>
                            <div class="input-group">
                                <input value="" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="incluido">Includo em</label>
                            <div class="input-group date datepicker" data-date-autoclose="true"
                                data-date-format="dd-mm-yyyy">
                                <input value="" class="form-control input-sm" placeholder="Início">
                                <button class="input-gp-btn fas fa-calendar-alt"></button></span>
                                <input value="" class="form-control input-sm" placeholder="Fim">
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="faturado">Faturado em</label>
                            <div class="input-group">
                                <input value="" class="form-control input-sm" placeholder="Início">
                                <button class="input-gp-btn fas fa-calendar-alt"></button> </span>
                                <input value="" class="form-control input-sm" placeholder="Fim">
                            </div>
                        </div>
                    </div>
                    <div class="input-mostrar filtro-mostrar form-group" id="dataTable1_length">
                        <label>Mostrar
                            <select name="dataTable1_length" aria-controls="dataTable1" class="form-control input-sm">
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="-1">Todos</option>
                            </select>
                    </div>
                    <div class="acoes-filtros d-flex flex-row gap-2 form-group align-items-center ">
                        <button class="btn btn-sm btn-limpar">
                            <i class="fad fa-rotate"></i>
                            Limpar
                        </button>
                        <button class="btn btn-sm btn-primary text-light btn-buscar">
                            <i class="fad fa-magnifying-glass"></i>
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="ctn-pedidos column d-flex flex-row gap-2 flex-wrap flex-lg-nowrap m-2 m-lg-0">
            <div class="coluna-pedidos col-12 col-lg-3">
                <div class="fixed-column box-border rounded">
                    <div class="titulo-pagina p-2 d-flex flex-wrap">
                        <div class="d-flex flex-row gap-1">
                            <h5>Pedidos</h5>
                            <p class="m-0 fw-100">(10)</p>
                        </div>
                        <div class="column d-flex align-items-center gap-4 gap-lg-3 ">
                            <i class="icone-filtro fad fa-filter" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filtroExpand" aria-expanded="false" aria-controls="filtroExpand"></i>
                            <button class="btn btn-sm  btn-success">+ Incluir</button>
                        </div>
                    </div>
                    <div class="lista-pedidos">

                        <?php
                            $contador = 0;
                            foreach ($pedidos as $pedido) {
                                $contador++;

                                if($contador == 1) {
                                    $primeiro_pedido=$pedido->codsite_lj_pedidos;
                                }

                                echo '
                                        <div class="box-pedido p-2 '.($contador==1 ? 'pedido-active' : '').'" data-codigo="'.$pedido->codsite_lj_pedidos.'">
                                            <div class="box-pedido-status">
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="'.$pedido->lo_logo.'" width="50px" alt="" class="logo-pedido">
                                                    <p class="data-pedido m-0 align-self-center">'.date('d/m/Y', strtotime($pedido->pe_dthr)).'</p>
                                                </div>
                                                <!-- icone status separando -->
                                                '.$pedido->fase.'
                                            </div>
                                            <div class="pedido-numero-data d-flex flex-row align-items-center gap-2 my-1">
                                                <p class="loja-pedido-valor m-0 col-auto align-items-end">R$ '.number_format($pedido->pe_valor_total, 2, ',', '.').'</p>
                                                <p class="numero-pedido m-0">#'.$pedido->codsite_lj_pedidos.'</p>
                                            </div>
                                            <p class="loja-pedido m-0 col-auto align-self-center">'.$pedido->cl_nome.'</p>
                                        </div>                                
                                ';

                            }

                        ?>


                    </div>
                </div>
            </div>

            <!-- Modal funções pedido -->
            <div class="modal fade" id="funcoes-pedido" tabindex="-1" aria-labelledby="funcoes-pedidoLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pedido número: #000000</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <div class="acoes-pedido-modal acao-modal">
                                <a href=""><i class="fad fad fa-pen" title="Editar pedido"></i>
                                    Editar Pedido
                                </a>
                            </div>
                            <div class="acoes-pedido-modal acao-modal-done">
                                <a href=""><i class="fad fa-thumbs-up" title="Confirmado"></i>
                                    Confirmar
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fad fa-cart-flatbed" title="Prazo de entrega"></i>
                                    Prazo de entrega
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fad fa-boxes-packing" title="Folha de separação"></i>
                                    Folha de separação
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fad fa-scale-balanced" title="Ajuste de peso"></i>
                                    Ajuste de peso
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fad fa-cash-register" title="Faturar + Enviar"></i>
                                    Faturar / Enviar
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fad fa-rotate-left" title="Reabrir venda"></i>
                                    Reabrir venda
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fad fa-lock-open" title="Devolver para o caixa"></i>
                                    Devolver para o caixa
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>

            <div class="ctn-gestao-right d-lg-flex flex-column col-12 col-lg-9" style="border: none;">
                <iframe id="pedido_edicao" src="pedido.php?codigo=<?php echo $primeiro_pedido; ?>" style="width:100%; height: calc(100vh - 85px); border:none;"></iframe>
            </div>


        </div>
    </div>
    <script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/jquery-3.7.1.min.js"></script>
    <script src="script.js"></script>
</body>

</html>
<script>
        $('.box-pedido').on('click',function(){
           console.log($(this).attr('data-codigo'));
           $('#pedido_edicao').attr('src','pedido.php?codigo='+$(this).attr('data-codigo') );
        });
</script>