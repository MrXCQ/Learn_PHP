<?php
header('Content-Type: text/html; charset=UTF-8');
$data = array(
    'author' => '苏轼',
    'titie' => '江城子·密州出猎',
    'contents'=>'老夫聊发少年狂，左牵黄，右擎苍。锦帽貂裘，千骑卷平冈。为报倾城随太守，亲射虎，看孙郎。
    酒酣胸胆尚开张，鬓微霜，又何妨？持节云中，何日遣冯唐？会挽雕弓如满月，西北望，射天狼。'
);

$response = array(
    'code'=>200,
    'message' => 'success',
    'data' => $data
);


echo json_encode($response);