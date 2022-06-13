<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WakafController extends Controller
{
   /**
     * TagihanFinnet
     *
     * @param  mixed $request
     * @return json
     */
    //public function TagihanFinnet(Request $request)
	public function TagihanFinnet(Request $request)
    {
				//Put the merchant_id and key here
		$nominal = $request->nominal;
		$key="ZORA2016";
		$merchant_id="ZORA484";

		//$key="7JuSFXPs";
		//$merchant_id="O777QAE5IYZNS";

		//Put all the d parameters here, in free order
		$amt="1".rand(1000,9999);
		$arrParam=array(
			"amount" => $nominal,//$amt, amount transaksi
			"cust_email" => "email@email.com",
			"cust_id" => "123456",
			"cust_msisdn" => "081101010101",
			"cust_name" => "Ramon Mon",
			"invoice" => date("mdHis").rand(11,99), //min 6 digit max 12 digit, unik
			"merchant_id" => $merchant_id,
			"items" => "[[\"Kura-kura Terbang\",\"".$amt."\",1]]",
			"return_url" => "https://enjocyjoky2hc.x.pipedream.net",
			"success_url" => "https://enprkoj2cr34s.x.pipedream.net",
			"failed_url" => "https://enbgemck5o0z.x.pipedream.net",
			"back_url" => "https://en83plhn4lo0g.x.pipedream.net",
			"timeout" => "1440", //in minutes
			"trans_date" => date("YmdHis"), 
			"add_info1" => "satu",
			"add_info2" => "dua XXX . a",
			"add_info3" => "tiga",
			"add_info4" => "empat",
			"add_info5" => "lima",
		);

		//call the function, and Voila!, this is the correct signature
		$signature=$this->createSignature($arrParam, $key);
		$arrParam=array_merge($arrParam, array("mer_signature"=>$signature));

		//Post the data
		$eurl="https://sandbox.finpay.co.id/servicescode/api/pageFinpay.php";

	/* 	echo "mid: $merchant_id\n";
		echo "key: $key\n";
		echo "url_endpoint: $eurl\n\n";
		print_r($arrParam); */

		$postfields=http_build_query($arrParam);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $eurl);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if ($err) {
			echo "cURL Error #:" . $err;
		}else{
			$response=json_decode($response, true);
		//	print_r($response);
		}
		
		return $response;
	}
	
	 /**
     * createSignature
     *
     * @param  mixed $arrParam
     * @return string
     */
	function createSignature($arrParam, $key)
	{
		ksort($arrParam, 0);
		$signature=hash('sha256', strtoupper(implode("%",$arrParam))."%".$key);;
		return $signature;
	}
}
