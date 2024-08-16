<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$curl = curl_init();

$erro = false;
$mensagem_erro = '';

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
                "usuario": "testeapi",
                "senha": "54d12332466253425d"
            }',
    CURLOPT_HTTPHEADER => array(
        'x-api-secret: d4cfc2a8e0bbaa1234d89c64e85c59a5db63a8e7d4b1af8a6c4e87a8b4d95a64',
        'x-api-public: BkwAK4oPuGiF7JRQFM97seT92RJ0o5lGGX83rBNHbHDid2cMmAmTOOyeSXGCKidhZjalTBK3PRLaUyIJP26KQxYEngDkJyEYmhnN5CYHkkeOS7BeJYbxUEalspAEx1ec',
        'x-api-key: CnHSOyfrROIIQZsTzCIbmWBL8KAfl6tgFa0cO0ozO9YWTROBL0dAideAIJWLytdoC4p8LZgUUyQ4B6po4C3g7FCZc5t8xS830ImgmSN6TAeJ4EGgMuD5TASDcIMS04a',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($err) {
    $erro = true;
    $mensagem_erro = 'Erro obtenção do token: ' . $httpcode . ' - ' . $err;
} else {
    if ($httpcode != 200 && $httpcode != 201) {
        $erro = true;
        $mensagem_erro = 'Erro obtenção da token HTTP: ' . $httpcode;
    }
}

if ($erro) {
    echo $mensagem_erro;
    exit;
}

$resposta = json_decode($response);

// echo '<pre>'; print_r($resposta); echo '</pre>';

$token = $resposta->data->access_token;
$token_tipo = $resposta->data->token_type;

$erro = false;
$mensagem_erro = '';

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://aga.totem.app.br/_custom/api/v1/pedidos/DataInicial>=2024-01-01',
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
        'Authorization: ' . $token_tipo . ' ' . $token,
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($err) {
    $erro = true;
    $mensagem_erro = 'Erro obtenção dos pedidos: ' . $httpcode . ' - ' . $err;
} else {
    if ($httpcode != 200 && $httpcode != 201) {
        $erro = true;
        $mensagem_erro = 'Erro obtenção dos pedidos HTTP: ' . $httpcode;
    }
}

if ($erro) {
    echo $mensagem_erro;
    exit;
}

$resposta = json_decode($response);

$pedidos = $resposta->data;

// echo '<pre>'; print_r($pedidos); echo '</pre>';
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
                            <i class="fa-solid fa-rotate"></i>
                            Limpar
                        </button>
                        <button class="btn btn-sm btn-primary text-light btn-buscar">
                            <i class="fa-solid fa-magnifying-glass"></i>
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
                            <i class="icone-filtro fa-solid fa-filter" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filtroExpand" aria-expanded="false" aria-controls="filtroExpand"></i>
                            <button class="btn btn-sm  btn-success">+ Incluir</button>
                        </div>
                    </div>
                    <div class="lista-pedidos">
                        <!-- box com classe para quando pedido estiver ativo -->
                        <?php

                        $contador = 0;
                        foreach ($pedidos as $pedido) {
                            $contador++;

                        echo '
                                <div class="box-pedido p-2" data-codigo="'.$pedido->codsite_lj_pedidos.'">
                                    <div class="box-pedido-status">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="'.$pedido->lo_logo.'" width="50px" alt="" class="logo-pedido">
                                            <p class="data-pedido m-0 align-self-center">'.date('d/m/y', strtotime($pedido->pe_dthr)).'</p>
                                        </div>
                                        <!-- icone status faturando-->
                                        <i class="icone-status fa-solid fa-truck" title="'.$pedido->pe_status.'"></i>
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
                                <a href=""><i class="fa-solid fa-solid fa-pen" title="Editar pedido"></i>
                                    Editar Pedido
                                </a>
                            </div>
                            <div class="acoes-pedido-modal acao-modal-done">
                                <a href=""><i class="fa-solid fa-thumbs-up" title="Confirmado"></i>
                                    Confirmar
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fa-solid fa-cart-flatbed" title="Prazo de entrega"></i>
                                    Prazo de entrega
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fa-solid fa-boxes-packing" title="Folha de separação"></i>
                                    Folha de separação
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fa-solid fa-scale-balanced" title="Ajuste de peso"></i>
                                    Ajuste de peso
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fa-solid fa-cash-register" title="Faturar + Enviar"></i>
                                    Faturar / Enviar
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fa-solid fa-rotate-left" title="Reabrir venda"></i>
                                    Reabrir venda
                                </a>
                            </div>
                            <div class="acoes-pedido-modal">
                                <a href=""><i class="fa-solid fa-lock-open" title="Devolver para o caixa"></i>
                                    Devolver para o caixa
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>

            <div class="ctn-gestao-right d-lg-flex flex-column col-12 col-lg-9">
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

                    <!-- Modal protocolo -->

                    <div class="modal fade" id="modal-protocolo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Protocolo Pedido #000000</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="ctn-lista-pedidos box-border">
                                        <p class="titulo-pagina">Time Line</p>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-novo-protocolo">+ Incluir</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal novo protocolo -->
                    <div class="modal fade" id="modal-novo-protocolo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Protocolo Pedido #000000</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Novo Protocolo</h6>
                                    <textarea name="novo-protocolo" id="" class="form-control text-area-protocolo col-12"></textarea>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    <button type="button" class="btn btn-success">+ Incluir</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal prazo de entrega -->
                    <div class="modal fade " id="data-entrega" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-prazo">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Previsão de entrega</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="Prazo-entrega" class="fw-bold">Selecione a data de entrega</label>
                                        <div class="input-group date datepicker" data-date-autoclose="true"
                                            data-date-format="dd-mm-yyyy">
                                            <input value="" class="form-control input-sm" inputmode="numeric" type="number">
                                            <button class="input-gp-btn fas fa-calendar-alt"></button> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tela adicionar/editar pedido -->

                    <!-- remover classe d-none -->
                    <div class="d-none ctn-pedido adicionar-pedido m-3">
                        <div class="info-cliente row">
                            <div class="container form-group">
                                <div class="row my-3">
                                    <div class="input-pedido col-6 col-lg">
                                        <label for="Cliente">Cliente</label>
                                        <div class="input-group date datepicker" data-date-autoclose="true"
                                            data-date-format="dd-mm-yyyy">
                                            <input class="form-control" type="text">
                                            <button class="input-gp-btn fa-solid fa-magnifying-glass"
                                                style="background:var(--gray-background)"></button>
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
                                        <input type="text-area" class="form-control"></input>
                                    </div>
                                    <div class="mb-3 mb-lg-0 col-4 col-lg-2" style="margin:0 auto">
                                        <label for="Total">Total R$</label>
                                        <input type="text" class="col-2 form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center row p-2">
                            <div class="column p-0 col-12 form-group tabela-produtos d-flex flex-column flex-lg-row gap-2">
                                <div class="ctn-lista-add col-12 col-lg box-border d-none d-lg-block">
                                    <div>
                                        <h6 class="titulo-pagina p-2 itens-pedido-tabela">Lista de produtos</h6>
                                    </div>
                                    <div class="busca-produto d-flex flex-column p-2">
                                        <label>
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            Digite o que deseja buscar ou em branco para todos
                                        </label>
                                        <input class="form-control"
                                            label="Digite o que deseja buscar ou em branco para todos">
                                    </div>
                                    <div class="tabela-esquerda">
                                        <div>
                                            <table class="table table-striped tabela-produtos-th">
                                                <thead>
                                                    <tr>
                                                        <th class="col-12">Produto</th>
                                                        <th class="col-auto d-none d-lg-table-cell">Tabela</th>
                                                        <th class="col-auto d-none d-lg-table-cell">Un.</th>
                                                        <th class="col-auto d-none d-lg-table-cell">Preço</th>
                                                        <th class="col-auto d-none d-lg-table-cell">Qtde.</th>
                                                        <th class="col-auto"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tabela-produtos-edicao">
                                                    <tr>
                                                        <td>MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                        <td class="d-none d-lg-table-cell">21,20</td>
                                                        <td class="d-none d-lg-table-cell">PCT</td>
                                                        <td class="d-none d-lg-table-cell"><input class="form-control text-center"></td>
                                                        <td class="d-none d-lg-table-cell"><input class="form-control text-center"></td>
                                                        <td class=""><i class="fa-solid fa-plus icone-add"></i></i>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="ctn-lista-produtos col-12 col-lg col-auto box-border">
                                    <div>
                                        <div
                                            class="add-produto-barra titulo-pagina p-2 itens-pedido-tabela d-flex justify-content-between">
                                            <h6 class="m-0">Itens do Pedido</h6>
                                            <button class="btn btn-sm btn-success d-lg-none" type="button"
                                                data-bs-toggle="modal" data-bs-target="#modal-add-produto">
                                                + Produto</button>
                                        </div>
                                    </div>
                                    <div class="tabela-direita">
                                        <div>
                                            <table class="table table-striped tabela-produtos-th">
                                                <thead>
                                                    <tr>
                                                        <th class="col-12">Produto</th>
                                                        <th class="col tabela-produto px-3 px-lg-2">Qtde.</th>
                                                        <th class="col tabela-produto-valor d-none d-lg-table-cell">Preço</th>
                                                        <th class="col tabela-produto-subtotal">R$</th>
                                                        <th class="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tabela-produtos-edicao">
                                                    <tr>
                                                        <td class="">MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                        <td><input class="form-control text-center" type="tel"></td>
                                                        <td class="d-none d-lg-table-cell"><input class="form-control text-center"></td>
                                                        <td>598,00</td>
                                                        <td><i class="fa-solid fa-trash icone-trash"></i></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Modal adicionar produto -->

                                    <div class="modal fade" id="modal-add-produto" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-fullscreen">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Adicionar produto</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="lista-modal box-border">
                                                        <div class="busca-produto d-flex flex-column p-2">
                                                            <label>
                                                                <i class="fa-solid fa-magnifying-glass"></i>
                                                                Procurar produto
                                                            </label>
                                                            <input class="form-control"
                                                                label="Digite o que deseja buscar ou em branco para todos">
                                                        </div>
                                                        <div class="div_scroll">
                                                            <div id="listagem_itens_pedidos">
                                                                <div>
                                                                    <table
                                                                        class="table table-striped tabela-produtos-th">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-12">Produto</th>
                                                                                <th class="col-auto"></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="">
                                                                            <tr data-bs-toggle="modal" data-bs-target="#btn-add-produto">
                                                                                <td>MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                                                <td><i class="fa-solid fa-plus icone-add"></i></i>
                                                                                </td>
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal adicionar-produto -->
                                <div class="modal fade" data-bs-backdrop="static" id="btn-add-produto" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Nome do Produto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body input-add m-auto">
                                                <div class="form-group">
                                                    <label class="fw-bold" for="quantidade">Quantidade</label>
                                                    <input class="form-control input-sm" type="number" inputmode="numeric">
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-add-produto">Voltar</button>
                                                <button type="button" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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


                    <!-- Tela faturando/Reabrir venda/ Devolver para o caixa -->

                    <!-- 
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
                                                            <th class="col tabela-produto">Quantidade</th>
                                                            <th class="col tabela-produto-valor">Valor</th>
                                                            <th class="col tabela-produto-subtotal">SubTotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tabela-produtos-edicao">
                                                        <tr>
                                                            <td class="">MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                            <td>20,00</td>
                                                            <td>29,90</td>
                                                            <td>598,00</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                            <td>20,00</td>
                                                            <td>29,90</td>
                                                            <td>598,00</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                            <td>20,00</td>
                                                            <td>29,90</td>
                                                            <td>598,00</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">MUSSARELA PEÇA MISTURA - SAPUTO</td>
                                                            <td>20,00</td>
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
                    </div> -->


                </div>
            </div>


        </div>
    </div>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/script.js"></script>
</body>

</html>