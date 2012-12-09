<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Locale Detection class Demo</title>
</head>
<body>

<h1>Demo of the ChooseLocale PHP class</h1>

<pre>

<?php
require_once './ChooseLocale.class.php';

/*
 * Locales on the site
 *
 */

$site = array('es-ES', 'en-US', 'en-US', 'fr', 'ga-IE', 'ru');

/*
 * Create the object and initialize with our supported locales
 * if no array is provided, the array is just 'en'.
 * duplicate locales are filtered out.
 *
 */

$locale = new tinyL10n\ChooseLocale($site);

/*
 * Set fallBack, here we want Russian. If the locale is not on the site,
 * the first locale in the supportedLocales array is default
 *
 */

$locale->setDefaultLocale('ru');

/*
 *  We accept short and long locale codes and map them to the closest
 *  long locale as last resort like en-CA -> en-US, es -> es-ES
 *
 */


$locale->mapLonglocales = true;

echo '<h4>Visitors accept lang headers:</h4>';
var_dump($locale -> HTTPAcceptLang);

echo '<h4>Locales supported on the site:</h4>';
var_dump($locale -> supportedLocales);

echo '<h4>The default locale is:</h4>';
var_dump($locale -> getDefaultLocale());

echo '<h4>The chosen locale for the visitor is:</h4>';
var_dump($locale -> getCompatibleLocale());

?>

</pre>
</body>
</html>
