<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Dependency Injections: Die Initialisierung / Registrierung von manuell hinzugefügten Bundles aus dem /src Ordner heraus: Contao Bundles, die wir zusätzlich manuell hinzu nehmen wollen kommen sowohl hier als auch in die src/ixtensa/BundleName/ContaoManagerPlugin.php, damit Sie entsprechend geparst und für das Contao Projekt einbezogen werden.
// Um Bundles zu den Contao Core (Standard) Bundles hinzu zu nehmen, dafür gibt es die Contao Funktion "getBundles" vom "BundlePluginInterface". Dieses Interface verwenden wir also nun, indem wir die per Namespace registrierten Bundle "libraries" in diesem Sinne per use statements verwenden. Folgende 4 werden für die Registrierung verwendet:
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\CoreBundle\ContaoCoreBundle;

// Hier kommen jetzt unsere Bundles – in diesem Fall nur eines – das EmployeeBundle, hinzu -
// "Benutze den Ordner EmployeeBundle und seine Inhalte für das EmployeeBundle", genauer gesagt diese Datei: src\ixtensa\EmployeeBundle\EmployeeBundle.php
use ixtensa\EmployeeBundle\EmployeeBundle;

// Lade das BundlePluginInterface von Contao
class ContaoManagerPlugin implements BundlePluginInterface
{
    // Ziehe dir die Bundles und Konfigurationsdateien vom Bundle Parser
	public function getBundles(ParserInterface $parser)
	{
        // Lade unser Bundle in die Config hinzu – allerdings erst nach dem Core Bundle, damit wir keine DCA Konflikte haben
		return [
            BundleConfig::create(EmployeeBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
		];
	}
}
