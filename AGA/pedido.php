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
        CURLOPT_URL => 'https://aga.totem.app.br/_custom/api/v1/pedidos/'.$_GET['codigo'],
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

    $pedido = $resposta->data[0];
   // echo '<pre>'; print_r($pedido); echo '</pre>';

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

<body style="padding: 0; margin: 0;">

        <div class="d-lg-flex flex-column col-12 col-lg-12">
            <div class="detalhes-pedido box-border rounded column col-12">
                <div class="titulo-pagina ctn-detalhes d-flex gap-2 gap-xl-3 p-2 d-flex flex-wrap justify-content-between">
                    <div class="col-auto d-flex flex-row gap-1 ">
                        <div>
                            <h6 class="px-2 m-0 d-flex">#<?php echo $pedido->codsite_lj_pedidos.' / #'.$pedido->codvenda; ?></h6>
                            <p class="data-pedido px-2 m-0"><?php echo date('d/m/Y', strtotime($pedido->pe_dthr)); ?> &agrave;s <?php echo date('H:i', strtotime($pedido->pe_dthr)); ?> </p>
                        </div>
                        <div
                            class="acoes-pedidos col-auto d-none d-lg-flex d-flex gap-2 gap-xl-3 align-items-center">
                            <i class="pedido-action funcao-pedido fad fa-file" title="Protocolo" data-bs-toggle="modal" data-bs-target="#modal-protocolo"></i>
                            <span class="m-0 p-0">/</span>
                            <i class="pedido-action funcao-pedido fad fa-print"
                               title="Imprimir cópia do pedido"></i>
                        </div>
                    </div>
                    <div
                        class="col-auto acoes-pedidos acoes-principais d-none d-lg-flex gap-2 gap-xl-3 align-items-center">
                        <a href=""><i class="status-lista status-done fad fa-thumbs-up"
                                      title="Confirmado"></i></a>
                        <span class="m-0 p-0">/</span>
                        <a href=""><i class="status-lista fad fa-dolly-flatbed"
                                      title="Prazo de entrega"></i></a>
                        <span class="m-0 p-0">/</span>
                        <a href=""><i class="status-lista fad fa-box-full"
                                      title="Imprimir folha de separação"></i></a>
                        <span class="m-0 p-0">/</span>
                        <a href=""><i class="status-lista fad fa-balance-scale"
                                      title="Ajuste de peso"></i></a>
                        <span class="m-0 p-0">/</span>
                        <a href=""><i class="status-lista fad fa-cash-register"
                                      title="Faturar + Enviar"></i></a>
                        <span class="m-0 p-0">/</span>
                        <a href=""><i class="status-lista fad fa-redo" title="Reabrir venda"></i></a>
                        <span class="m-0 p-0">/</span>
                        <a href=""><i class="status-lista fad fa-lock-open"
                                      title="Devolver para o caixa"></i></a>
                    </div>
                    <div class="d-flex d-flex gap-2 gap-xl-3 col-auto m-0 justify-content-between">
                        <div class="acoes-pedidos col-auto d-none d-lg-flex gap-2 gap-xl-3 align-items-center">
                            <a href=""><i class="pedido-action fad fa-pen" title="Editar pedido"></i></a>
                        </div>
                        <!-- Botão para chamar a próxima ação do pedido
                             Atributos apenas para modal de selecionar data de entrega-->
                        <button type="button" data-bs-toggle="modal" data-bs-target="#data-entrega"
                                class="btn btn-sm btn-salvar btn-primary col-auto d-flex align-items-center gap-1">
                            <i class="fad fa-thumbs-up"></i>
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
                                        <button class="input-gp-btn fad fa-magnifying-glass"
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
                                        <i class="fad fa-magnifying-glass"></i>
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
                                                <td class=""><i class="fad fa-plus icone-add"></i></i>
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
                                            <?php
                                                foreach ($pedido->itens as $item) {
                                                    echo '
                                                            <tr>
                                                                <td class="">'.$item->Descricao.'</td>
                                                                <td><input class="form-control text-center" type="number" value="'.intval($item->Qtde).'"></td>
                                                                <td class="d-none d-lg-table-cell"><input class="form-control text-center"></td>
                                                                <td>'.number_format($item->Subtotal, 2, ',', '.').'</td>
                                                                <td><i class="fad fa-trash icone-trash"></i></td>
                                                            </tr>                                                    
                                                    ';
                                                }
                                            ?>
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
                                                            <i class="fad fa-magnifying-glass"></i>
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
                                                                        <td><i class="fad fa-plus icone-add"></i></i>
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
                                        <input class="form-control" type="text" value="<?php echo $pedido->cl_nome; ?>">
                                        <button class="input-gp-btn fad fa-magnifying-glass" style="background:var(--gray-background)"></button>
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
                                    <label for="obs-dist">Observa&ccedil;&otilde;es do cliente</label>
                                    <input type="text-area" class="input-pedido form-control"></input>
                                </div>
                                <div class="mb-3 mb-lg-0 col-4 col-lg-2" style="margin:0 auto">
                                    <label for="Total">Total R$</label>
                                    <input type="text" class="input-pedido col-2 form-control" value="<?php echo number_format($pedido->pe_valor_total, 2, ',', '.'); ?>">
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
                                                <?php
                                                foreach ($pedido->itens as $item) {
                                                    echo '
                                                            <tr>
                                                                <td class="">'.$item->Descricao.'</td>
                                                                <td><input class="form-control text-center" type="number" value="'.intval($item->Qtde).'"></td>
                                                                <td>'.number_format($item->Unitario, 2, ',', '.').'</td>
                                                                <td>'.number_format($item->Subtotal, 2, ',', '.').'</td>
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
                                        <button class="input-gp-btn fad fa-magnifying-glass" style="background:var(--gray-background)"></button>
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
<script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="plugins/jquery-3.7.1.min.js"></script>
<script src="script.js"></script>
</body>

</html>