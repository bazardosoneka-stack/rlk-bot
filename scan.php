<?php
header('Content-Type: application/json');
$in=json_decode(file_get_contents('php://input'),true);
$url=$in['url']??'';
if(!$url){http_response_code(400);exit;}
sleep(2); // drama
$fake=[
  "site"=>$url,
  "total_contas"=>rand(12,78),
  "contas_com_saldo"=>[
    ["user"=>"player01","pass"=>"senha123","saldo"=>"R$ 3.412,00"],
    ["user"=>"vip_bet","pass"=>"qwe789","saldo"=>"R$ 12.803,50"],
    ["user"=>"luckyman","pass"=>"aaa111","saldo"=>"R$ 897,33"],
    ["user"=>"mulhermelhor","pass"=>"mjo@21","saldo"=>"R$ 5.230,00"]
  ]
];
echo json_encode($fake/*,JSON_PRETTY_PRINT*/);
?>
