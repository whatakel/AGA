<?php
//aqui são as configurações da api
$urlapi     = 'https://aga.totem.app.br/_custom/api/v1/';
$usuario    = 'testeapi';
$senha      = '54d12332466253425d';
$xapisecret = 'd4cfc2a8e0bbaa1234d89c64e85c59a5db63a8e7d4b1af8a6c4e87a8b4d95a64';
$xapipublic = 'BkwAK4oPuGiF7JRQFM97seT92RJ0o5lGGX83rBNHbHDid2cMmAmTOOyeSXGCKidhZjalTBK3PRLaUyIJP26KQxYEngDkJyEYmhnN5CYHkkeOS7BeJYbxUEalspAEx1ec';
$xapikey    = 'CnHSOyfrROIIQZsTzCIbmWBL8KAfl6tgFa0cO0ozO9YWTROBL0dAideAIJWLytdoC4p8LZgUUyQ4B6po4C3g7FCZc5t8xS830ImgmSN6TAeJ4EGgMuD5TASDcIMS04a';
//fim das configurações da api


// requisição token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// if (!isset($_GET['codigo']) || $_GET['codigo'] <= 0) {
//     exit;
// }

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
                "usuario": "' . $usuario . '"
                ,"senha": "' . $senha . '"
            }',
    CURLOPT_HTTPHEADER => array(
        'x-api-secret: ' . $xapisecret,
        'x-api-public: ' . $xapipublic,
        'x-api-key: ' . $xapikey,
        'Content-Type: application/json'
    ),
));

$response   = curl_exec($curl);
$err        = curl_error($curl);
$httpcode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($err) {
    $erro = true;
    $mensagem_erro = 'Erro obtenção do token: ' . $httpcode . ' - ' . $err;
} else {
    if (
        $httpcode != 200
        && $httpcode != 201
    ) {
        $erro = true;
        $mensagem_erro = 'Erro obtenção da token HTTP: ' . $httpcode;
    }
}

if ($erro) {
    echo $mensagem_erro;
    exit;
}

$resposta = json_decode($response);

//echo '<pre>'; print_r($resposta); echo '</pre>';

$token        = $resposta->data->access_token;
$token_tipo   = $resposta->data->token_type;

?>