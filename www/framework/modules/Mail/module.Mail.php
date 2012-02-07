<?PHP


/***
 * MAIL Class
 *
 * $headers['From']    = "{보내는 사람}";
 * $headers['To']      = "{받는 사람}";
 * $headers['Subject'] = "{제목}";
 * $body = "{보내는 내용}";
 * //$params['sendmail_path'] = "/usr/sbin/sendmail";
 *
 * $mail = new Mail($params);
 * $headers = Array( );
 * $headers['Subject'] = $_GET["email_title"];
 * $headers['Subject'] = str_replace("{end_date}", date("Y-m-d",strtotime("+7 day")), $headers['Subject']);
 * $headers['Name']    = $_GET["user_name"];
 * $headers['From']    = $_GET["user_email"];
 * $headers['To']      = $_GET["recip_email"];
 * $body = $_GET["email_content"];
 * $mail->send( $headers, $body );
 */

class Mail {
	
	var $sep = "\n";
	var $sendmail_args = '';
	var $sendmail_path = '/usr/sbin/sendmail';
	var $errors;

	public function __construct($params) {
		if (isset($params['sendmail_path'])) $this->sendmail_path = $params['sendmail_path'];
		if (isset($params['sendmail_args'])) $this->sendmail_args = $params['sendmail_args'];
	}

	private function error($msg) {
		$this->errors[] = "[ERROR] $msg";
		return false;
	}

	private function http_src($content) {
		$host = 'http://'.$_SERVER[HTTP_HOST];
		$host = preg_replace("/:[0-9].+$/","",$host); //포트번호 삭제

		$pattern_a = array("@(\s*href|\s*src)(\s*=\s*'{1})(/[^']+)('{1})@ie"
						, "@(\s*href|\s*src)(\s*=\s*\"{1})(/[^\"]+)(\"{1})@ie"
						, "@(\s*href|\s*src)(\s*=\s*)(/[^\s>\"\']+)(\s|>)@ie"
		);
		$replace_a = "'\\1\\2".($host)."\\3\\4'";
		$content = preg_replace($pattern_a, $replace_a, $content);
		return $content;
	}

	public function send($headers, $body) {
		$body = stripslashes($body);
		$headerElements = $this->prepareHeaders($headers);

		list($from, $text_headers) = $headerElements;

		if (!isset($from)) return $this->error('No from address given');
		if (!$this->recipients) return $this->error('No to address given');

		$body = $this->setHTMLBody($body,$this->bodyFile);

		$body = str_replace('\\','',$this -> http_src($body));

		$body = chunk_split(base64_encode($body));

		$result = 0;

		if ( is_file($this->sendmail_path)) {
			//echo "sendmail !!";
			
			$from = escapeShellCmd($from);
			$return = $from;	// 리턴메일 주소 설정
			
			$sendmail_exec = $this->sendmail_path . (!empty($this->sendmail_args) ? ' ' . $this->sendmail_args : '') . " -t -f $return";// -- $this->recipients
			//$sendmail_exec = $this->sendmail_path . (!empty($this->sendmail_args) ? ' ' . $this->sendmail_args : '') . " -t";//" -i ".$this->recipients;
			
			$mail = popen($sendmail_exec, 'w');
			fputs($mail, $text_headers);
			fputs($mail, $this->sep);
			fputs($mail, $body);
			$result = pclose($mail) >> 8 & 0xFF;
		} else {
			//echo "mail !!";
			$to_email = $headers['To'];
			unset($headers['To']);
			
			$headerElements = $this->prepareHeaders($headers);
			list($from, $text_headers) = $headerElements;

			$ret = mail($to_email, $headers['Subject'], $body, $text_headers);
			if (!$ret) return $this->error('sendmail [' . $this->sendmail_path . '] is not a valid file');
		}

		if ($result != 0) {
			return $this->error('sendmail returned error code ' . $result);
		}

		return true;
	}

	private function encode_2047($subject) {
		return '=?utf-8?b?'.base64_encode($subject).'?=';
	}

	private function prepareHeaders($headers)
	{
		$lines	= array();
		$from	= null;

		$headers['MIME-Version'] = '1.0';
		$headers['Content-Type'] = 'text/html;charset=utf-8';
		$headers['Content-Transfer-Encoding'] = 'base64';

		foreach ($headers as $key => $value) {
			if (strcasecmp($key, 'From') === 0){
				$from = $value;
				$value = '"'.$this->encode_2047($headers['Name']).'" <'.$value.'>';
			} else if (strcasecmp($key, 'Subject') === 0) $value = $this->encode_2047($value);
			else if (strcasecmp($key, 'To') === 0) $this->recipients = $value;
			if (strcasecmp($key, 'Name') !== 0) $lines[] = $key . ': ' . $value;
		}
		return array($from, join($this->sep, $lines) . $this->sep);
	}

	private function setHTMLBody($data, $isfile = false) {
		if (!$isfile) return $data;
		else return $this->_file2str($isfile);
	}

	private function _file2str($file_name) {
		if (!is_readable($file_name)){
			return $this->error('File is not readable ' . $file_name);
		}
		if (!$fd = fopen($file_name, 'rb')) {
			return $this->error('Could not open ' . $file_name);
		}
		$cont = fread($fd, filesize($file_name));
		fclose($fd);
		return $cont;
	}

}

?>
