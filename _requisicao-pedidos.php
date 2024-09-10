    <?php 

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlapi.'/pedidos/DataInicial>=2024-01-01',
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

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($err) {
        echo $err.'<br>';
        $erro = true;
    } else {
        $resposta = json_decode($response);
        if ( $httpcode != 200 && $httpcode != 201) {
            echo "Erro".'<br>';
            var_dump($resposta);
            $erro = true;

        } else {
            $pedidos_abertos = [];

            foreach($resposta->data as $dado) {
                if($dado->pe_status !== "Finalizado" && $dado->pe_status !== "Cancelado"){
                    $pedidos_abertos[] = $dado;
                }
            }

            $erro = false;
        }
    }

    $pedidos = [];
    $pedidos = $pedidos_abertos;
    $qtd_pedidos = count($pedidos);

    // echo '<pre>'; print_r($pedidos); echo '</pre>';

?>