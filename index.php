<?php
header('Content-Type: text/html; charset=utf-8');
// подрубаем API
require_once "vendor/autoload.php";


// дебаг
if(true){
	error_reporting(E_ALL & ~(E_NOTICE | E_USER_NOTICE | E_DEPRECATED));
	ini_set('display_errors', 1);
}

// создаем переменную бота
$token = "614983231:AAFr-IYreAkrKyFczPK00XAa4mzJGNoTZPI";
$bot = new \TelegramBot\Api\Client($token,null);

if($_GET["bname"] == "revcombot"){
	$bot->sendMessage("@burgercaputt", "Тест");
}

// если бот еще не зарегистрирован - регистируем
if(!file_exists("registered.trigger")){
	/**
	 * файл registered.trigger будет создаваться после регистрации бота.
	 * если этого файла нет значит бот не зарегистрирован
	 */

	// URl текущей страницы
	$page_url = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$result = $bot->setWebhook($page_url);
	if($result){
		file_put_contents("registered.trigger",time()); // создаем файл дабы прекратить повторные регистрации
	} else die("ошибка регистрации");
}

// Команды бота
// пинг. Тестовая
$bot->command('ping', function ($message) use ($bot) {
	$bot->sendMessage($message->getChat()->getId(), 'pong!');
});

// обязательное. Запуск бота
$bot->command('start', function ($message) use ($bot) {
    $answer = 'Добро пожаловать! Чат ид = '.$message->getChat()->getId();
    $bot->sendMessage($message->getChat()->getId(), $answer);
});


// помощ
$bot->command('help', function ($message) use ($bot) {
    $answer = 'Команды:
/help - помощ';
    $bot->sendMessage($message->getChat()->getId(), $answer);
});


$bot->command('api', function ($message) use ($bot) {

    $data_string = "tt=54";
    $url = "http://alevd.ru/apitst.php";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string );
    $answer = curl_exec($curl);

    $bot->sendMessage($message->getChat()->getId(), $answer);
});


// Отлов любых сообщений + обрабтка reply-кнопок
$bot->on(function($Update) use ($bot){

	$message = $Update->getMessage();
	$mtext = $message->getText();
	$cid = $message->getChat()->getId();

	if(mb_stripos($mtext,"Сиськи") !== false){
		$pic = "http://aftamat4ik.ru/wp-content/uploads/2017/05/14277366494961.jpg";

		$bot->sendPhoto($message->getChat()->getId(), $pic);
	}
	if(mb_stripos($mtext,"власть советам") !== false){
		$bot->sendMessage($message->getChat()->getId(), "Смерть богатым!");
	}
}, function($message) use ($name){
	return true; // когда тут true - команда проходит
});
echo $bot->getRawBody();
// запускаем обработку
$bot->run();

echo "бот";