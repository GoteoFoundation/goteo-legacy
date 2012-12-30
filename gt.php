<?php
$locale="nl_NL";
$domain="messages";

\setlocale(\LC_TIME, $locale);
\putenv("LANG={$locale}");
\setlocale(LC_ALL, $locale);

// configure settext domain
\bindtextdomain($domain, "locale");
\bind_textdomain_codeset($domain, 'UTF-8');
\textdomain($domain);

?>
<h1><?= _("Historial envios"); ?></h1>
<h1><?= _("Categorias e Intereses"); ?></h1>
<h1><?= _("Listado de usuarios"); ?></h1>
<h1><?= _("Gestión de retornos colectivos cumplidos"); ?></h1>
<h1><?= _("Actividad reciente"); ?></h1>
<h1><?= _("Gestión de campañas"); ?></h1>
