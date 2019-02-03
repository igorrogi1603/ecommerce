<?php

namespace Hcode;

use Rain\Tpl;

class Mailer {
	//-----------------------------------------------------------------------//
	//																		 //
	//	  USAR UM GMAIL JA EXISTENTE PARA ENVIAR OS EMAIL DE RECUPERAÇÃO     //
	//																		 //
	//-----------------------------------------------------------------------//
	const USERNAME = "seu email@gmail.com";
	const PASSWORD = "sua senha";
	const NAME_FROM = "Hcode Store";

	private $mail;

	public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
	{

		$config = array(
			"tpl_dir"	=> $_SERVER["DOCUMENT_ROOT"]."/views/email/",
			"cache_dir"	=> $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"		=> false
		);
		
		Tpl::configure( $config );
		
		$tpl = new Tpl;

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName, true);

		$this->mail = new \PHPMailer;

		//Enable SMTP debugging
		//0 = production
		//1 = client menssages
		//2 = clint and server messages
		$this->mail->SMTPDebug = 0;

		$this->mail->Debugoutput = 'html';

		$this->mail->Host = 'smtp.gmail.com';

		$this->mail->Port = 587;

		//ativar apenas para teste 
		//nao deixar ativado pois deixa o email vuneravel
		//ele permite enviar o emil com http 
		//pois o google nao deixa receber pois estamos usando http nao https
		$this->mail->isSMTP();
		$this->mail->SMTPOptions = array(
		    'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		);

		$this->mail->SMTPSecure = 'tls';

		$this->mail->SMTPAuth = true;

		$this->mail->Username = Mailer::USERNAME;

		$this->mail->Password = Mailer::PASSWORD;

		$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

		$this->mail->addAddress($toAddress, $toName);

		$this->mail->Subject = $subject;

		$this->mail->msgHTML($html);

		$this->mail->AltBody = 'Mensagem que vai aparecer';

	}

	public function send()
	{
		return $this->mail->send();
	}

}

?>