<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/contact', function (Request $request) {

    $ticketNumber = 2023;

    $token = $request->headers->get("access_token");
    $data = $request->all();

    try {
        //валидация данных
        $rules = [
            'name' => 'string', //Must be a number and length of value is 8
            'age' => 'numeric',
            'email' => 'email',
            'phone' => 'numeric',
            'position' => 'string',
            'gender' => 'digits:7'
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response(["success"=>false, "data"=>$validator->errors()->all(), "message"=>"Error ."],400);
        }

        // проверка на дубль
        $isDubl = false;
        $url = 'https://bilhomov.amocrm.ru/api/v4/contacts';

        $client = new GuzzleHttp\Client([
            'verify' => false,
        ]);
        $response = $client->get(
            $url,
            [
                'headers' => ['Authorization' => "Bearer ".$token, "Content-Type"=> "application/json"]
            ]);

        $body = json_decode($response->getBody()->getContents());

        if($body != null){
            $contacts = $body->_embedded->contacts;
            foreach ($contacts as $contact){
                foreach ($contact->custom_fields_values as $field){
                    if($field->field_code == "PHONE"){
                        foreach ($field->values as $value){
                           $isDubl = $data["phone"] == $value->value;
                        }
                    }

                }
            }
        }
        //если есть дубль вернет ошибку
        if($isDubl){
           return response(["success"=>false, "data"=>null, "message"=>"Error duble contact."],400);
        }

        // создание контакта и сделки
        $bodyReuqestLeadContact = array(
            [
                "name"=>"Новая сделка #".$ticketNumber,
                "_embedded"=> [
                    "contacts"=> array(
                        [
                            "name"=>$data["name"],
                            "custom_fields_values"=> array(
                                [
                                    "field_code"=> "POSITION",
                                    "values"=> array(
                                        [
                                            "value"=> $data["position"]
                                        ]
                                    )
                                ],
                                [
                                    "field_code"=> "PHONE",
                                    "values"=> array(
                                        [
                                            "value"=> $data["phone"]
                                        ]
                                    )
                                ],
                                [
                                    "field_code"=> "EMAIL",
                                    "values"=> array(
                                        [
                                            "value"=> $data["email"]
                                        ]
                                    )
                                ],

                                [
                                    "field_id"=>  862119,
                                    "values"=> array(
                                        [
                                            "value"=> (int) $data["age"]
                                        ]
                                    )
                                ],
                                [
                                    "field_id"=> 862339,
                                    "values"=> array(
                                        [
                                            "enum_id"=> (int) $data["gender"]
                                        ]
                                    )
                                ]
                            )
                        ]
                    )
                ]
            ]
        );
        $url = 'https://bilhomov.amocrm.ru/api/v4/leads/complex';

        $client = new GuzzleHttp\Client([
            'verify' => false,

        ]);

        $response = $client->post(
            $url,
            [
                'headers' => ['Authorization' => "Bearer ".$token, "Content-Type"=> "application/json"],
                'body' => json_encode($bodyReuqestLeadContact)
            ]
        );


        $body = $response->getBody()->getContents();
        $taskTime = 4*24*60*60;
        // проверка на рабочее время
        if(date('w') > 1) $taskTime += 2*24*60*60;
        if((int) date('G') < 9 )
        {
            $diff = 9 - (int) date('G');
            $taskTime += ($diff+2)*60*60;
        }
        if((int) date('G') > 18 ) $taskTime -= 6*60*60;


        // создание задачи
        $bodyReuqestTask = array(
            [
                "text"=>"Новая Задача #".$ticketNumber,
                "complete_till"=> time()+$taskTime,
                "entity_id"=> json_decode($body)[0]->id,
                "entity_type"=> "leads"
            ]
        );

        $url2 = 'https://bilhomov.amocrm.ru/api/v4/tasks';

        $client2 = new GuzzleHttp\Client([
            'verify' => false,
        ]);

        $response2 = $client2->post(
            $url2,
            [
                'headers' => ['Authorization' => "Bearer ".$token, "Content-Type"=> "application/json"],
                'body' => json_encode($bodyReuqestTask)
            ]
        );

        $body2 = $response2->getBody()->getContents();
        
        return response(["success"=>true, "data"=> json_decode($body), "message"=>"Success ."]);
    }catch (Exception $e){
        return response(["success"=>false, "data"=>$e->getMessage(), "message"=>"Error ."], 400);
    }
});



