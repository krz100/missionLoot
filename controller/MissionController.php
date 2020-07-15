<?php
namespace Controller;

include_once 'common/generateCode.php';
include_once 'common/file.php';
include_once 'common/mailer.php';

class MissionController {
    private $requestMethod;

    public function __construct(string $requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->goOnMission();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    
    private function getData() :array
    {
        $input = [];
        $input['numberOfLoot'] = filter_input(INPUT_GET, 'numberOfLoot', FILTER_SANITIZE_NUMBER_INT);
        $input['address'] = filter_input(INPUT_GET, 'address', FILTER_SANITIZE_STRING);
        if (! $this->validateMission($input)) {
            return $this->unprocessableEntityResponse();
        }
        return $input;
    }

    private function goOnMission() :array
    {
        try {
            $input = $this->getData();
            //wygenerowanie kodów
            $generator = new \generateCode();
            $list = $generator->generateCodes(0, $input['numberOfLoot']);
            //utworzenie pliku pdf
            $file = new \file();
            $fileName = $file->savePdfFile(implode("<br>", $list));
            //wysłanie wiadomości
            $mailer = new \mailer();
            $mailResponce = $mailer->sendMail($input['address'], $fileName);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'success' => $mailResponce
            ]);
            return $response;
        } catch (\Exception $e) {
            $response['status_code_header'] = 'HTTP/1.1 '.$e->getCode();
            $response['body'] = json_encode([
                'error' => 'GAME OVER - '.$e->getMessage()
            ]);
            return $response;
        }
    }

    private function validateMission(array $input) :bool
    {
        if (! isset($input['numberOfLoot'])) {
            return false;
        }
        if (! isset($input['address'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse() :array
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse() :array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}