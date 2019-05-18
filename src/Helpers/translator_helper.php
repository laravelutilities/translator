<?php

if(! function_exists('translator'))
{
    function translator($message, $replace = [], $locale = null)
    {
        $translatorLangFileName = config('translator.lang_filename');
        $appLocale = app()->getLocale();
        $translated = trans(($translatorLangFileName . '.' . $message), $replace, $locale);
        if($translated == $translatorLangFileName. '.' . $message)
        {
            $translator = require(resource_path('lang/'. $appLocale .'/'. $translatorLangFileName . '.php'));
            
            $translator[$message] = $message;
            $content = "<?php \n return " . var_export($translator, true) . ";\n";
            file_put_contents(resource_path('lang/'. $appLocale .'/'. $translatorLangFileName . '.php'), $content, LOCK_EX);
            $translated = $message;
        }
        return $translated;
    }
}
