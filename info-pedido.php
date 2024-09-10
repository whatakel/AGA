<?php
include('_config.php');


/***** obtem os dados dos pedidos *****/

$erro           = false;
$mensagem_erro  = '';

curl_setopt_array($curl, array(
    CURLOPT_URL => $urlapi.'/pedidos/' . $_GET['codigo'],
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
        'x-api-secret: '.$xapisecret,
        'x-api-public: '.$xapipublic,
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
// echo '<pre>'; print_r($pedido); echo '</pre>';

curl_close($curl);

?>
<!DOCTYPE html>
<html lang="pt-br">

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
                    <h6 class="px-2 m-0 d-flex">Pedido: <?php echo $pedido->codsite_lj_pedidos; ?></h6>
                    <p class="data-pedido px-2 m-0"><?php echo date('d/m/Y', strtotime($pedido->pe_dthr)); ?> &agrave;s <?php echo date('H:i', strtotime($pedido->pe_dthr)); ?></p>
                </div>
            </div>
            <div
                class="col-auto acoes-pedidos acoes-principais d-none d-lg-flex gap-2 gap-xl-3 align-items-center">
                <a href=""><i class="status-lista status-done fa-solid fa-thumbs-up" style="color:var(--azul)" title="Confirmado"></i></a>
                <span class="m-0 p-0">/</span>
                <a href="" data-bs-toggle="modal" data-bs-target="#data-entrega"><i class="status-lista status-next fa-solid fa-cart-flatbed " title="Prazo de entrega"></i></a>
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

        <!-- Tela confirmação pedido -->

        <div class="ctn-pedido confirmar-pedido m-3">
            <div class="info-cliente row">
                <div class="container form-group">
                    <div class="row my-3">
                        <div class="col-6 col-lg">
                            <label for="Cliente">Cliente</label>
                            <div class="input-pedido input-group date datepicker" data-date-autoclose="true" data-date-format="dd-mm-yyyy">
                                <input class="form-control" type="text" value="<?php echo $pedido->cl_nome; ?>" disabled>
                            </div>
                        </div>
                        <div class="input-pedido col-6 col-lg ">
                            <label for="Faturado por">Faturado por</label>
                            <input type="text" class="form-control" value="<?php echo $pedido->lo_descricao ?>" disabled>
                        </div>
                        <div class="input-pedido col-6 col-lg">
                            <label for="Venda">Pagamento</label>
                            <input type="text" class="form-control" value="<?php echo $pedido->fp_descricao ?>" disabled>
                        </div>
                        <div class="input-pedido col-6 col-lg">
                            <label for="Status">Status</label>
                            <input type="text" class="form-control" value="<?php echo $pedido->pe_status ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="container form-group form-observacao">
                    <div class="row">
                        <div class="mb-3 col-8 col-lg-10">
                            <label for="obs-dist">Observações do cliente</label>
                            <input type="text-area" class="input-pedido form-control" value="<?php echo $pedido->observacao ?>" disabled></input>
                        </div>
                        <div class="mb-3 mb-lg-0 col-4 col-lg-2" style="margin:0 auto">
                            <label for="Total">Total R$</label>
                            <input type="text" class="input-pedido col-2 form-control" value="<?php echo $pedido->pe_valor_total ?>" disabled>
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
                                                <th class="col tabela-produto px-3">UN</th>
                                                <th class="col tabela-produto-valor">Valor</th>
                                                <th class="col tabela-produto-subtotal">SubTotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tabela-produtos-edicao">
                                            <?php
                                            foreach ($pedido->itens as $item) {
                                                echo '
                                                            <tr>
                                                                <td>' . $item->Descricao . '</td>
                                                                <td>' . intval($item->Qtde) . '</td>
                                                                <td>' . $item->Unidade . '</td>
                                                                <td>' . number_format($item->Unitario, 2, ',', '.') . '</td>
                                                                <td>' . number_format($item->Unitario, 2, ',', '.') . '</td>
                                                            </tr>
                                                        ';
                                            }
                                            ?>
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
                        <div class="date datepicker" data-date-autoclose="true"
                            data-date-format="dd-mm-yyyy">
                            <input class="form-control input-sm" type="date" name="data-entrega" id="data-entrega" onclick="showPicker()">
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

    <!-- modal impressão pedido -->

    <div class="modal fade" id="imprime-pedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal-impressao">
                <div class="modal-body">
                    <iframe class="embed-responsive-item" src="impressao-pedido/imprime-copia-pedido.php" height="100%"></iframe>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary bg-danger border-0" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary bg-success border-0">Salvar PDF</button>
                </div>
            </div>
        </div>
    </div>

    <script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="plugins/jquery-3.7.1.min.js" type="text/javascript"></script>
</body>

</html>
<script>

</script>