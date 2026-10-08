<?php

namespace App\Support;

use Illuminate\Contracts\Hashing\Hasher;

class Md5Hasher implements Hasher
{
    public function info(mixed $hashedValue): array
    {
        if (is_string($hashedValue) && preg_match('/\A[a-f0-9]{32}\z/', $hashedValue)) {
            return ['algo' => 'md5', 'algoName' => 'md5', 'options' => []];
        }

        return password_get_info((string) $hashedValue);
    }

    public function make(#[\SensitiveParameter] mixed $value, array $options = []): string
    {
        return md5((string) $value);
    }

    public function check(#[\SensitiveParameter] mixed $value, mixed $hashedValue, array $options = []): bool
    {
        return is_string($hashedValue)
            && preg_match('/\A[a-f0-9]{32}\z/', $hashedValue) === 1
            && hash_equals($hashedValue, $this->make($value));
    }

    public function needsRehash(mixed $hashedValue, array $options = []): bool
    {
        return $this->info($hashedValue)['algoName'] !== 'md5';
    }
}
