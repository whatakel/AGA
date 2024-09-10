<?php
    include('_config.php');
    include('_requisicao-pedidos.php');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ERROR);

    @session_start();


    $html = file_get_contents('monta-pedido.html');
    
    
    $html = str_replace('[codigo]','12345',$html);
    $html = str_replace('[nome]','fulano',$html);
    $html = str_replace('[valor]','312,26',$html);

    $style = '
        .tamanho_1 { 
            font-size: 40 !important; 
        }
    ';
    


    include("../plugins/mpdf60/mpdf.php");
	$mpdf=new mPDF('utf-8','A4',0,'',5,5,5,5,9,9,'P');
	$mpdf->SetTitle('Documento de Exemplo');
	$mpdf->setAutoTopMargin = 'stretch'; 
	$mpdf->setAutoBottomMargin = 'stretch';

    $mpdf->WriteHTML($style,1);

	$mpdf->WriteHTML($html,2);
    
	$mpdf->Output('Documento_teste.pdf', 'I');
?>
