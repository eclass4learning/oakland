<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Automatically generated strings for Moodle installer
 *
 * Do not edit this file manually! It contains just a subset of strings
 * needed during the very first steps of installation. This file was
 * generated automatically by export-installer.php (which is part of AMOS
 * {@link http://docs.moodle.org/dev/Languages/AMOS}) using the
 * list of strings defined in /install/stringnames.txt.
 *
 * @package   installer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['admindirname'] = 'Admin-map';
$string['availablelangs'] = 'Beschikbare taalpakketten';
$string['chooselanguagehead'] = 'Kies een taal';
$string['chooselanguagesub'] = 'Kies een taal voor de installatie. Deze taal zal ook als standaardtaal voor de site gebruikt worden, maar die instelling kun je later nog wijzigen.';
$string['clialreadyconfigured'] = 'Het configuratiebestand config.php bestaat al. Maak aub gebruik van admin/cli/install_database.php als je Totara voor deze site wenst te installeren.';
$string['clialreadyinstalled'] = 'Het configuratiebestand config.php bestaat al. Maak aub gebruik van admin/cli/install_database.php als je Totara voor deze site wenst te upgraden.';
$string['cliinstallheader'] = 'Totara {$a} command line installatieprogramma';
$string['databasehost'] = 'Databank host:';
$string['databasename'] = 'Datanbanknaam:';
$string['databasetypehead'] = 'Kies databankdriver';
$string['dataroot'] = 'Gegevensmap';
$string['datarootpermission'] = 'Toestemming datamappen';
$string['dbprefix'] = 'Tabelvoorvoegsel';
$string['dirroot'] = 'Totara-map';
$string['environmenthead'] = 'Omgeving controleren ...';
$string['environmentsub2'] = 'Elke Totara vraagt een minimale PHP-versie en een aantal vereiste PHP-extenties.
De volledige installatie-omgeving wordt gecontroleerd voor elke installatie en upgrade. Contacteer je server beheerder als je niet weet hoe je de juiste PHP-versie moet installeren of PHP-extenties moet inschakelen.';
$string['errorsinenvironment'] = 'Fouten in je omgeving!';
$string['installation'] = 'Installatie';
$string['langdownloaderror'] = 'De taal "{$a}" kon niet worden gedownload. Het installatieproces gaat verder in het Engels.';
$string['memorylimithelp'] = '<p>De PHP-geheugenlimiet van je server is ingesteld op {$a}.</p>
<p>Hierdoor kan Totara geheugenproblemen krijgen, vooral als je veel modules installeert en/of veel gebruikers hebt.</p>

<p>We raden je aan PHP met een hogere geheugenlimiet te configureren indien mogelijk, bijvoorbeeld 40Mb. Er zijn verschillende mogelijkheden om dat te doen. Je kunt proberen:
<ol>
<li>Indien je kunt PHP hercompileren met <i>--enable-memory-limit</i>.
Hierdoor kan Totara zelf zijn geheugenlimiet instellen.
<li>Als je toegang hebt tot het php.ini-bestand, kun je de <b>memory_limit</b>-instelling veranderen naar bv 40Mb. Als je geen toegang hebt kun je je systeembeheerder vragen dit voor je te wijzigen.</li>
<li>Op sommige PHP-servers kun je een .htaccess-bestand maken in de Totara-map met volgende lijn: <p><blockquote>php_value memory_limit 40M</blockquote></p>
<p>Opgelet: op sommige servers zal dit verhinderen dat <b>alle</b> PHP-bestanden uitgevoerd worden. (je zult foutmeldingen zien wanneer je naar php-pagina\'s kijkt) Je zult dan het .htaccess-bestand moeten verwijderen.</li>
</ol>';
$string['paths'] = 'Paden';
$string['pathserrcreatedataroot'] = 'Datamap ({$a->dataroot}) kan niet aangemaakt worden door het installatiescript';
$string['pathshead'] = 'Bevestig paden';
$string['pathsrodataroot'] = 'De dataroot map is niet beschrijfbaar.';
$string['pathsroparentdataroot'] = 'De bovenliggende map ({$a->parent}) is niet beschrijfbaar. De datamap ({$a->dataroot}) kan niet aangemaakt worden door het installatiescript';
$string['pathssubadmindir'] = 'Sommige webhosts gebruiken /admin als een speciale url om toegang tot bijvoorbeeld een controlepaneel te krijgen. Dit kan conflicten veroorzaken met de standaardlocatie van de Totara admin scripts. Je kunt dit oplossen door de admin map van Totara te hernoemen en de nieuwe naam hier te zetten. Bijvoorbeeld <em>moodleadmin</em>. Dat zal de admin links in Totara herstellen.';
$string['pathssubdataroot'] = '<p>Een map waar Totara geüploade bestanden kan bewaren.</p>
<p>Deze map moet leesbaar en BESCHRIJFBAAR zijn door de webserver gebruiker (gewoonlijk \'nobody\', \'apache\' of www-data\').</p>
<p>Ze mag niet rechtstreeks toegankelijk zijn vanaf het internet.</p>
<p>Als de map niet bestaat, zal het installatieproces ze proberen te maken.</p>';
$string['pathssubdirroot'] = '<p>Het volledig pad naar de Totara-code.</p>';
$string['pathssubwwwroot'] = '<p>Het volledige webadres waarlangs de toegang naar Totara zal gebeuren - het adres dat gebruikers zullen ingeven om Totara te bereiken.</p>
<p>Het is niet mogelijk toegang tot Totara te krijgen via meerdere adressen. Als je site meerdere publieke adressen heeft, dan zul je permanente verwijzingen moeten opzetten voor al die andere adressen.</p>
<p>Als je site zowel vanaf het internet als vanaf een intranet toegankelijk is, zet dat het internetadres hier.</p>
<p>Als het adres niet juist is, wijzig dan de URL in je browser en herstart de installatie.</p>';
$string['pathsunsecuredataroot'] = 'De plaats van de datamap is niet veilig.';
$string['pathswrongadmindir'] = 'De adminmap bestaat niet';
$string['phpextension'] = '{$a} PHP-extentie';
$string['phpversion'] = 'PHP-versie';
$string['phpversionhelp'] = '<p>Totara heeft minstens PHP-versie 4.3.0 of 5.1.0 nodig (5.0.x heeft veel bekende problemen).</p> <p>De huidige versie op je server is {$a}</p>
<p>Je moet PHP upgraden of verhuizen naar een host met een nieuwere versie van PHP!<br />(Als je 5.0.x draait, kun je ook downgraden naar versie 4.4.x)</p>';
$string['welcomep10'] = '{$a->installername} ({$a->installerversion})';
$string['welcomep20'] = 'Je krijgt deze pagina te zien omdat je met succes het <strong>{$a->packname} {$a->packversion}</strong> packet op je computer gezet en gestart hebt. Proficiat!';
$string['welcomep30'] = 'Deze uitgave van <strong>{$a->installername}</strong> bevat de software die nodig is om een omgeving te creëren waarin <strong>Totara</strong> zal werken, namelijk:';
$string['welcomep40'] = 'Dit pakket bevat ook <strong>Totara {$a->moodlerelease} ({$a->moodleversion})</strong>.';
$string['welcomep50'] = 'Het gebruik van alle programma\'s in dit pakket wordt geregeld door hun respectievelijke licenties. Het complete <strong>{$a->installername}</strong> pakket is
<a href="http://www.opensource.org/docs/definition_plain.html">open source</a> en wordt verdeeld onder de <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a> licentie.';
$string['welcomep60'] = 'De volgende pagina\'s leiden je door een aantal makkelijk te volgen stappen om <strong>Totara</strong> te installeren op je computer. Je kunt de standaardinstellingen overnemen of, optioneel, ze aanpassen aan je noden.';
$string['welcomep70'] = 'Klik op de "volgende"-knop om verder te gaan met de installatie van <strong>Totara</strong>';
$string['wwwroot'] = 'Web adres';
