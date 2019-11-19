<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
 */

// Diese Classes sind verantwortlich, dass das Element im Backend und Frontend auftaucht / generiert wird, weil hier die Backend und Frontend Templates verarbeitet werden
// Es handelt sich hierbei um ein Inhalts Element für Artikel - Inhaltselemente, weswegen wir hier die \ContentElement erweitern
// Wir definieren hier nun fest den Namespace des Bundles zu dieser Datei – wird in der /Resources/contao/config/config.php verwendet um auf diese Class für die CTE erweiterung per namespace zu verweisen
namespace ixtensa\AnsprechpartnerBundle\Classes;

// Wir extenden in dieser KLasse die Content Elemente - wie bereits erwähnt - also für Artikel - Inhaltselemente
class Ansprechpartner extends \ContentElement
{

	// Das hier ist / sind die Content Template(s) als zentrale(n) String(s) für die FRONTEND AUSGABE - Werden hier zentral definiert und können dann mit $this->strTemplate verwendet werden.
	protected $strTemplate = 'ce_ansprechpartner';

	// Die Generate Funktion ist verantwortlich für die Backend Ausgabe des Elements. Also dass wir im Backend in der Elementübersicht Daten über dieses Element angezeigt bekommen. (In diesem Fall "### Ansprechpartner ### und sein Name und Vorname)
    public function generate()
	{
        if (TL_MODE == 'BE')
		{
			// Es gibt ein Standard Template für Backend Wildcards. Dieses Template erzeugt die Standard Backend Karten - so eine wollen wir auch.
            $this->strTemplate          = 'be_wildcard';
            $this->Template             = new \BackendTemplate($this->strTemplate);
			// Die Wildcard ist der Untere Teil des Elements. Dort schreiben wir "### Ansprechpartner ###" hin
            $this->Template->wildcard   = "### Ansprechpartner ###";
			//  $this->ansprechpartnerpicker ist ein DCA Feld des Inhaltselements in der /Resources/contao/dca/tl_content.php und der /Widget/AnsprechpartnerPicker.php von hier ziehen wir uns die in dem Element gewählte Ansprechpartner Person mit seiner ID raus über diese Abfrage raus. Mit dieser ID können wir eine Datenbank Abfrage machen um in der Backend Wildcard den Vornamen und Nachnamen des ausgewählten Partners zu sehen.
            $ansprechpartnerId = $this->ansprechpartnerpicker;
			// Datenbank abfrage mit den IDs ausführen
            $res = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id = ?")->execute($ansprechpartnerId)->fetchAssoc();
			// Den Namen und Vornamen der Person im Backend Template title ausgeben
            $this->Template->title = $res['name'] . ' ' . $res['firstname'];
			// Backend Template parsen und ausgeben
            return $this->Template->parse();
        }
		// Funktion parsen und backend wildcard ausgeben
        return parent::generate();
    }


	// Die compile Funktion erzeugt die Ausgabe für das Frontend
    protected function compile()
    {
		// Mit der Globalen $objPage initiiert man Meta Informationen zu der Webseite. Diese Infos kann man manchmal gebrauchen um Sprache der Seite und RootId (wie in diesem Fall) zu finden und abzuspeichern
        global $objPage;
        $rootId = $objPage->rootId;

		// Wir ziehen uns aus dem DCA Feld vom Picker aus /Resources/contao/dca/tl_content.php und der /Widget/AnsprechpartnerPicker.php wieder die in dem Element gewählte Ansprechpartner Person mit seiner ID raus und speichern Sie in einer Variable ab
        $ansprechpartnerId = $this->ansprechpartnerpicker;
		// Jetzt fragen wir alle Informationen aus der Datenbank für die Kontaktperson mit dieser ID ab und speichern diese in einer Variable $res
        $res = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id = ?")->execute($ansprechpartnerId)->fetchAssoc();

		// Jetzt übergeben wir an das Frontend Template von oben (weil die function protected ist wird das Template 'ce_ansprechpartner' von oben genommen) die ganzen abgefragten Daten der Datenbank aus der $res Variable, damit sie dort für eine Frontend Verarbeitung weiter verarbeitet werden können. Das machen wir für jedes wichtige Feld der Datenbank folglich einzeln.
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

        // In diesem Feld wurde in einer Checkbox angehakt / abgefragt, ob ein Bild hinzugefügt werden soll.
        $this->Template->addImage = $res['addImage'];
        // Ein Bild wird nicht als Pfad direkt abgespeichert, sondern per UUID. Diese haben den Vorteil, dass die Datei verschoben werden kann und immer noch diese ID besitzt, man das Bild dadurch also nicht verliert im Frontend und der Datenbankzuweisung. Nachteil ist, dass wir hier noch ein bisschen proccessing machen müssen, um den Pfad wieder rauszubekommen
        $objFile = \FilesModel::findByUuid($res['image']);
        if($objFile) {
            $this->image = $objFile->path;
            $this->addImageToTemplate($this->Template, $this->arrData);
        }
		// Das wars. Die ganzen Daten aus dem Backend stehen jetzt bereit für das Frontend Template. Der Rest passiert unter /Resources/contao/templates/ce_ansprechpartner.html5 oder den individuellen Templates
    }
}
