<?php

return [

     /*
     |------------------------------------------------ -------------------------
     | Jezičke linije za validaciju
     |------------------------------------------------ -------------------------
     |
     | Sljedeće jezičke linije sadrže zadane poruke o grešci koje koristi
     | klasa validatora. Neka od ovih pravila imaju više verzija npr
     | kao pravila veličine. Ovdje možete podesiti svaku od ovih poruka.
     |
     */

    'accepted' => 'Polje :attribute mora biti prihvaćeno.',
    'accepted_if' => 'Polje :attribute mora biti prihvaćeno kada je :other :value.',
    'active_url' => 'Polje :attribute mora biti ispravan URL.',
    'after' => 'Polje :attribute mora biti datum nakon :date.',
    'after_or_equal' => 'Polje :attribute mora biti datum nakon ili jednako :date.',
    'alpha' => 'Polje :attribute mora sadržavati samo slova.',
    'alpha_dash' => 'Polje :attribute mora sadržavati samo slova, brojeve, crtice i donje crte.',
    'alpha_num' => 'Polje :attribute mora sadržavati samo slova i brojeve.',
    'array' => 'Polje :attribute mora biti niz.',
    'ascii' => 'Polje :attribute mora sadržavati samo jednobajtne alfanumeričke znakove i simbole.',
    'before' => 'Polje :attribute mora biti datum prije :date.',
    'before_or_equal' => 'Polje :attribute mora biti datum prije ili jednako :date.',
    'between' => [
        'array' => 'Polje :attribute mora imati između :min i :max stavki.',
        'file' => 'Polje :attribute mora biti između :min i :max kilobajta.',
        'numeric' => 'Polje :attribute mora biti između :min i :max.',
        'string' => 'Polje :attribute mora biti između :min i :max znakova.',
    ],
    'boolean' => 'Polje :attribute mora biti tačno ili netačno.',
    'can' => 'Polje :attribute sadrži neovlaštenu vrijednost.',
    'confirmed' => 'Potvrda polja :attribute se ne poklapa.',
    'contains' => 'Polju :attribute nedostaje potrebna vrijednost.',
    'current_password' => 'Lozinka je netačna.',
    'date' => 'Polje :attribute mora biti ispravan datum.',
    'date_equals' => 'Polje :attribute mora biti datum jednak :date.',
    'date_format' => 'Polje :attribute mora odgovarati formatu :format.',
    'decimal' => 'Polje :attribute mora imati :decimal decimalnih mjesta.',
    'declined' => 'Polje :attribute mora biti odbijeno.',
    'declined_if' => 'Polje :attribute mora biti odbijeno kada je :other :value.',
    'different' => 'Polje :attribute i :other moraju biti različiti.',
    'digits' => 'Polje :attribute mora biti :digits cifre.',
    'digits_between' => 'Polje :attribute mora biti između :min i :max cifara.',
    'dimensions' => 'Polje :attribute ima nevažeće dimenzije slike.',
    'distinct' => 'Polje :attribute ima dupliranu vrijednost.',
    'doesnt_end_with' => 'Polje :attribute ne smije završavati sa jednim od sljedećih: :values.',
    'doesnt_start_with' => 'Polje :attribute ne smije početi sa jednim od sljedećih: :values.',
    'email' => 'Polje :attribute mora biti važeća adresa e-pošte.',
    'ends_with' => 'Polje :attribute mora završiti sa jednim od sljedećih: :values.',
    'enum' => 'Odabrani :attribute je nevažeći.',
    'exists' => 'Odabrani :attribute je nevažeći.',
    'extensions' => 'Polje :attribute mora imati jednu od sljedećih ekstenzija: :values.',
    'file' => 'Polje :attribute mora biti datoteka.',
    'filled' => 'Polje :attribute mora imati vrijednost.',
    'gt' => [
        'array' => 'Polje :attribute mora imati više od :value stavki.',
        'file' => 'Polje :attribute mora biti veće od :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti veće od :value.',
        'string' => 'Polje :attribute mora biti veće od :value znakova.',
    ],
    'gte' => [
        'array' => 'Polje :attribute mora imati stavke :value ili više.',
        'file' => 'Polje :attribute mora biti veće ili jednako :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti veće ili jednako :value.',
        'string' => 'Polje :attribute mora biti veće ili jednako znakovima :value.',
    ],
    'hex_color' => 'Polje :attribute mora biti važeća heksadecimalna boja.',
    'image' => 'Polje :attribute mora biti slika.',
    'in' => 'Odabrani :attribute je nevažeći.',
    'in_array' => 'Polje :attribute mora postojati u :other.',
    'integer' => 'Polje :attribute mora biti cijeli broj.',
    'ip' => 'Polje :attribute mora biti važeća IP adresa.',
    'ipv4' => 'Polje :attribute mora biti važeća IPv4 adresa.',
    'ipv6' => 'Polje :attribute mora biti važeća IPv6 adresa.',
    'json' => 'Polje :attribute mora biti važeći JSON niz.',
    'list' => 'Polje :attribute mora biti lista.',
    'lowercase' => 'Polje :attribute mora biti malim slovima.',
    'lt' => [
        'array' => 'Polje :attribute mora imati manje od :value stavki.',
        'file' => 'Polje :attribute mora biti manje od :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti manje od :value.',
        'string' => 'Polje :attribute mora biti manje od :value znakova.',
    ],
    'lte' => [
        'array' => 'Polje :attribute ne smije imati više od :value stavki.',
        'file' => 'Polje :attribute mora biti manje od ili jednako :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti manje ili jednako :value.',
        'string' => 'Polje :attribute mora biti manje ili jednako znakovima :value.',
    ],
    'mac_address' => 'Polje :attribute mora biti važeća MAC adresa.',
    'max' => [
        'array' => 'Polje :attribute ne smije imati više od :max stavki.',
        'file' => 'Polje :attribute ne smije biti veće od :max kilobajta.',
        'numeric' => 'Polje :attribute ne smije biti veće od :max.',
        'string' => 'Polje :attribute ne smije biti veće od :max znakova.',
    ],
    'max_digits' => 'Polje :attribute ne smije imati više od :max cifara.',
    'mimes' => 'Polje :attribute mora biti datoteka tipa: :values.',
    'mimetypes' => 'Polje :attribute mora biti datoteka tipa: :values.',
    'min' => [
        'array' => 'Polje :attribute mora imati najmanje :min stavki.',
        'file' => 'Polje :attribute mora biti najmanje :min kilobajta.',
        'numeric' => 'Polje :attribute mora biti najmanje :min.',
        'string' => 'Polje :attribute mora imati najmanje :min znakova.',
    ],
    'min_digits' => 'Polje :attribute mora imati najmanje :min cifara.',
    'missing' => 'Polje :attribute mora nedostajati.',
    'missing_if' => 'Polje :attribute mora nedostajati kada je :other :value.',
    'missing_unless' => 'Polje :attribute mora nedostajati osim ako :other nije :value.',
    'missing_with' => 'Polje :attribute mora nedostajati kada je :values prisutno.',
    'missing_with_all' => 'Polje :attribute mora nedostajati kada su prisutne :vrijednosti.',
    'multiple_of' => 'Polje :attribute mora biti višestruko od :value.',
    'not_in' => 'Odabrani :attribute je nevažeći.',
    'not_regex' => 'Format polja :attribute je nevažeći.',
    'numeric' => 'Polje :attribute mora biti broj.',
    'password' => [
        'slova' => 'Polje :attribute mora sadržavati najmanje jedno slovo.',
        'mixed' => 'Polje :attribute mora sadržavati najmanje jedno veliko i jedno malo slovo.',
        'numbers' => 'Polje :attribute mora sadržavati najmanje jedan broj.',
        'symbols' => 'Polje :attribute mora sadržavati najmanje jedan simbol.',
        'uncompromised' => 'Dati :attribute se pojavio u curenju podataka. Molimo odaberite drugi :attribute.',
    ],
    'present' => 'Polje :attribute mora biti prisutno.',
    'present_if' => 'Polje :attribute mora biti prisutno kada je :other :value.',
    'present_unless' => 'Polje :attribute mora biti prisutno osim ako :other nije :value.',
    'present_with' => 'Polje :attribute mora biti prisutno kada je :values prisutno.',
    'present_with_all' => 'Polje :attribute mora biti prisutno kada su prisutne :values.',
    'prohibited' => 'Polje :attribute je zabranjeno.',
    'prohibited_if' => 'Polje :attribute je zabranjeno kada je :other :value.',
    'prohibited_unless' => 'Polje :attribute je zabranjeno osim ako :other nije u :values.',
    'prohibits' => 'Polje :attribute zabranjuje prisustvo :other.',
    'regex' => 'Format polja :attribute je nevažeći.',
    'required' => 'Polje :attribute je obavezno.',
    'required_array_keys' => 'Polje :attribute mora sadržavati unose za: :values.',
    'required_if' => 'Polje :attribute je potrebno kada je :other :value.',
    'required_if_accepted' => 'Polje :attribute je obavezno kada je :other prihvaćeno.',
    'required_if_declined' => 'Polje :attribute je obavezno kada je :other odbijeno.',
    'required_unless' => 'Polje :attribute je obavezno osim ako :other nije u :values.',
    'required_with' => 'Polje :attribute je obavezno kada je :values prisutno.',
    'required_with_all' => 'Polje :attribute je obavezno kada su prisutne :vrijednosti.',
    'required_without' => 'Polje :attribute je obavezno kada :values nije prisutno.',
    'required_without_all' => 'Polje :attribute je obavezno kada nijedna od :vrijednosti nije prisutna.',
    'same' => 'Polje :attribute mora odgovarati :other.',
    'size' => [
        'array' => 'Polje :attribute mora sadržavati stavke :size.',
        'file' => 'Polje :attribute mora biti :size kilobajta.',
        'numeric' => 'Polje :attribute mora biti :size.',
        'string' => 'Polje :attribute mora biti :size znakova.',
    ],
    'starts_with' => 'Polje :attribute mora početi sa jednim od sljedećih: :values.',
    'string' => 'Polje :attribute mora biti string.',
    'timezone' => 'Polje :attribute mora biti važeća vremenska zona.',
    'unique' => 'Atribut :atribut je već zauzet.',
    'uploaded' => 'Učitavanje :attributa nije uspjelo.',
    'uppercase' => 'Polje :attribute mora biti velikim slovima.',
    'url' => 'Polje :attribute mora biti ispravan URL.',
    'ulid' => 'Polje :attribute mora biti važeći ULID.',
    'uuid' => 'Polje :attribute mora biti važeći UUID.',

    /*
     |------------------------------------------------ -------------------------
     | Prilagođene jezičke linije za validaciju
     |------------------------------------------------ -------------------------
     |
     | Ovdje možete specificirati prilagođene poruke validacije za atribute koristeći
     | konvencija "attribute.rule" za imenovanje linija. Ovo ga čini brzim
     | specificirajte specifičnu liniju prilagođenog jezika za dato pravilo atributa.
     |
     */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |------------------------------------------------ -------------------------
    | Prilagođeni atributi validacije
    |------------------------------------------------ -------------------------
    |
    | Sljedeće jezičke linije se koriste za zamjenu našeg čuvara mjesta atributa
    | sa nečim jednostavnijim za čitanje kao što je "e-mail adresa".
    | od "e-pošte". Ovo nam jednostavno pomaže da našu poruku učinimo izražajnijom.
    |
    */

    'attributes' => [],
];
