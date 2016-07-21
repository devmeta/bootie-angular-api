<?php 

//Encode message for transfer to client.
function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	
	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}


/*      Simple Websocket Client
	copyleft Stefano Cudini 2011-01-19
	stefano.cudini@...

	inspired by:
		http://caucho.com/resin-4.0/examples/websocket-php/
*/
	$host = '127.0.0.1';  //where is the websocket server
	$port = 9999;
	$local = "http://localhost/";  //url where this script run
	//$data = 'hello world!';  //data to be send
	$data = json_encode(array('type'=>'usermsg', 'name'=>'martin', 'message'=>'testing...', 'color' => 'green'));

	$head =	"GET / HTTP/1.1"."\r\n".
			"Sec-WebSocket-Key: fYwwu2X8tGg6/PIkfDA5mA=="."\r\n".
			"Upgrade: WebSocket"."\r\n".
			"Connection: Upgrade"."\r\n".
			"Origin: $local"."\r\n".
			"Host: $host"."\r\n".
			"Content-Length: ".strlen($data)."\r\n"."\r\n";
	////WebSocket handshake
	$sock = fsockopen($host, $port, $errno, $errstr, 2);
	fwrite($sock, $head ) or die('error:'.$errno.':'.$errstr);
	$headers = fread($sock, 2000);
	fwrite($sock, "$data" ) or die('error:'.$errno.':'.$errstr);
	$wsdata = fread($sock, 2000);  //receives the data included in the websocket package "\x00DATA\xff"
	$retdata = trim($wsdata,"\x00\xff"); //extracts data
	////WebSocket handshake
	fclose($sock);

	echo $retdata  //data result
?>