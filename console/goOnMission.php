<?php

include_once '../common/generateCode.php';
include_once '../common/file.php';
include_once '../common/mailer.php';

//php goOnMission.php --numberOfLoot 10 --address test@intelect.pl
$shortopts  = "n:a:";

$longopts  = array(
    "numberOfLoot:",
    "address:",
);
$options = getopt($shortopts, $longopts);
try {
    //wygenerowanie kodów
    $generator = new generateCode();
    $list = $generator->generateCodes(0, isset($options['numberOfLoot'])?$options['numberOfLoot']:0);
    //utworzenie pliku pdf
    $file = new file();
    $fileName = $file->savePdfFile(implode("<br>", $list));
    //wysłanie wiadomości
    $mailer = new mailer();
    echo $mailer->sendMail(isset($options['address'])?$options['address']:'', $fileName);
} catch (\Exception $e) {
    echo 'GAME OVER - '.$e->getMessage();
}
?>
