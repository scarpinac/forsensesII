<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Il campo :attribute deve essere accettato.',
    'accepted_if' => 'Il campo :attribute deve essere accettato quando :other è :value.',
    'active_url' => 'Il campo :attribute non è un URL valido.',
    'after' => 'Il campo :attribute deve essere una data successiva al :date.',
    'after_or_equal' => 'Il campo :attribute deve essere una data successiva o uguale a :date.',
    'alpha' => 'Il campo :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il campo :attribute può contenere solo lettere, numeri e trattini.',
    'alpha_num' => 'Il campo :attribute può contenere solo lettere e numeri.',
    'array' => 'Il campo :attribute deve essere un array.',
    'ascii' => 'Il campo :attribute deve contenere solo caratteri alfanumerici e simboli.',
    'before' => 'Il campo :attribute deve essere una data precedente al :date.',
    'before_or_equal' => 'Il campo :attribute deve essere una data precedente o uguale a :date.',
    'between' => [
        'array' => 'Il campo :attribute deve avere tra :min e :max elementi.',
        'file' => 'Il campo :attribute deve essere tra :min e :max kilobyte.',
        'numeric' => 'Il campo :attribute deve essere tra :min e :max.',
        'string' => 'Il campo :attribute deve avere tra :min e :max caratteri.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'confirmed' => 'Il campo di conferma per :attribute non corrisponde.',
    'current_password' => 'La password non è corretta.',
    'date' => 'Il campo :attribute non è una data valida.',
    'date_equals' => 'Il campo :attribute deve essere una data uguale a :date.',
    'date_format' => 'Il campo :attribute non corrisponde al formato :format.',
    'decimal' => 'Il campo :attribute deve avere :decimal cifre decimali.',
    'declined' => 'Il campo :attribute deve essere rifiutato.',
    'declined_if' => 'Il campo :attribute deve essere rifiutato quando :other è :value.',
    'different' => 'I campi :attribute e :other devono essere diversi.',
    'digits' => 'Il campo :attribute deve avere :digits cifre.',
    'digits_between' => 'Il campo :attribute deve avere tra :min e :max cifre.',
    'dimensions' => 'Il campo :attribute ha dimensioni di immagine non valide.',
    'distinct' => 'Il campo :attribute ha un valore duplicato.',
    'doesnt_end_with' => 'Il campo :attribute non può terminare con uno dei seguenti: :values.',
    'doesnt_start_with' => 'Il campo :attribute non può iniziare con uno dei seguenti: :values.',
    'email' => 'Il campo :attribute deve essere un indirizzo email valido.',
    'ends_with' => 'Il campo :attribute deve terminare con uno dei seguenti: :values.',
    'enum' => 'Il campo :attribute selezionato non è valido.',
    'exists' => 'Il campo :attribute selezionato non è valido.',
    'file' => 'Il campo :attribute deve essere un file.',
    'filled' => 'Il campo :attribute è obbligatorio.',
    'gt' => [
        'array' => 'Il campo :attribute deve avere più di :value elementi.',
        'file' => 'Il campo :attribute deve essere più di :value kilobyte.',
        'numeric' => 'Il campo :attribute deve essere più di :value.',
        'string' => 'Il campo :attribute deve avere più di :value caratteri.',
    ],
    'gte' => [
        'array' => 'Il campo :attribute deve avere :value elementi o più.',
        'file' => 'Il campo :attribute deve essere :value kilobyte o più.',
        'numeric' => 'Il campo :attribute deve essere :value o più.',
        'string' => 'Il campo :attribute deve avere :value caratteri o più.',
    ],
    'image' => 'Il campo :attribute deve essere un\'immagine.',
    'in' => 'Il campo :attribute selezionato non è valido.',
    'in_array' => 'Il campo :attribute non esiste in :other.',
    'integer' => 'Il campo :attribute deve essere un numero intero.',
    'ip' => 'Il campo :attribute deve essere un indirizzo IP valido.',
    'ipv4' => 'Il campo :attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => 'Il campo :attribute deve essere un indirizzo IPv6 valido.',
    'json' => 'Il campo :attribute deve essere una stringa JSON valida.',
    'lowercase' => 'Il campo :attribute deve essere in minuscolo.',
    'lt' => [
        'array' => 'Il campo :attribute deve avere meno di :value elementi.',
        'file' => 'Il campo :attribute deve essere meno di :value kilobyte.',
        'numeric' => 'Il campo :attribute deve essere meno di :value.',
        'string' => 'Il campo :attribute deve avere meno di :value caratteri.',
    ],
    'lte' => [
        'array' => 'Il campo :attribute deve avere :value elementi o meno.',
        'file' => 'Il campo :attribute deve essere :value kilobyte o meno.',
        'numeric' => 'Il campo :attribute deve essere :value o meno.',
        'string' => 'Il campo :attribute deve avere :value caratteri o meno.',
    ],
    'mac_address' => 'Il campo :attribute deve essere un indirizzo MAC valido.',
    'max' => [
        'array' => 'Il campo :attribute non può avere più di :max elementi.',
        'file' => 'Il campo :attribute non può essere più di :max kilobyte.',
        'numeric' => 'Il campo :attribute non può essere più di :max.',
        'string' => 'Il campo :attribute non può avere più di :max caratteri.',
    ],
    'mimes' => 'Il campo :attribute deve essere un file di tipo: :values.',
    'mimetypes' => 'Il campo :attribute deve essere un file di tipo: :values.',
    'min' => [
        'array' => 'Il campo :attribute deve avere almeno :min elementi.',
        'file' => 'Il campo :attribute deve essere almeno :min kilobyte.',
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'string' => 'Il campo :attribute deve avere almeno :min caratteri.',
    ],
    'missing' => 'Il campo :attribute deve essere mancante.',
    'missing_if' => 'Il campo :attribute deve essere mancante quando :other è :value.',
    'missing_unless' => 'Il campo :attribute deve essere mancante a meno che :other non sia :value.',
    'missing_with' => 'Il campo :attribute deve essere mancante quando :values è presente.',
    'missing_with_all' => 'Il campo :attribute deve essere mancante quando :values sono presenti.',
    'multiple_of' => 'Il campo :attribute deve essere un multiplo di :value.',
    'not_in' => 'Il campo :attribute selezionato non è valido.',
    'not_regex' => 'Il formato del campo :attribute non è valido.',
    'numeric' => 'Il campo :attribute deve essere un numero.',
    'password' => 'La password non è corretta.',
    'present' => 'Il campo :attribute deve essere presente.',
    'prohibited' => 'Il campo :attribute è vietato.',
    'prohibited_if' => 'Il campo :attribute è vietato quando :other è :value.',
    'prohibited_unless' => 'Il campo :attribute è vietato a meno che :other non sia in :values.',
    'prohibits' => 'Il campo :attribute vieta :other dall\'essere presente.',
    'regex' => 'Il formato del campo :attribute non è valido.',
    'required' => 'Il campo :attribute è obbligatorio.',
    'required_array_keys' => 'Il campo :attribute deve contenere le chiavi: :values.',
    'required_if' => 'Il campo :attribute è obbligatorio quando :other è :value.',
    'required_unless' => 'Il campo :attribute è obbligatorio a meno che :other non sia in :values.',
    'required_with' => 'Il campo :attribute è obbligatorio quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è obbligatorio quando :values sono presenti.',
    'required_without' => 'Il campo :attribute è obbligatorio quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quando nessuno dei :values è presente.',
    'same' => 'I campi :attribute e :other devono corrispondere.',
    'size' => [
        'array' => 'Il campo :attribute deve contenere :size elementi.',
        'file' => 'Il campo :attribute deve essere :size kilobyte.',
        'numeric' => 'Il campo :attribute deve essere :size.',
        'string' => 'Il campo :attribute deve avere :size caratteri.',
    ],
    'starts_with' => 'Il campo :attribute deve iniziare con uno dei seguenti: :values.',
    'string' => 'Il campo :attribute deve essere una stringa.',
    'timezone' => 'Il campo :attribute deve essere un fuso orario valido.',
    'unique' => 'Il campo :attribute è già stato utilizzato.',
    'uploaded' => 'Il caricamento del campo :attribute è fallito.',
    'uppercase' => 'Il campo :attribute deve essere in maiuscolo.',
    'url' => 'Il campo :attribute deve essere un URL valido.',
    'ulid' => 'Il campo :attribute deve essere un ULID valido.',
    'uuid' => 'Il campo :attribute deve essere un UUID valido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

    // Menu Form Request Validation Messages
    'menu' => [
        'validation' => [
            'descricao' => [
                'required' => 'Il campo descrizione è obbligatorio.',
                'string' => 'Il campo descrizione deve essere un testo.',
                'max' => 'Il campo descrizione non può avere più di 25 caratteri.',
            ],
            'icone' => [
                'string' => 'Il campo icona deve essere un testo.',
                'max' => 'Il campo icona non può avere più di 50 caratteri.',
            ],
            'rota' => [
                'required' => 'Il campo rotta è obbligatorio.',
                'string' => 'Il campo rotta deve essere un testo.',
                'max' => 'Il campo rotta non può avere più di 80 caratteri.',
            ],
            'menuPai_id' => [
                'exists' => 'Il menu padre selezionato non è valido.',
            ],
            'permissao_id' => [
                'required' => 'Il campo permesso è obbligatorio.',
                'exists' => 'Il permesso selezionato non è valido.',
            ],
            'situacao_id' => [
                'required' => 'Il campo situazione è obbligatorio.',
                'exists' => 'La situazione selezionata non è valida.',
            ],
        ],
    ],
    
    // Permission Form Request Validation Messages
    'permission' => [
        'validation' => [
            'descricao' => [
                'required' => 'Il campo descrizione è obbligatorio.',
                'string' => 'Il campo descrizione deve essere un testo.',
                'max' => 'Il campo descrizione non può avere più di 80 caratteri.',
            ],
        ],
    ],
    
    // Utente Form Request Validation Messages
    'usuario' => [
        'validation' => [
            'name' => [
                'required' => 'Il campo nome è obbligatorio.',
                'string' => 'Il campo nome deve essere un testo.',
                'max' => 'Il campo nome non può avere più di 255 caratteri.',
            ],
            'email' => [
                'required' => 'Il campo e-mail è obbligatorio.',
                'string' => 'Il campo e-mail deve essere un testo.',
                'email' => 'Il campo e-mail deve essere un indirizzo email valido.',
                'max' => 'Il campo e-mail non può avere più di 255 caratteri.',
                'unique' => 'Questo e-mail è già in uso.',
            ],
            'password' => [
                'required' => 'Il campo password è obbligatorio.',
                'string' => 'Il campo password deve essere un testo.',
                'min' => 'La password deve avere almeno 8 caratteri.',
                'confirmed' => 'La conferma della password non corrisponde.',
            ],
            'admin' => [
                'required' => 'Il campo amministratore è obbligatorio.',
                'integer' => 'Il campo amministratore deve essere un numero.',
                'in' => 'Selezionare un\'opzione valida per amministratore.',
            ],
            'avatar' => [
                'mimes' => 'L\'avatar deve essere un\'immagine nei formati: JPEG, PNG, JPG, GIF o WebP.',
                'max' => 'L\'avatar non può essere più grande di 2MB.',
            ],
        ],
    ],
    
    // Access Level Form Request Validation Messages
    'access_level' => [
        'validation' => [
            'descricao' => [
                'required' => 'Il campo descrizione è obbligatorio.',
                'string' => 'Il campo descrizione deve essere un testo.',
                'max' => 'Il campo descrizione non può avere più di 80 caratteri.',
            ],
            'permissoes' => [
                'array' => 'Il campo permessi deve essere un array.',
                'integer' => 'Cgni permesso deve essere un numero intero.',
                'exists' => 'Uno dei permessi selezionati non è valido.',
            ],
        ],
    ],
    
    // API Form Request Validation Messages
    'api' => [
        'validation' => [
            'api_id' => [
                'required' => 'Il campo tipo API è obbligatorio.',
                'exists' => 'Il tipo API selezionato non è valido.',
            ],
            'credencial' => [
                'required' => 'Il campo credenziale è obbligatorio.',
                'string' => 'Il campo credenziale deve essere una stringa.',
            ],
            'situacao_id' => [
                'required' => 'Il campo situazione è obbligatorio.',
                'exists' => 'La situazione selezionata non è valida.',
            ],
        ],
    ],

    // Parameter Form Request Validation Messages
    'parametro' => [
        'validation' => [
            'nome' => [
                'required' => 'Il campo nome è obbligatorio.',
                'string' => 'Il campo nome deve essere una stringa.',
                'max' => 'Il campo nome non può avere più di 80 caratteri.',
            ],
            'descricao' => [
                'required' => 'Il campo descrizione è obbligatorio.',
                'string' => 'Il campo descrizione deve essere una stringa.',
            ],
            'tipo_id' => [
                'required' => 'Il campo tipo è obbligatorio.',
                'exists' => 'Il tipo selezionato non è valido.',
            ],
            'valor' => [
                'required' => 'Il campo valore è obbligatorio.',
                'string' => 'Il campo valore deve essere una stringa.',
            ],
        ],
    ],

    // Notification Form Request Validation Messages
    'notification' => [
        'validation' => [
            'title' => [
                'required' => 'Il campo titolo è obbligatorio.',
                'string' => 'Il campo titolo deve essere una stringa.',
                'max' => 'Il campo titolo non può avere più di 50 caratteri.',
            ],
            'message' => [
                'required' => 'Il campo messaggio è obbligatorio.',
                'string' => 'Il campo messaggio deve essere una stringa.',
            ],
            'notification_type' => [
                'required' => 'Il campo tipo notifica è obbligatorio.',
                'exists' => 'Il tipo di notifica selezionato non è valido.',
            ],
            'sent_notification_to' => [
                'required' => 'Il campo destinatario è obbligatorio.',
                'exists' => 'Il destinatario selezionato non è valido.',
            ],
            'icon' => [
                'string' => 'Il campo icona deve essere una stringa.',
                'max' => 'Il campo icona non può avere più di 30 caratteri.',
            ],
            'send_at' => [
                'required' => 'Il campo data/ora invio è obbligatorio.',
                'date' => 'Il campo data/ora invio deve essere una data valida.',
                'after' => 'La data/ora invio deve essere successiva alla data/ora attuale.',
            ],
            'expired_at' => [
                'date' => 'Il campo data scadenza deve essere una data valida.',
                'after' => 'La data scadenza deve essere successiva alla data/ora attuale.',
            ],
            'send_to' => [
                'required' => 'Il campo invia a è obbligatorio.',
                'string' => 'Il campo invia a deve essere una stringa.',
            ],
            'users' => [
                'required_if' => 'Il campo utenti è obbligatorio quando il destinatario è "Utenti Specifici".',
                'array' => 'Il campo utenti deve essere un array.',
                'exists' => 'Uno o più utenti selezionati non sono validi.',
            ],
            'profiles' => [
                'required_if' => 'Il campo profili è obbligatorio quando il destinatario è "Profili Specifici".',
                'array' => 'Il campo profili deve essere un array.',
                'exists' => 'Uno o più profili selezionati non sono validi.',
            ],
        ],
    ],
];

