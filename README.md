# Serwisant Online PHP API


## Wstęp


Dostęp do API mają klienci z subskrypcją 'all-in-one'

Rozszerzone API zwraca dane osobowe Twoich klientów - wyniki zwrócone przez API powinny być zabezpieczone przed publicznym 
dostępem - na Tobie spoczywa  odpowiednie zabezpieczenie tych danych.

API działa w imieniu pracownika który wygenerował i udostępnił dane autoryzacyjne. Wszystkie operacje będą wykonywane na 
konto tego pracownika i podpisywane jego imieniem. Rozsądnym podejściem jest założenie fikcyjnego pracownika, np. 
'Synchronizacja danych', wygenerowanie na nim danych dostępowych.

## Autoryzacja

Dostęp autoryzowany jest za pomocą OAuth, wymagane jest abyś otrzymał lub wygenerował samodzielnie Key i Secret.

Key i Secret możesz utworzyć na tej stronie: [https://serwisant-online.pl/oauth_credentials](https://serwisant-online.pl/oauth_credentials).

W żądaniu HTTP przesyłąsz zawsze Key, natomiast Secret jest daną poufną, służącą do podpisania żądania. Powinieneś
traktować Secret tak, jak hasło do aplikacji, chronić je i nie udostępniać osobom trzecim.
 
Używamy uproszczonego OAutha - nie wymagamy abyś pobierał request_token, ani przeprowadzał autoryzację request_tokena. 

Wystarczy, że każdy request HTTP zaopatrzysz w nagłówek ``Authorization`` zawierający sygnaturę OAuth 1.0 wygenerowaną
z użyciem ``HMAC-SHA1``.

Przykładowy nagłówek:
```
Authorization: OAuth oauth_version="1.0",oauth_nonce="c33b16edb4d87c2891a641863755265a",oauth_timestamp="1500500769",oauth_consumer_key="f073e5b4-e638-439a-8109-7da713cfd73e",oauth_signature_method="HMAC-SHA1",oauth_signature="zX2tE1d2e2LgZDFw%2F5vNG5wG0pA%3D"
```

Przykłady znajdziesz w katalogach ``php`` oraz ``ruby`` - sugerujemy, abyś nie implementował OAuth na nowo, poszukaj
biblioteki odpowiedniej dla Twojego języka - z pewnością coś znajdziesz. Dla ``PHP`` polecamy przygotowaną przez nas
bibliotekę kliencką: ``serwisant/serwisant-api`` która dostępna jest via ``composer``

## Paginacja list

Każda wystawiana przez API lista ma nie więcej niż **10 elementów**. Aby pobrać kolejne 10 elementów należy dodać do adresu
endpointu parametr ``page=n`` gdzie ``n`` to kolejna strona.

Jeśli ilość elementów listy zwróconej dla strony ``n`` jest mniejsza niż 10 (może być 0) oznacza to, że nie ma więcej
elementów na liście.

Stad aby pobrać wszystkie elementy należy wywołać endpoint inkrementując parametr ``page`` do czasu, aż dostaniemy mniej
niż 10 elementów w wyniku.

## Naprawy

### Lista napraw

```
https://serwisant-online.pl/api/v1/orders.json
```

Pod powyższym endpointem podajemy listę napraw - 

Parametry:
* _lang_ - zawsze ``pl``
* _page_ - numer strony - int
* _filter_ - widok listy - string
* _sort_ - sposób sortowania listy - string
* _id_ lub _status_id_ - dodatkowy warunek filtra, przekazujący np. identyfikator pracownika lub stanu naprawy

Filtry:
* _all_ - wszystkie naprawy, włącznie z zakończonymi
* _expired_ - naprawy przeterminowane
* _delegated_ - naprawy oddelegowane do zewnętrznych serwisów - wymaga dodatkowego parametru _id_ określającego identyfikator serwisu
* _status_ - naprawy w konkretnym statusie - wymaga dodatkowego parametru _status_id_ określającego identyfikator stanu naprawy
* _open_ - otwarte (nieodebrane) naprawy
* _service_supplier_ - naprawy przyjęte w konkretnym oddziale serwisowym - wymaga dodatkowego parametru _id_ określającego identyfikator serwisu
* _employee_service_supplier_ - naprawy naprawiane w konkretnym oddziale serwisowym - wymaga dodatkowego parametru _id_ określającego identyfikator serwisu
* _employee_ - naprawy przypisane do konkretnego pracownika - wymaga  dodatkowego parametru _id_ określającego identyfikator pracownika

Sortowanie wg.:
* _date_created_ - daty utworzenia 
* _date_started_ - daty rozpoczęcia
* _date_started_rev_ - daty rozpoczęcia (odwrócona)
* _rma_ - numeru RMA
* _customer_ - identyfikatora klienta
* _kind_ - typy przedmioty naprawy
* _status_ - stanu naprawy
* _days_remaining_ - ilości dni, które zostały do końca naprawy
* _updated_at_ - daty aktualizacji

Przykładowe zapytania:

```
https://serwisant-online.pl/api/v1/orders.json?lang=pl&page=1&filter=open&sort=status
https://serwisant-online.pl/api/v1/orders.json?lang=pl&page=1&filter=status&status_id=10
```
