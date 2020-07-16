<?php

require_once __DIR__ . './../vendor/autoload.php';

class file {
    
    /**
    * sprawdzenie i utworzenie danej ścieżki
    * @param string $path
    * @return string
    */
    private function directoryPath(string $fileName)
    {
        $path_parts = pathinfo($fileName);
        $path = '';
        if(isset($path_parts['dirname'])) {
            $path = $path_parts['dirname'];
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        }
        return $path;
    }

    /**
     * zapis do pliku
     * @param type $fileName
     * @param type $text
     */
    public function saveFile(string $fileName, string $text = '')
    {
        $this->directoryPath($fileName);
        file_put_contents ($fileName , $text);
    }
    
    public function savePdfFile(string $text = '') :string
    {
        $params = require(__DIR__ . '/../config/params.php');
        $pdfParams = isset($params['pdf'])?$params['pdf']:[];
        $mpdf = new \Mpdf\Mpdf();
        $fileName = './../files/'.md5(time()).'.pdf';
        $permitions = isset($pdfParams['permitions'])?$pdfParams['permitions']:array('print');
        $password = isset($pdfParams['password'])?$pdfParams['password']:'';
        $mpdf->SetProtection($permitions, $password, $password);
        $mpdf->WriteHTML($text);
        $this->directoryPath($fileName);
        $mpdf->Output($fileName, \Mpdf\Output\Destination::FILE);
        return $fileName;
    }

    public function returnFile(string $filename) {
        $attachment_location = $filename;
        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public");
        header("Content-Type: application/txt");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($attachment_location));
        header("Content-Disposition: attachment; filename=".$filename);
        readfile($attachment_location);
        die();
    }
}


