<?php
include('_config.php');
include('_lista-pedidos.php')
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGA - Gestão de Pedidos</title>

    <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plataforma.css">
    <script src="https://kit.fontawesome.com/edb1b9d001.js" crossorigin="anonymous"></script>
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
                        <form action="" method="GET">
                            <div class="form-group">
                                <label for="Status">Status</label>
                                <div class="input-group  input-status">
                                    <select class="form-control">
                                        <option value="" disabled hidden selected=""></option>
                                        <option value="Novo">Novo</option>
                                        <option value="Confirmado">Confirmado</option>
                                        <option value="Preparando">Preparando</option>
                                        <option value="Faturando">Faturando</option>
                                        <option value="Entregando">Entregando</option>
                                        <option value="Entregue">Entregue</option>
                                        <option value="Finalizado">Finalizado</option>
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
                                        <input value="" class="form-control input-sm" placeholder="Início" type="date" onclick="showPicker()">
                                        <button class="input-gp-btn data-filtro">Até</button>
                                        <input value="" class="form-control input-sm" placeholder="Fim" type="date" onclick="showPicker()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="faturado">Faturado em</label>
                                    <div class="input-group">
                                        <input value="" class="form-control input-sm" placeholder="Início" type="date" onclick="showPicker()">
                                        <button class="input-gp-btn data-filtro">Até</button>
                                        <input value="" class="form-control input-sm" placeholder="Fim" type="date" onclick="showPicker()">
                                    </div>
                                </div>
                            </div>
                            <div class="input-mostrar filtro-mostrar form-group" id="dataTable1_length">
                                <label>Mostrar
                                    <select name="data-lenght" aria-controls="dataTable1" class="form-control input-sm">
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
                                <button class="btn btn-sm btn-primary text-light btn-buscar" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    Buscar
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
        <div class="ctn-pedidos column d-flex flex-row gap-2 flex-wrap flex-lg-nowrap m-2 m-lg-0">
            <div class="coluna-pedidos col-12 col-lg-3">
                <div class="fixed-column box-border rounded">
                    <div class="titulo-pagina p-2 d-flex flex-wrap">
                        <div class="d-flex flex-row gap-1">
                            <h5>Pedidos</h5>
                            <p class="m-0 fw-100">(<?php echo $qtd_pedidos?>)</p>
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
                            
                            // echo '<pre>'; print_r($pedidos); echo '</pre>';
                            
                        echo '
                                <div class="box-pedido p-2" data-codigo="'.$pedido->codsite_lj_pedidos.'">
                                    <div class="box-pedido-status">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="'.$pedido->lo_logo.'" width="50px" alt="" class="logo-pedido">
                                            <p class="data-pedido m-0 align-self-center">'.date('d/m/y', strtotime($pedido->pe_dthr)).'</p>
                                        </div>
                                        <!-- icone status faturando-->
                                        <i class="icone-status" title="'.$pedido->pe_status.'"></i>
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
                <div class="background-right">
                    <img src="img/logo.svg" width="50%">
                </div>
                <iframe id="pedido_edicao" style="width:100%; height: calc(100vh - 85px);"></iframe>
            </div>
        </div>
    </div>
    <script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/jquery-3.7.1.min.js"></script>
    <script src="script.js"></script>
</body>
</html>