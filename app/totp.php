<?php

use OTPHP\TOTP;

function base32_encode_rfc4648(string $data): string {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $bits = '';
    $len = strlen($data);

    for ($i = 0; $i < $len; $i++) {
        $bits .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
    }

    $out = '';
    for ($i = 0; $i < strlen($bits); $i += 5) {
        $chunk = substr($bits, $i, 5);
        if (strlen($chunk) < 5) {
            $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
        }
        $out .= $alphabet[bindec($chunk)];
    }

    return $out;
}

function totp_generate_secret(int $bytes = 20): string {
    return base32_encode_rfc4648(random_bytes($bytes));
}

function totp_make(string $secret, string $label): TOTP {
    $label = trim($label);
    if ($label === '') {
        $label = 'WEBTE2';
    }

    $totp = TOTP::create($secret);
    $totp->setLabel($label);
    $totp->setIssuer('WEBTE2');

    return $totp;
}

function totp_verify(string $secret, string $code): bool {
    $code = preg_replace('/\s+/', '', $code);

    if (!preg_match('/^\d{6}$/', $code)) {
        return false;
    }

    $totp = TOTP::create($secret);
    $totp->setLabel('WEBTE2');
    $totp->setIssuer('WEBTE2');

    $now = time();

    return $totp->verify($code, $now - 30)
        || $totp->verify($code, $now)
        || $totp->verify($code, $now + 30);
}