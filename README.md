# Wtyczka "Sticky Phone Button" (przyklejony przycisk telefonu )

Wtyczka wyświetla przyklejony przycisk telefonu na stronie, umożliwiający natychmiastowe połączenie, z opcją dostosowania jego wyglądu, położenia oraz godzin widoczności.

## Jak działa ta wtyczka, krok po kroku

Oto, jak działa wtyczka "Sticky Phone Button" krok po kroku:

1. **Start**:
    - Po zainstalowaniu i aktywacji wtyczki na stronie WordPress, jej funkcje są dodawane do systemu WordPress.
    - Zabezpieczenie jest ustawione w taki sposób, aby kod nie był uruchamiany bezpośrednio, jeśli nie znajduje się w środowisku WordPress.
2. **Dodawanie stylów i skryptów**:
    - Wtyczka ładuje pliki CSS i JavaScript tylko na stronach front-endowych, co oznacza, że nie będą one ładowane w panelu administracyjnym WordPressa.
    - Plik CSS odpowiada za wygląd przycisku, a plik JavaScript może obsługiwać interaktywne funkcje, np. animacje.
3. **Dodanie strony ustawień w panelu administracyjnym**:
    - Wtyczka dodaje nową stronę ustawień w menu "Ustawienia" w panelu WordPress, dzięki czemu administrator strony może zarządzać funkcjami przycisku telefonu.
4. **Konfiguracja ustawień**:
    - Na stronie ustawień administrator może:
        - Wpisać numer telefonu, na który użytkownik będzie dzwonił po kliknięciu przycisku.
        - Wybrać, na jakich urządzeniach (telefony, komputery, oba) przycisk będzie wyświetlany.
        - Określić, w których dniach tygodnia i godzinach przycisk będzie aktywny.
        - Dostosować kolor ikony telefonu i tła przycisku.
        - Ustawić pozycję przycisku na ekranie (np. dolny prawy róg).
5. **Przechowywanie ustawień**:
    - Wtyczka zapisuje wybrane przez administratora ustawienia w bazie danych WordPressa. Te ustawienia są później wykorzystywane do generowania dynamicznego przycisku.
6. **Wyświetlanie przycisku na stronie**:
    - Na podstawie zapisanych ustawień, wtyczka dodaje kod HTML do stopki strony (hook `wp_footer`), który wyświetla przycisk telefonu.
    - Przycisk jest widoczny tylko w wybranych dniach i godzinach, a także na wybranych urządzeniach (telefony, komputery).
7. **CSS**:
    - Wtyczka generuje dynamiczny styl CSS, który kontroluje m.in. animację mrugania przycisku, dostosowaną do wybranego czasu między mrugnięciami.
8. **Działanie na froncie**:
    - Gdy użytkownik odwiedza stronę i spełnione są warunki (np. odpowiednie urządzenie, godziny pracy), przycisk pojawia się na ekranie.
    - Kliknięcie przycisku otwiera domyślną aplikację telefonu na urządzeniu użytkownika, umożliwiając bezpośrednie nawiązanie połączenia na ustawiony numer.

W skrócie: Wtyczka działa, ładując odpowiednie style i skrypty, wyświetla przycisk telefonu na stronie na podstawie ustawień administratora, a kliknięcie przycisku inicjuje połączenie telefoniczne.
