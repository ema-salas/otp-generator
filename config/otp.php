<?php

return [
    /*
     * The password generator option allows you to decide
     * which generator implementation to be used when
     * generating new passwords.
     *
     * Here are the options:
     *  - string
     *  - numeric
     *  - numeric-no-0
     */

    'password_generator' => 'string',

    /*
     * The expiry time of the tokens in minutes.
     */

    'expires' => 15, // in minutes.

    /*
     * The length of the generated token.
     */

    'length' => 6, // in characters.
];