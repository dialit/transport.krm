<?php
/**
* Форма обратной связи, отправка письма
* Site: http://bezramok-tlt.ru
* Регистрация пользователя письмом
*/
 
// Устанавливаем константы 
//Адрес почты кому отправляем
define('MAIL_TO','Office <dialit73@gmail.com>');

//Адрес почты от кого отправляем
define('MAIL_AUTOR','kram-ways.pp.ua <no-reply@kram-ways.pp.ua>');

$captcha_error = false;
 
/**Отпровляем сообщение на почту
* @param string  $to - Кому
* @param string  $from - От кого
* @param string  $title - Заголовок письма
* @param string  $message - Тело письма
*/
function sendMail($to, $from, $title, $message)
{
	//Формируем заголовок письма
	$subject = $title;
	$subject = '=?utf-8?b?'. base64_encode($subject) .'?=';
   
	/*Формируем заголовки для почтового сервера,
	Говорим серверу что используем HTML*/
	$headers = "Content-type: text/html; charset=\"utf-8\"\r\n";
	$headers .= "From: ". $from ."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Date: ". date('D, d M Y h:i:s O') ."\r\n";

	//Отправляем данные на ящик
	if(!mail($to, $subject, $message, $headers))
		return 'Ошибка отправки письма!';  
	else  
		return true;  
}

if ($_REQUEST['g-recaptcha-response']) {

    require_once '../includes/recaptcha/recaptchalib.php';

    $secret = "6LciQCsUAAAAAFtPzx86xPqlqiTLOnwzgSwytS3D";

    $response = NULL;

    // check secret key
    $reCaptcha = new ReCaptcha($secret);
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_REQUEST['g-recaptcha-response']
    );
    if (!$response->success) {

        $captcha_error = 1;
    }

} else {

    $captcha_error = 1;
}

//Если отправили форму, проверяем данные	
if(isset($_POST['email']))
{
	//Определяем переменные
	$resp = array();
	
	//Утюжим переменные
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$resp['err'][] = 'Не верный Email'; 
	}
	
	//Шаблон проверки мобильного телефона
//	$pattern = '/(8|7|\+7)?9\d{9}/';
	$pattern = '/(\+38)\d{10}/';
	
	//Проверяем мобильный телефон
	if(!preg_match($pattern, $_POST['mobile'])){
		$resp['err'][] = 'Не верный мобильный телефон';
	}
    if($captcha_error){
        $resp['err'][] = 'Ошибка капчи - отправьте форму повторно';
    }
   
	//Формируем заголовок письма
	$title = 'Ура нам письмо пришло!';
		
	//Формируем HTML верстку письма для отправки
	$msg  = 'Имя <strong>'. $_POST['name'].'</strong><br />';
	$msg .= 'Мобильный телефон <strong>'. $_POST['mobile'].'</strong><br />';
	$msg .= nl2br($_POST['text']);
	 
	//Проверяем ошибки
	if(!empty($resp['err']))
	{
		//Выводим ошибки
		$resp['status'] = 0;
		echo json_encode($resp);
	}
	else
	{
		//Вызываем функцию отправки письма
		if(sendMail(MAIL_TO, MAIL_AUTOR, $title, $msg))
		{
			//Отправляем сообщение пользователю
			$resp['ok'] = 'Письмо отправленно...';
			$resp['status'] = 1;
			echo json_encode($resp);
		}
	}
	
}

?>