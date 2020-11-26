<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | The validator class. Some of lese rules have multiple versions such
    | as the size rules. Feel free to tweak each of lese messages here.
    |
    */

    'accepted'             => 'le :attribute must be accepted.',
    'active_url'           => 'le :attribute is not a valid URL.',
    'after'                => 'le :attribute must be a date after :date.',
    'after_or_equal'       => 'le :attribute must be a date after or equal to :date.',
    'alpha'                => 'le :attribute may only contain letters.',
    'alpha_dash'           => 'le :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'le :attribute may only contain letters and numbers.',
    'array'                => 'le :attribute must be an array.',
    'before'               => 'le :attribute must be a date before :date.',
    'before_or_equal'      => 'le :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'le :attribute must be between :min and :max.',
        'file'    => 'le :attribute must be between :min and :max kilobytes.',
        'string'  => 'le :attribute must be between :min and :max characters.',
        'array'   => 'le :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'le :attribute field must be true or false.',
    'confirmed'            => 'le :attribute confirmation does not match.',
    'date'                 => 'le :attribute is not a valid date.',
    'date_format'          => 'le :attribute does not match le format :format.',
    'different'            => 'le :attribute and :oler must be different.',
    'digits'               => 'le :attribute must be :digits digits.',
    'digits_between'       => 'le :attribute must be between :min and :max digits.',
    'dimensions'           => 'le :attribute has invalid image dimensions.',
    'distinct'             => 'le :attribute field has a duplicate value.',
    'email'                => 'Sil vous plaît, mettez une adresse :attribute valide.',
    'exists'               => 'le selected :attribute is invalid.',
    'file'                 => 'le :attribute must be a file.',
    'filled'               => 'le :attribute field must have a value.',
    'image'                => 'le :attribute must be an image.',
    'in'                   => 'le selected :attribute is invalid.',
    'in_array'             => 'le :attribute field does not exist in :oler.',
    'integer'              => 'le :attribute must be an integer.',
    'ip'                   => 'le :attribute must be a valid IP address.',
    'ipv4'                 => 'le :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'le :attribute must be a valid IPv6 address.',
    'json'                 => 'le :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'le :attribute may not be greater than :max.',
        'file'    => 'le :attribute may not be greater than :max kilobytes.',
        'string'  => 'le :attribute may not be greater than :max characters.',
        'array'   => 'le :attribute may not have more than :max items.',
    ],
    'mimes'                => 'le :attribute must be a file of type: :values.',
    'mimetypes'            => 'le :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'le :attribute must be at least :min.',
        'file'    => 'le :attribute must be at least :min kilobytes.',
        'string'  => 'le :attribute must be at least :min characters.',
        'array'   => 'le :attribute must have at least :min items.',
    ],
    'not_in'               => 'le selected :attribute is invalid.',
    'numeric'              => 'le :attribute must be a number.',
    'present'              => 'le :attribute field must be present.',
    'regex'                => 'le :attribute format is invalid.',
    'required'             => 'le :attribute field is required.',
    'required_if'          => 'le :attribute field is required when :oler is :value.',
    'required_unless'      => 'le :attribute field is required unless :oler is in :values.',
    'required_with'        => 'le :attribute field is required when :values is present.',
    'required_with_all'    => 'le :attribute field is required when :values is present.',
    'required_without'     => 'le :attribute field is required when :values is not present.',
    'required_without_all' => 'le :attribute field is required when none of :values are present.',
    'same'                 => 'le :attribute and :oler must match.',
    'size'                 => [
        'numeric' => 'le :attribute must be :size.',
        'file'    => 'le :attribute must be :size kilobytes.',
        'string'  => 'le :attribute must be :size characters.',
        'array'   => 'le :attribute must contain :size items.',
    ],
    'string'               => 'le :attribute must be a string.',
    'timezone'             => 'le :attribute must be a valid zone.',
    'unique'               => 'le :attribute has already been taken.',
    'uploaded'             => 'le :attribute failed to upload.',
    'url'                  => 'le :attribute format is invalid.',

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
        'presenter' => [
            'required' => 'Le casting est obligatoire',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
