<?PHP

class Encrypt {
	public static function decrypt_web($buf, $key="password") {
		$key1 = md5($key);
		
        while($buf) {
           $m = substr($buf, 0, 16);
           $buf = substr($buf, 16);
          
           $c = "";
           for($i=0;$i<16;$i++)
           {
               $c .= $m{$i}^$key1{$i};
           }
          
           $ret_buf .= $m = $c;
        }
       
        return($ret_buf);
	}
	
	public static function encrypt_web($buf, $key="password") {
		$key1 = md5($key);
        while($buf) {
            $m = substr($buf, 0, 16);
            $buf = substr($buf, 16);
           
            $c = "";
            for($i=0;$i<16;$i++) {
                $c .= $m{$i}^$key1{$i};
            }
            $ret_buf .= $c;
        }
        return $ret_buf;
	}
	
	public static function encrypt_hmac($data, $key="password") {
		$b = 64; // byte length for md5
        if (strlen($key) > $b) {
                $key = pack("H*",md5($key));                    
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;
        $message = $k_opad . pack("H*",md5($k_ipad . $data));
        return base64_encode(md5($message));
	}
	
/* Ex)
	$name = '1';
    $enc_name = encrypt_md5($name);
    $dec_name = decrypt_md5($enc_name);
   
    echo "name = {$name}<br>\n";
    echo "encode_name = {$enc_name}<br>\n";
    echo "decode_name = {$dec_name}<br>\n";
*/
	public static function decrypt_md5($hex_buf, $key="password"){
        $len = strlen($hex_buf);
        for ($i=0; $i<$len; $i+=2)
            $buf .= chr(hexdec(substr($hex_buf, $i, 2)));
       
        $key1 = pack("H*", md5($key));
        while($buf)
        {
           $m = substr($buf, 0, 16);
           $buf = substr($buf, 16);
          
           $c = "";
           for($i=0;$i<16;$i++)
           {
               $c .= $m{$i}^$key1{$i};
           }
          
           $ret_buf .= $m = $c;
           $key1 = pack("H*",md5($key.$key1.$m));
        }
       
        return($ret_buf);
    }
   
    public static function encrypt_md5($buf, $key="password"){
        $key1 = pack("H*",md5($key));
        while($buf)
        {
            $m = substr($buf, 0, 16);
            $buf = substr($buf, 16);
           
            $c = "";
            for($i=0;$i<16;$i++)
            {
                $c .= $m{$i}^$key1{$i};
            }
            $ret_buf .= $c;
            $key1 = pack("H*",md5($key.$key1.$m));
        }
       
        $len = strlen($ret_buf);
        for($i=0; $i<$len; $i++)
            $hex_data .= sprintf("%02x", ord(substr($ret_buf, $i, 1)));
        return($hex_data);
    }
    
/* Ex)
    $name = '1';
    $enc_name = encrypt_md5_base64($name);
    $dec_name = decrypt_md5_base64($enc_name);
   
    echo "name = {$name}<br>\n";
    echo "encode_name = {$enc_name}<br>\n";
    echo "decode_name = {$dec_name}<br>\n";
*/    
    public static function encrypt_md5_base64($plain_text, $password="password", $iv_len = 16){
        $plain_text .= "\x13";
        $n = strlen($plain_text);
        if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
        $i = 0;
        while ($iv_len-- >0)
        {
            $enc_text .= chr(mt_rand() & 0xff);
        }
       
        $iv = substr($password ^ $enc_text, 0, 512);
        while($i <$n)
        {
            $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
            $enc_text .= $block;
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }
        return base64_encode($enc_text);
    }

    public static function decrypt_md5_base64($enc_text, $password="password", $iv_len = 16){
        $enc_text = base64_decode($enc_text);
        $n = strlen($enc_text);
        $i = $iv_len;
        $plain_text = '';
        $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
        while($i <$n)
        {
            $block = substr($enc_text, $i, 16);
            $plain_text .= $block ^ pack('H*', md5($iv));
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }
        return preg_replace('/\x13\x00*$/', '', $plain_text);
    }
}


?>