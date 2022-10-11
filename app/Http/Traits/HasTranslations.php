<?php

namespace App\Http\Traits;

use Spatie\Translatable\HasTranslations as HasTranslationsBase;

trait HasTranslations
{
    use HasTranslationsBase;

    //region Custom Code
    protected $lang = null;

    protected function getLang()
    {
        if (!$this->lang) $this->lang = view()->shared('lang', config('translatable.fallback_locale'));

        return $this->lang;
    }

    public function a($key)
    {
        return $this->getTranslation($key, $this->getLang());
    }

    //endregion

    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = false)
    {
        $locale = $this->normalizeLocale($key, $locale, $useFallbackLocale);

        $translations = $this->getTranslations($key);

        $translation = $translations[$locale] ?? '';

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $translation);
        }

        return $translation;
    }

    protected function asJson($value)
    {
        return json($value);
    }
}
