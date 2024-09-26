<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$erro = false;
	$curl = curl_init();

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
			CURLOPT_POSTFIELDS =>'{
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

	$response = curl_exec($curl);
	$err      = curl_error($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	echo $httpcode.'<br>';
	if ($err) {
		echo $err.'<br>';
		$erro = true;
	} else {
		$resposta = json_decode($response);
		if (
					   $httpcode != 200
					&& $httpcode != 201
		) {			
			echo "Erro".'<br>';
			var_dump($resposta);
			$erro = true;
			
		} else {
			echo "Sucesso".'<br>';
            //echo '<pre>'; print_r($resposta->data); echo '</pre>';

			$token = $resposta->data->access_token;
			$tipo  = $resposta->data->token_type;

			$erro = false;
		}

	}

	if($erro) {
		exit;
	}
	
	
	curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://aga.totem.app.br/_custom/api/v1/empresas',
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
					'Content-Type: application/json',
					'x-api-secret: d4cfc2a8e0bbaa1234d89c64e85c59a5db63a8e7d4b1af8a6c4e87a8b4d95a64',
					'x-api-public: BkwAK4oPuGiF7JRQFM97seT92RJ0o5lGGX83rBNHbHDid2cMmAmTOOyeSXGCKidhZjalTBK3PRLaUyIJP26KQxYEngDkJyEYmhnN5CYHkkeOS7BeJYbxUEalspAEx1ec',
					'Authorization: '.$tipo.' '.$token

			),
	));

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    echo $httpcode.'<br>';
    if ($err) {
        echo $err.'<br>';
        $erro = true;
    } else {
        $resposta = json_decode($response);
        if (
            $httpcode != 200
            && $httpcode != 201
        ) {
            echo "Erro".'<br>';
            var_dump($resposta);
            $erro = true;

        } else {
            echo "Sucesso".'<br>';
            //echo '<pre>';            print_r($resposta->data);            echo '</pre>';

			foreach($resposta as $dado) {
				//var_dump($dado);
				echo '<pre>';            print_r($dado);            echo '</pre>';
				//echo '<h1>'.$dado->pe_status.'</h1>';
			}

            $erro = false;
        }

    }
		
	curl_close($curl);