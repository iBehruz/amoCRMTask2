<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Redirecting...</title>

    </head>
    <body >
    <?php
    $code = $_GET["code"] ?? null;
        if(is_null( $code)) {
            echo "redirect";
        }else{
          try{
            $url = 'https://bilhomov.amocrm.ru/oauth2/access_token';

            $client = new GuzzleHttp\Client([
                'verify' => false,
            ]);

            // Create a POST request
            $response = $client->request(
                'POST',
                $url,
                [
                    'form_params' => [
                        "client_id"=>"f31a47ca-c957-4451-be75-eda7cb121971",
                        "client_secret"=> "idTHr77PpRks4jdNxfDttC5Vt3OLkZbv9sLBXg1LZqy1Zlp8JulDDlIOyv3Aug48",
                        "grant_type"=> "authorization_code",
                        "code"=> $code,
                        "redirect_uri"=> "http://localhost:8000/auth"
                    ]
                ]
            );

        // Parse the response object, e.g. read the headers, body, etc.
            $headers = $response->getHeaders();
            $body = $response->getBody()->getContents();

        // Output headers and body for debugging purposes

            echo "<script> window.data = ".json_encode($body).";
            localStorage.setItem('auth_data', window.data);
            document.location.href = 'http://localhost:8000/form';</script>";

          } catch (Exception $e) {
        // An exception was raised but there is an HTTP response body
        // with the exception (in case of 404 and similar errors)
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            echo $responseBodyAsString;
         }
        }

     ?>
    </body>
</html>
