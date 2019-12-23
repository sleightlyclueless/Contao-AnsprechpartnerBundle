<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Dependency Injections: Die Initialisierung / Registrierung von Bundles: Contao Bundles, die wir zusätzlich hinzu nehmen wollen kommen sowohl hier als auch in die src/ixtensa/AnsprechpartnerBundle/ContaoManagerPlugin.php, damit Sie entsprechend geparst und für das Contao Projekt verwendet werden.
// Um Bundles zu den Contao Core (Standard) Bundles hinzu zu nehmen, damit diese ebenfalls eingebunden werden, dafür gibt es die Contao Funktion "getBundles" vom "BundlePluginInterface". Dieses Interface verwenden wir also nun, indem wir die per Namespace registrierten Bundle "libraries" in diesem Sinne per use statements verwenden. Folgende 4 werden für die Registrierung verwendet:
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\CoreBundle\ContaoCoreBundle;

// Hier kommen jetzt unsere Bundles – in diesem Fall nur eines – das AnsprechpartnerBundle, hinzu
// "Benutze den Ordner AnsprechpartnerBundle und seine Inhalte für das AnsprechpartnerBundle", genauer gesagt diese Datei: src\ixtensa\AnsprechpartnerBundle\AnsprechpartnerBundle.php
use ixtensa\AnsprechpartnerBundle\AnsprechpartnerBundle;

// Lade das BundlePluginInterface von Contao
class ContaoManagerPlugin implements BundlePluginInterface
{
    // Ziehe dir die Bundles und Konfigurationsdateien vom Bundle Parser
	public function getBundles(ParserInterface $parser)
	{
        // Lade unser Bundle in die Config hinzu – allerdings erst nach dem Core Bundle, damit wir keine DCA Konflikte haben
		return [
            BundleConfig::create(AnsprechpartnerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
		];
	}
}
