<?php

// Initialisierung / Registrierung der Bundles: Contao Bundles, die zusätzlich hinzu nehmen wollen kommen sowohl hier als auch in die src/ixtensa/AnsprechpartnerBundle/ContaoManagerPlugin.php
// – dafür gibt es die Contao Funktion "getBundles" für das "BundlePluginInterface", das wollen wir verwenden also tun wir das per Symfonie mit "use". Wir brauchen diese 4:
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;


// Hier kommen jetzt unsere Bundles – in diesem Fall nur eines – AnsprechpartnerBundle, hinzu
// "Benutze den Ordner AnsprechpartnerBundle und seine Inhalte für das AnsprechpartnerBundle"
use ixtensa\AnsprechpartnerBundle\AnsprechpartnerBundle;

// Lade das BundlePluginInterface von Contao
class ContaoManagerPlugin implements BundlePluginInterface
{
    //Ziehe dir die Bundles und Konfigurationsdateien
	public function getBundles(ParserInterface $parser)
	{
        //Lade unser Bundle hinzu – allerdings nach dem Core Bundle, damit wir keine DCA Konflikte haben
		return [
            BundleConfig::create(AnsprechpartnerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
		];
	}
}
