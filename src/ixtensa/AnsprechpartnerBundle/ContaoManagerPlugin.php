<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
 */

 // Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann. Gibt es auch auf selber Ebene in der 'AnsprechpartnerBundle.php'. Diese Datei wird von der /src/AnsprechpartnerBundle/composer.json aufgerufen, die wiederrum durch den /composer.json im root aufgerufen wurde
namespace ixtensa\AnsprechpartnerBundle;

// Um Bundles zu den Standard Contao Core Bundles hinzu zu nehmen, damit diese alle ebenfalls geparst werden, dafür gibt es die Contao Funktion "getBundles" vom "BundlePluginInterface". Dieses Interface verwenden wir also nun, indem wir die per Namespace registrierten Bundle "libraries" in diesem Sinne per use statements verwenden. Folgende 4 werden für die Registrierung verwendet:
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;

// Lade das BundlePluginInterface von Contao
class ContaoManagerPlugin implements BundlePluginInterface
{
    // Ziehe dir die Bundles und Konfigurationsdateien vom Bundle Parser
    public function getBundles(ParserInterface $parser)
    {
        // Lade unser Bundle hinzu – allerdings erst nach dem Core Bundle, damit wir keine DCA Konflikte haben
        return [
            BundleConfig::create(AnsprechpartnerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}
