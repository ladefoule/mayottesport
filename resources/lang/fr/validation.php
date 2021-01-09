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

    'accepted' => "L'attribut doit être accepté.",
    'active_url' => "L'attribut n'est pas une URL valide",
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'Le champ :attribute ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
    'alpha_num' => 'Le champ :attribute ne peut contenir que des lettres et des chiffres.',
    'array' => 'Le champ :attribute doit être un tableau.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => "Le champ de confirmation ne correspond pas.",
    'date' => "La valeur saisie n'est pas une date valide.",
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => "Les champs :attribute et :other doivent être différents.",
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => "Le champ doit être une adresse email valide.",
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => "Le champ sélectionné n'est pas valide.",
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => "La valeur doit être supérieur à :value.",
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => "La valeur doit être supérieur ou égal à :value.",
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'Le champ :attribute doit correspondre à une image.',
    'in' => "Le champ :attribute n'est pas valide.",
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => "La valeur doit être un nombre entier.",
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => "La valeur doit être inférieur à :value.",
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => "La valeur doit être inférieur ou égal à :value.",
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => "La valeur ne peut pas être supérieure à :max.",
        'file' => "Le fichier ne peut pas dépasser :max kilo-octets.",
        'string' => "Le champ ne peut pas dépasser :max caractères.",
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'Le champ :attribute doit être un fichier de type :values.',
    'mimetypes' => 'Le champ :attribute doit être un fichier de type :values.',
    'min' => [
        'numeric' => "La valeur doit être au moins égale à :min.",
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => "Le champ doit comporter au moins :min caractères.",
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => "Le champ doit être un nombre.",
    'password' => "Le mot de passe est incorrect.",
    'present' => 'The :attribute field must be present.',
    'regex' => "Le format du champ :est invalide.",
    'required' => 'Ce champ est obligatoire.',
    'required_if' => 'Le champ :attribute est requis lorsque :other est égale à :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => "Ce champ est requis si le champ :values est saisi.",
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => "Le champ :attribute doit être égal à :size.",
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'Le champ :attribute doit comporter :size caractères.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => "Le champ doit être une chaîne de caractères.",
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => "La valeur saisie éxiste déjà.",
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
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

];
