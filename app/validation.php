<?php
function app_strlen(string $value): int {
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

function validate_person_name(string $value, string $label): ?string {
    $value = trim($value);
    if ($value === '') {
        return sprintf('%s je povinné.', $label);
    }
    if (app_strlen($value) > 100) {
        return sprintf('%s môže mať najviac 100 znakov.', $label);
    }
    if (!preg_match('/^[\p{L}\p{M}][\p{L}\p{M}\s\-\']*$/u', $value)) {
        return sprintf('%s obsahuje nepovolené znaky.', $label);
    }
    return null;
}

function validate_email_address(string $email): ?string {
    $email = trim($email);
    if ($email === '') {
        return 'E-mail je povinný.';
    }
    if (app_strlen($email) > 190 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Neplatný e-mail.';
    }
    return null;
}

function validate_password_value(string $password): ?string {
    if ($password === '') {
        return 'Heslo je povinné.';
    }
    if (strlen($password) < 8) {
        return 'Heslo musí mať aspoň 8 znakov.';
    }
    if (strlen($password) > 255) {
        return 'Heslo je príliš dlhé.';
    }
    return null;
}

function validate_totp_value(string $code): ?string {
    return preg_match('/^\d{6}$/', $code) ? null : 'Zadajte 6-miestny kód.';
}
