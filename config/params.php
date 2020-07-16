<?php

return [
    'pdf' => [
        'password' => 'IntellectPL!',
        'permitions' => ['print']
    ],
    'mailer' => [
        'host' => 'smtp.mailtrap.io',
        'port' => 2525,
        'userName' => '637ed9e41ce265',
        'password' => 'a81489b397504a',
        'fromMail' => 'poslaniec@kdz.com',
        'fromName' => 'Posłaniec'
    ],
    'gen' => [
        'availableChar' => [
            '0','1','2','3','4','5','6','7','8','9',
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
        ],
        'defaultLengthOfCode' => 2,
        'defaultCountOfCode' => 100
    ]
];
