<?php
include('../_config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

@session_start();

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

// echo '<pre>'; print_r($pedido->codsite_lj_pedidos); echo '</pre>';


function formatarMoedaBR($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

$html = file_get_contents('monta-pedido.html');

//info cliente

$html = str_replace('[Texto||emp_fantasia]', $pedido->cl_nome , $html);
$html = str_replace('[var_global_url]', $pedido->lo_logo, $html);
$html = str_replace('[Texto||codvenda]', $pedido->codsite_lj_pedidos, $html);
$html = str_replace('[Data||dtpedido]', $pedido->pe_dthr, $html);
$html = str_replace('[Texto||cl_nome]', $pedido->cl_nome, $html);
$html = str_replace('[Texto||cl_fantasia]', $pedido->fb_nome, $html);
$html = str_replace('[Texto||cl_cnpj]', $pedido->cl_cpf, $html);
$html = str_replace('[Texto||cl_logradouro]', $pedido->cl_end_logradouro, $html);
$html = str_replace('[Texto||cl_numero]', $pedido->cl_end_numero, $html);
$html = str_replace('[Texto||cl_complemento]', $pedido->cl_end_complemento, $html);
$html = str_replace('[Texto||cl_bairro]', $pedido->cl_end_bairro, $html);
$html = str_replace('[Texto||cl_cidade]', $pedido->cl_end_cidade, $html);
$html = str_replace('[Texto||cl_estado]', $pedido->cl_end_uf, $html);
$html = str_replace('[Texto||cl_cep]', $pedido->cl_end_cep, $html);
$html = str_replace('[Texto||cl_email]', $pedido->cl_email, $html);
$html = str_replace('[Texto||cl_fone1]', $pedido->cl_telefone, $html);
$html = str_replace('[Texto||cl_celular]', $pedido->cl_celular, $html);
$html = str_replace('[Texto||contato]', $pedido->cl_contato, $html);


//info produto

$html = str_replace('[Texto||pr_codigo]', $pedido->itens[1]->ID_Produto, $html);
$html = str_replace('[Texto||pr_descricao]', $pedido->itens[1]->Descricao, $html);
$html = str_replace('[Moeda||valortotal]', formatarMoedaBR($pedido->pe_valor_total), $html);
$html = str_replace('[Moeda||valorfrete]', $pedido->cl_end_valor_frete, $html);
$html = str_replace('[Moeda||valortotalnota]', $pedido->pe_valor_total, $html);
$html = str_replace('[Data||dtprevi]', $pedido->pe_previsao_dt, $html);
$html = str_replace('[Texto||hrprevi]', $pedido->pe_previsao_hr, $html);
$html = str_replace('[Texto||dadosadicionais]', $pedido->observacao, $html);
$html = str_replace('[Data||dtimpresso]', date('d-m-y h:i:s'), $html);
$html = str_replace('[Texto||emp_nome]', $pedido->lo_descricao, $html);

$lista_itens = '';

forEach($pedido->itens as $item){
    $lista_itens.=
                    '
                    <tr>
                    <td style="width: 75px;">'.$item->ID_Produto.'</td>
                    <td>'.$item->Descricao.'</td>
                    <td style="text-align: right;">'.$item->Unitario.'</td>
                    <td style="text-align: right;">'.$item->Qtde.' '.$item->Unidade.'</td>
                    <td style="text-align: right;">'.$item->Embalagem.'</td>
                    <td style="text-align: right;">'.$item->Subtotal.'</td>
                    </tr>
                    '
    ;
}

//info empresa

$html = str_replace('[Texto||emp_cnpj]', $pedido->Emp_CNPJ, $html);
$html = str_replace('[Texto||emp_logradouro]', $pedido->Emp_Logradouro, $html);
$html = str_replace('[Texto||emp_numero]', $pedido->Emp_Numero, $html);
$html = str_replace('[Texto||emp_complemento]', $pedido->Emp_Complemento, $html);
$html = str_replace('[Texto||emp_bairro]', $pedido->Emp_Bairro, $html);
$html = str_replace('[Texto||emp_cidade]', $pedido->Emp_Cidade, $html);
$html = str_replace('[Texto||emp_estado]', $pedido->Emp_Estado, $html);
$html = str_replace('[Texto||emp_cep]', $pedido->Emp_Cep, $html);
$html = str_replace('[Texto||emp_email]', $pedido->Emp_emails, $html);
$html = str_replace('[Texto||emp_fone1]', $pedido->Emp_Telefone, $html);


$html = str_replace('[HTML||lista_itens]', $lista_itens, $html);


$style = '
        .folha-a4{
            display:flex !important;
            height:3508px;
        }
        .table-order{
            height:90%;
        }
        .rodape-empresa{
            height:10%;
            font-size:12px;
            text-align:center;
        }
    ';



include("../plugins/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8v', 'A4', 0, '', 5, 5, 5, 5, 9, 9, 'P');
$mpdf->SetTitle('AGA - Cópia do pedido - ' . $pedido->codsite_lj_pedidos);
$mpdf->SetDisplayMode('fullpage');
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';

$mpdf->WriteHTML($style, 1);

$mpdf->WriteHTML($html, 2);

$mpdf->Output('AGA - Cópia do pedido - ' . $pedido->codsite_lj_pedidos . '.pdf', 'I');


?>

