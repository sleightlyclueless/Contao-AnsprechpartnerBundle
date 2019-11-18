<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
 */

// Define the namespace for our bundle folder location (also present in the file 'AnsprechpartnerBundle.php')
namespace ixtensa\AnsprechpartnerBundle;

// Use contaos core and bundle files to add ours in here via the PluginInterface with the function "getBundles" just like in the dependency injection file
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;

// Add Bundle to the BundlePluginInterface
class ContaoManagerPlugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(AnsprechpartnerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}
