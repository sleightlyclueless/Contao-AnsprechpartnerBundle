<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

 // Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann. Gibt es auch auf selber Ebene in der 'ContauManagerPlugin.php'. Diese Datei wird mit seinem Namespace von der /src/DependencyInjection/ContaoManagerPlugin.php aufgerufen.
namespace ixtensa\AnsprechpartnerBundle;

// Bundle Klasse zum Symfonie Kernel hinzufügen – Bundle bei Symfonie hinzufügen
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnsprechpartnerBundle extends Bundle
{
}
