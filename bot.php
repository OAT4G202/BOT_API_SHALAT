<?php
/*
copyright @ medantechno.com
Modified @ Farzain - zFz
2017

*/

require_once('./line_class.php');
require_once('./unirest-php-master/src/Unirest.php');

$channelAccessToken = 'LfRQfo4Ec4bgMeZdwFjP9/V3pydY6dcDH3D1QbILkg8yKs3DGueJFfcTugctITCVdfpdihQdj3RRy2717/AXH9AfPwua7UzdSTCG3sPbRzs2lhOSydw/x8zGD5FlaKiR+4Kc7VTvaDpldIWCygn31QdB04t89/1O/w1cDnyilFU='; //sesuaikan 
$channelSecret = '706ec586967e7a92045c68665c8c14ae';//sesuaikan

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$userId 	= $client->parseEvents()[0]['source']['userId'];
$groupId 	= $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp	= $client->parseEvents()[0]['timestamp'];
$type 		= $client->parseEvents()[0]['type'];

$message 	= $client->parseEvents()[0]['message'];
$messageid 	= $client->parseEvents()[0]['message']['id'];

$profil = $client->profil($userId);

$pesan_datang = explode(" ", $message['text']);

$command = $pesan_datang[0];
$options = $pesan_datang[1];
if (count($pesan_datang) > 2) {
    for ($i = 2; $i < count($pesan_datang); $i++) {
        $options .= '+';
        $options .= $pesan_datang[$i];
    }
}
#-------------------------[Function]-------------------------#
function shalat($keyword) {
    $uri = "https://time.siswadi.com/pray/" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "❴Jadwal Shalat Sekitar❵ ";
	$result .= $json['location']['address'];
	$result .= "\n★Tanggal★ : ";
	$result .= $json['time']['date'];
	$result .= "\n\n★Shubuh★ : ";
	$result .= $json['data']['Fajr'];
	$result .= "\n★Dzuhur★ : ";
	$result .= $json['data']['Dhuhr'];
	$result .= "\n★Ashar★ : ";
	$result .= $json['data']['Asr'];
	$result .= "\n★Maghrib★ : ";
	$result .= $json['data']['Maghrib'];
	$result .= "\n★Isya★ : ";
	$result .= $json['data']['Isha'];
    return $result;
}
#-------------------------[Function]-------------------------#

# require_once('./src/function/search-1.php');
# require_once('./src/function/download.php');
# require_once('./src/function/random.php');
# require_once('./src/function/search-2.php');
# require_once('./src/function/hard.php');

//show menu, saat join dan command /menu
if ($type == 'join' || $command == 'Menu') {
    $text = "Assalamualaikum Kakak, aku adalah bot jadwal shalat,\n      ★EDITOR BOT★\nhttp://line.me/ti/p/~adiputra.95\nsilahkan ketik\n\nshalat •nama tempat•\n\nnanti aku bakalan kasih tahu jam berapa waktunya shalat ^_^";
    $balas = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}

//pesan bergambar
if($message['type']=='text') {
	    if ($command == 'Shalat') {

        $result = shalat($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }

}else if($message['type']=='sticker')
{	
	$balas = array(
							'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'text',									
										'text' => '👶🏼ᏚᎢᏆᏟKᎬᎡ👶🏼 10Ꮯ ᏴᏌᎪNᏩ KᎬ ᏞᎪᏌᎢ😂'.$profil->displayName.										
									
									)
							)
						);
						
}
if($message['type']=='image')
{	
	$balas = array(
							'UserID' => $profil->userId,	
                                                        'replyToken' => $replyToken,							
							'messages' => array(
								array(
										'type' => 'text',									
										'text' => 'Gambar apa itu kak'.$profil->displayName.								
									
									)
							)
						);
						
}
if (isset($balas)) {
    $result = json_encode($balas);
//$result = ob_get_clean();

    file_put_contents('./balasan.json', $result);


    $client->replyMessage($balas);
}
?>
