# missionLoot

## Information

Przygotuj aplikację webową, która generuje 'n' unikalnych kodów złożonych z znaków [A-Z0-9]{2}.
Wygenerowane kody zapisują się do pliku na serwerze o unikalnej nazwie.
Plik zostaje wysłany w wiadomości email w postaci pliku PDF na adres określony parametrem 'email'.
Wysłany w wiadomości plik PDF jest zabezpieczony.
Użytkownik może go otworzyć podając hasło 'IntellectPL!' ale posiada jedynie uprawnienia do drukowania dokumentu.
Aplikacja nie posiada interfejsu graficznego.
Jedyna forma komunikacji z aplikacją odbywa się poprzez REST API lub konsolę.

## QUICK INSTALL:

- Get repo

- Update composer

```composer update```

- Start server

```$ cd [my-app-name]; php -S localhost:8080 -t public public/index.php```

## Set your Config

- in file

``` config/params.php ```

## Example

### console

``` 
cd console

php goOnMission.php --numberOfLoot 10 --address test@intelect.pl  
```

### api

``` http://localhost:8080/missions?numberOfLoot=[number]&address=[email] ```
