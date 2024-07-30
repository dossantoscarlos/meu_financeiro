<?php

namespace App\Rules;

use App\Models\TipoDespesa;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class UniqueColumnCustom implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        Log::debug($value);
        $hydrate = mb_strtoupper(trim($value));
        $result = TipoDespesa::whereNome($hydrate)->get() ?? [];

        if ($result === []) {
            Log::debug($result);
            $fail('The :attribute must be unique.');
        }
    }
}
