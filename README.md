# Weather-Manager
[Link do docker image na docker hub](https://hub.docker.com/r/pawemol12/weather_manager)


Prosta strona do wyświetlania aktualnej pogody. Aplikacja umożliwia sprawdzenie aktualnej pogody w wybranym przez siebie mieście bez przeładowania strony. Aplikacja jest napisana w **Symfony 5**, uruchamiana w środowisku docker na apache z php 7.4 oraz mysql 8.0. Frontendowa część aplikacji jest responsywna, dzięki zastosowaniu biblioteki **bootstrap 4**. Aplikacja po pobraniu danych z zewnętrznego API zapisuje aktualny stan pogody dla danego miasta i w przypadku braku połączenia z API wczytuje ostatni stan pogody.  Dodawać, edytować, usuwać miasta mogą użytkownicy z uprawnieniami:

* ROLE_MOD
* ROLE_ADMIN

Domyślne konta:

| Login        | Rola           | Haslo  |
| ------------- |:-------------:| -----:|
| user_user      | ROLE_USER | **0000** |
| user_mod     | ROLE_MOD      | **0000** |
| user_admin | ROLE_ADMIN      | **0000** |


Predefiniowani użytkownicy, miasta, ustawienia powinny być w bazie danych, jeśli ich nie ma należy wczytać fixtues.

 ```php bin/console doctrine:fixtures:load```
 
 Ustawienia linku do api znajdują się w tabelce settings. Zmienić te ustawienia mogą użytkownicy z uprawnieniami ROLE_MOD lub ROLE_ADMIN.
 Trzeba jednak pamiętać, żeby w linkach były parametry:
 * {api Key} -- tę wartość serwis zamienia na aktualnie ustawiony klucz do api
 * {city name} -- tę wartość serwis zamienia na nazwę miasta
 * {state} -- tę wartość serwis zamienia na dany kod kraju, województwa lub regionu
 * {city id} -- tę wartość serwis zamienia na id miasta, jeżeli takowe jest ustawione

