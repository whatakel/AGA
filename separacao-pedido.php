<?php
include('_config.php');


/***** obtem os dados dos pedidos *****/

$erro           = false;
$mensagem_erro  = '';

curl_setopt_array($curl, array(
    CURLOPT_URL => $urlapi . '/pedidos/' . $_GET['codigo'],
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
        'x-api-secret: ' . $xapisecret,
        'x-api-public: ' . $xapipublic,
        'Authorization: ' . $token_tipo . ' ' . $token,
        'Content-Type: application/json'
    ),
));

$response   = curl_exec($curl);
$err        = curl_error($curl);
$httpcode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($err) {
    $erro = true;
    $mensagem_erro = 'Erro obtenção dos pedidos: ' . $httpcode . ' - ' . $err;
} else {
    if (
        $httpcode != 200
        && $httpcode != 201
    ) {
        $erro = true;
        $mensagem_erro = 'Erro obtenção dos pedidos HTTP: ' . $httpcode;
    }
}

if ($erro) {
    echo $mensagem_erro;
    exit;
}

$resposta = json_decode($response);


$pedido = $resposta->data[0];
// echo '<pre>'; print_r($resposta); echo '</pre>';
// echo $pedido;

curl_close($curl);

?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plataforma.css">
    <link rel="stylesheet" href="plugins/fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free-6.6.0-web/css/fontawesome.min.css">
</head>

<body style="padding: 0; margin: 0;">
    <div class="detalhes-pedido box-border rounded column col-12">
        <div class="titulo-pagina ctn-detalhes d-flex gap-2 gap-xl-3 p-2 d-flex flex-wrap justify-content-between">
            <div class="col-auto d-flex flex-row gap-1 ">
                <div>
                    <h6 class="px-2 m-0 d-flex">Pedido: <span class="string-pedido"><?php echo $pedido->codsite_lj_pedidos; ?></span></h6>
                    <p class="data-pedido px-2 m-0"><?php echo date('d/m/Y', strtotime($pedido->pe_dthr)); ?> &agrave;s <?php echo date('H:i', strtotime($pedido->pe_dthr)); ?></p>
                </div>
            </div>
            <div
                class="col-auto acoes-pedidos acoes-principais d-none d-lg-flex gap-2 gap-xl-3 align-items-center">
                <a href=""><i class="status-lista status-done fa-solid fa-thumbs-up" style="color:var(--azul)" title="Confirmado"></i></a>
                <span class="m-0 p-0">/</span>
                <a href="" data-bs-toggle="modal" data-bs-target="#data-entrega"><i class="status-lista status-next fa-solid fa-cart-flatbed" title="Prazo de entrega"></i></a>
                <span class="m-0 p-0">/</span>
                <a href=""><i class="status-lista fa-solid fa-boxes-packing" title="Imprimir folha de separação"></i></a>
                <span class="m-0 p-0">/</span>
                <a href=""><i class="status-lista fa-solid fa-scale-balanced" title="Ajuste de peso"></i></a>
                <span class="m-0 p-0">/</span>
                <a href=""><i class="status-lista fa-solid fa-cash-register" title="Faturar + Enviar"></i></a>
                <span class="m-0 p-0">/</span>
                <a href=""><i class="status-lista fa-solid fa-rotate-left" title="Reabrir venda"></i></a>
                <span class="m-0 p-0">/</span>
                <a href=""><i class="status-lista fa-solid fa-lock-open" title="Devolver para o caixa"></i></a>
            </div>
            <div class="d-flex d-flex gap-2 gap-xl-3 col-auto m-0 justify-content-between">
                <div class="acoes-pedidos col-auto d-none d-lg-flex gap-2 gap-xl-3 align-items-center">
                    <i class="pedido-action funcao-pedido fa-solid fa-sheet-plastic" title="Protocolo" data-bs-toggle="modal" data-bs-target="#modal-protocolo"></i>
                    <span class="m-0 p-0">/</span>
                    <i class="pedido-action funcao-pedido fa-solid fa-print" title="Imprimir cópia do pedido" data-bs-toggle="modal" data-bs-target="#imprime-pedido"></i>
                    <span class="m-0 p-0">/</span>
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

        <div class="ctn-pedido adicionar-pedido m-3">
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
                    

        <script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-3.7.1.min.js" type="text/javascript"></script>
    </body>
</html>

<!-- <script>
    var urlImprime = 2425
    $(document).ready(function() {
        $('.iframe-impressao').attr('src', 'impressao-pedido/imprime-separacao.php?codigo=' + (urlImprime) + '#toolbar=0');
        setTimeout(() => {
            $('.iframe-impressao')[0].contentWindow.print();
            $('.iframe-impressao')[0].contentWindow.focus();
        }, 1000);
    });
</script> -->