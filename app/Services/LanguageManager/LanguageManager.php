<?php

namespace App\Services\LanguageManager;

use App\Models\Language;
use Illuminate\Support\Facades\Request;

class LanguageManager
{
    private $locales;
    private $locale;
    private $requestLocale;
    private $defaultLocale;
    private $canonicalUrl;
    private $currentLocale;

    private function sessionKey()
    {
        return 'locale';
    }

    public function getPrefix()
    {
        $this->locales = Language::getIsos();
        $this->defaultLocale = $this->locales[settings('default_language', 2)];
        $firstSegment = Request::segment(1, null);
        if (preg_match('/^[a-z]{2}$/', $firstSegment)) {
            if (!in_array($firstSegment, $this->locales)) {
                $this->locale = $this->defaultLocale;

                return $this->redirect('/');

            }

            $this->requestLocale = $firstSegment;
            $this->locale = $this->requestLocale;
        } else {
            $this->requestLocale = null;
            $this->locale = $this->defaultLocale;
        }
        app()->setLocale($this->locale);

        return $this->requestLocale;
    }

    public function middleware()
    {
        $this->currentLocale = session($this->sessionKey());
        if (!$this->requestLocale) {
            if (!$this->currentLocale || !in_array($this->currentLocale, $this->locales)) $this->setSession($this->defaultLocale);
            if ($this->currentLocale != $this->defaultLocale) {
                return $this->redirect($this->getUrlWithLocale($this->currentLocale));
            }
        } else {
            if ($this->requestLocale == $this->defaultLocale) {
                if ($this->currentLocale !== $this->defaultLocale) $this->setSession($this->defaultLocale);

                return $this->redirect($this->getCanonicalUrl());
            } else if ($this->currentLocale != $this->requestLocale) {
                $this->setSession($this->requestLocale);
            }
        }

        return false;
    }

    public function setLocaleWithoutPrefix()
    {
        if (empty($this->defaultLocale)) return false;
        $this->currentLocale = session($this->sessionKey());
        if (!$this->currentLocale || !in_array($this->currentLocale, $this->locales)) $locale = $this->defaultLocale;
        else $locale = $this->currentLocale;
        app()->setLocale($locale);

        return $locale;
    }

    private function setSession($lang)
    {
        $this->currentLocale = $lang;
        session([$this->sessionKey() => $lang]);
        session()->save();
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    private function redirect($url)
    {
        session()->reflash();

        return redirect($url);
    }

    public function getCanonicalUrl()
    {
        if (!$this->canonicalUrl) {
            $url = Request::getPathInfo() . (Request::getQueryString() ? ('?' . Request::getQueryString()) : '');
            if ($this->requestLocale) {
                $url = preg_replace('/^\/[^\/]+\/?/', '/', $url);
            }
            $this->canonicalUrl = $url;
        }

        return $this->canonicalUrl;
    }

    public function getUrlWithLocale($locale)
    {
        return '/' . $locale . $this->getCanonicalUrl();
    }
}
