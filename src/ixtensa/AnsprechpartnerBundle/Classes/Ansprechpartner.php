<?php

// Wir definieren hier nun fest den Namespace des Bundles zu dieser Datei – wird in der config.php verwendet um auf diese Class für die CTE erweiterung per namespace zu verweisen
namespace ixtensa\AnsprechpartnerBundle\Classes;

// use ixtensa\ProductCatalogBundle\Helper\HelperClass;

// Diese Classes sind verantwortlich, dass das Element im Backend und Frontend auftaucht / generiert wird, weil hier die Backend und Frontend Templates verarbeitet werden

// TODO Weitere mögliche extends raussuchen
// \Frontend, \FrontendModule, welche gibts noch??
class Ansprechpartner extends \ContentElement
{

	/**
	 * Content Template(s) als zentrale(n) String(s) - können dann mit $this->strTemplate verwendet werden
	 * @var string
	 */
	protected $strTemplate = 'ce_ansprechpartner';


    public function generate()
	{
        if (TL_MODE == 'BE')
		{
            $this->strTemplate          = 'be_wildcard';
            $this->Template             = new \BackendTemplate($this->strTemplate);
            $this->Template->wildcard   = "### Ansprechpartner ###";

            $ansprechpartnerId = $this->ansprechpartnerpicker;
            $res = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id = ?")->execute($ansprechpartnerId)->fetchAssoc();

            $this->Template->title = $res['name'] . ' ' . $res['firstname'];

            return $this->Template->parse();
        }

        return parent::generate();
    }


	/**
     * Erzeugt die Ausgabe für das Frontend.
     * @return string
     */
    protected function compile()
    {
        global $objPage;
        $rootId = $objPage->rootId;

        $ansprechpartnerId = $this->ansprechpartnerpicker;
        $res = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id = ?")->execute($ansprechpartnerId)->fetchAssoc();

        $this->Template->ansprechId = $ansprechpartnerId;
        $this->Template->tstamp = $res['tstamp'];
        $this->Template->salutation = $res['salutation'];
        $this->Template->title = $res['title'];
        $this->Template->name = $res['name'];
        $this->Template->firstname = $res['firstname'];
        $this->Template->jobtitle = $res['jobtitle'];
        $this->Template->email = $res['email'];
        $this->Template->phone = $res['phone'];
        $this->Template->more = $res['more'];
        $this->Template->departementCheckList = $res['departementCheckList'];
        $this->Template->published = $res['published'];

        // get img
        $this->Template->addImage = $res['addImage'];
        
        $objFile = \FilesModel::findByUuid($res['image']);
        if($objFile) {
            $this->image = $objFile->path;
            $this->addImageToTemplate($this->Template, $this->arrData);
        }
    }
}
