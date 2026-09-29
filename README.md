# TRISTAR Camera Demo

Minimalna aplikacja demonstracyjna pokazująca, jak pobrać listę kamer TRISTAR, odczytać ich położenie oraz wyświetlić najnowszy dostępny obraz z otwartego API ZDiZ w Gdyni.

## Dlaczego jest tu `api.php`?

API TRISTAR odpowiada przez HTTPS, ale obecnie odpowiedzi JSON nie zawierają nagłówka CORS pozwalającego aplikacji działającej w innej domenie odczytać je bezpośrednio przez `fetch()`.

Dlatego przykład korzysta z minimalnego proxy PHP:

```text
przeglądarka -> api.php -> API TRISTAR
```

Proxy:
- nie buforuje danych,
- nie modyfikuje JSON-u,
- nie przechowuje danych,
- przepuszcza wyłącznie dwa endpointy używane przez demonstrację.

Sam obraz jest wyświetlany bezpośrednio z `https://api.zdiz.gdynia.pl` w elemencie `<img>`.

## Wymagania

Serwer WWW z:
- PHP 8.x (kod powinien działać także na współczesnych wersjach PHP 7),
- rozszerzeniem cURL,
- dostępem wychodzącym HTTPS do `api.zdiz.gdynia.pl`.

## Pliki

- `index.html` – interfejs i JavaScript aplikacji,
- `api.php` – minimalne proxy do odczytu JSON-u,
- `README.md` – opis projektu.

## Uruchomienie

Skopiuj `index.html` i `api.php` do tego samego katalogu na serwerze obsługującym PHP.

Przykładowo:

```text
/app/tristar/
  index.html
  api.php
```

Następnie otwórz `index.html` przez HTTP/HTTPS.

Nie uruchamiaj aplikacji przez `file://`, ponieważ `api.php` musi zostać wykonany przez serwer PHP.

## Endpointy TRISTAR używane przez przykład

- `https://api.zdiz.gdynia.pl/ri/rest/cameras`
- `https://api.zdiz.gdynia.pl/ri/rest/camera_image_data?cameraId=ID`

Proxy udostępnia je aplikacji lokalnie jako:

- `./api.php?path=ri/rest/cameras`
- `./api.php?path=ri/rest/camera_image_data&cameraId=ID`

## Odświeżanie

Aplikacja sprawdza najnowszy obraz co 5 minut i pozwala wymusić odświeżenie ręcznie. Jeśli API zwróci kilka rekordów, wybierany jest wpis z najnowszym `insertTime`.

## GitHub Pages

Repozytorium może być przechowywane na GitHubie, ale tej wersji demonstracji nie można uruchomić wyłącznie przez GitHub Pages, ponieważ GitHub Pages nie wykonuje PHP.

Działające demo należy opublikować na serwerze obsługującym PHP, np. w domenie portalu Otwarte Dane Gdynia.
