# Serwisant Online API

## Wstęp

Dostęp do API mają klienci z aktywną subskrypcją `all-in-one`. Wygaśnięcie subskrypcji które skutkuje przejściem aplikacji w 
tryb 'tylko-do-odczytu' będzie, w przypadku API skutkowało **całkowitą blokadą API**.

API oparte jest o autoryzację OAuth oraz format GraphQL. Działa na bazie standardowego protokołu HTTPS. 

GraphQL jest typowanym API, w którym używa się składni JSON do żądań a także w odpowiedziach. Jest to koncepcja całkowicie 
odmienna od API opartego o REST. 

## Zacznij w kilku krokach

- Zapoznaj się z dokumentacją znajdującą się na stronie [https://graphql.org/learn](https://graphql.org/learn)
- Przeczytaj resztę tego dokumentu, aby pogłębić wiedzę na temat API
- Pobierz i zainstaluj oprogramowanie do przeglądania dokumentacji API i wykonywania zapytań. Polecamy [Altair GraphQL Client](https://altair.sirmuel.design)
- Uruchom oprogramowanie i użyj jednego z adresów, podanych w sekcji _"Zakres funkcjonalny, aktorzy"_ aby przeglądać API. 
Nie potrzebujesz do tego aplikacji i danych OAuth, po prostu użyj odpowiedniego adresu i zacznij przeglądać dokumentację
- Zarejestruj aplikację OAuth w ustawieniach Serwisant Online
- Użyj klienta HTTP odpowiedniego dla języka, w którym piszesz, np `cUrl` dla `PHP` aby pobrać token autoryzacyjny i 
wykonywać zapytania GraphQL - nie musisz używać specjalnych bibliotek OAuth lub klientów GraphQL. Aby używać API wystarczy 
sam klient HTTP

## Zakres funkcjonalny, aktorzy

Zgodnie z koncepcją GraphQL, podstawowym elementem API jest `schema`. Z uwagi na fakt, iż w ramach aplikacji Serwisant Online
występują różni aktorzy (użytkownicy) udostępniliśmy kilka różnych schem, każda z nich przeznaczona jest dla innego aktora.

### `service`

Schema `service` zlokalizowana jest pod adresem `https://serwisant.online/graphql/service`.
Aktorem w tej części API jest pracownik serwisu. Oznacza to, że znajdują się tutaj funkcjonalności, które widzisz po zalogowaniu
się do aplikacji Serwisant Online jako pracownik. 

Korzystaj z tej schemy, jeśli chcesz budować integracje mające dostęp i mogące modyfikować wszystkie dane w twojej bazie. 


Schema co do zasady wymaga obecności konta pracownika i token OAuth użyty aby uzyskać do niej dostęp powinien być uzyskany 
poprzez zalogowanie. Mają tu zastosowanie wszystkie istniejące ograniczenia związane z pracownikiem, czyli limitowanie IP, 
 nadane uprawnienia, lub blokada/usunięcie konta. 
 
 Dodatkowo, po nadaniu specjalnego uprawnienia (patrz sekcja _"Autoryzacja"_) możesz uzyskać dostęp bez logowania jako pracownik. 
 Zwróć uwagę, że w tym przypadku nie będą stosowane ograniczenia, dostęp może być cofnięty wyłącznie poprzez usunięcie całej aplikacji
 OAuth.

### `public`

Schema `public`, nie wymaga aktora w postaci poświadczeń konkretnej osoby. Możesz ją znaleźć pod adresem `https://serwisant.online/graphql/public`
Przeznaczona jest do anonimowych operacji, takich jak sprawdzenie stanu naprawy, akceptacja lub odrzucenie kosztów naprawy. 

Korzystaj z tej schemy, jeśli chcesz udostępnić na własnej stronie proste sprawdzanie stanu naprawy.

Schema nie wymaga obecności konta pracownika lub klienta.

## Autoryzacja

Dostęp do API autoryzowany jest za pomocą tokenu OAuth, który należy uzyskać przed pierwszym zapytaniem do API.

Przed rozpoczęciem integracji wymagane jest abyś dodał aplikację OAuth. Możesz ją utworzyć używając narzędzia ze strony: 
[https://serwisant-online.pl/oauth_applications](https://serwisant-online.pl/oauth_applications)

W trakcie tworzenia aplikacji otrzymasz  `key` i `secret`, które posłużą do uzyskania tokenu OAuth. Musisz także określić 
zakres dostępu, który będzie miała aplikacja. Możesz wybrać spośród kilku uprawnień.

`public` - uprawnienie pozwalające aplikacji na dostęp do schemy `public`

`service_read` - uprawnienie pozwalające odczytywać dane ze schemy `service`

` service_write` - uprawnienie pozwalające zapisywać dane z użyciem schemy `service`. Zwróć uwagę, że do zapisu wymagane 
jest uprawnienie do odczytu, ponieważ będziesz musiał przed zapisem określić identyfikatory relacji.

`service_allow_untrusted` - pozwalaj na dostęp do API bez konieczności podawania poświadczeń pracownika (login, hasło) - do
autoryzacji wystarczą `key` i `secret` aplikacji OAuth. Wszelkie operacje zapisu będą robione na poczet pracownika `System`. 
Stosuj to uprawnienie z rozwagą, głównie  do aplikacji, w których nie istnieje kontekst pracownika, np. dodawanie napraw z 
poziomu twojej strony WWW.

Token możesz uzyskiwać wykonując zapytanie `HTTP POST` na `https://serwisant.online/oauth/token`

Zapytanie powinno mieć następujące parametry, wysłane jako `form-data`

-  `grant_type` - określa sposób logowania - `client_credentials` to logowanie bezkontekstowe, z użyciem danych aplikacji, 
`password` to logowanie konkretnego użytkownika i praca z jego poświadczeniami.
- `client_id` - identyfikator aplikacji, inaczej `key` podany podczas tworzenia aplikacji
- `client_secret` - hasło aplikacji, inaczej `secret` podany podczas tworzenia aplikacji
- `scope` - uprawnienia na których będzie działała aplikacja. Uprawnienia oddzielane są spacją. Możesz podać tu mniej uprawnień niż
zdefiniowano dla aplikacji, lecz nie więcej - prośba o uprawnienia inne, niż te które definiuje aplikacja identyfikowana poprzez `client_id`
nie powiedzie się.
- `username` i `password` - login i hasło pracownika lub klienta w przypadku użycia `grant_type=password` - to muszą być poprawne dane
konta istniejącego w ramach aplikacji.

Przykładowe zapytania:
```
curl 
  -X POST 
  --data "grant_type=client_credentials&client_id=xxx&client_secret=xxx&scope=public" 
  https://serwisant.online/oauth/token

curl 
  -X POST 
  --data "grant_type=client_credentials&client_id=xxx&client_secret=xxx&scope=service_read service_allow_untrusted" 
  https://serwisant.online/oauth/token

curl 
  -X POST 
  --data "?grant_type=password&client_id=xxx&client_secret=xxx&username=jankowalski&password=Sec.Ret.Pass&scope=service_read service_write" 
  https://serwisant.online/oauth/token
```

W odpowiedzi otrzymasz JSON, w którym będzie token OAuth, a także jego TTL (czas życia) w sekundach. Po upływie TTL token 
nie może być dłużej wykorzystany, jego użycie zostanie potraktowane jako nieautoryzowany dostęp. Przed upływem tego czasu należy
ponownie przeprowadzić operacje uzyskania tokena. 

**UWAGA**: nie powinieneś uzyskiwać tokena przy każdym zapytaniu. Token powinien być pozyskany jeśli wcześniej nie istniał
lub czas jego życia się skończył. Zastrzegamy sobie możliwość blokady aplikacji, które pozyskują token przy każdym zapytaniu.

## Zapytania API

Zapytania API wysyłane są metodą `POST` gdzie payload przekazany jest w formie `raw JSON` na adres odpowiedniej schemy. 
Zapytanie powinno być zgodnie ze specyfikacją GraphQL i zawierać nagłówki HTTP:
- `Authorization: Bearer 000000000000000000000`  - nagłówek autoryzacji, gdzie `000000000000000000000` jest tokenem OAuth 
uzyskanym z osobnego adresu (patrz sekcja _Autoryzacja_)
- `Content-Type: application/json` - określenie typu żądania

Przykładowe zapytanie:
```
curl
  -X POST
  -H "Content-Type: application/json"
  -H "Authorization: Bearer 000000000000000000000"   
  --data '{ "query": "{ viewer { employee { displayName } } }" }'
  https://serwisant.online/graphql/service
```