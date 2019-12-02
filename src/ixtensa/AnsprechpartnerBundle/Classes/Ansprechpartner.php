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
// Wir nutzen Funktionen, die aus einer Helferklasse stammen, um den Code übersichtlicher zu gestalten
use ixtensa\AnsprechpartnerBundle\Helper\HelperClass;

// Wir extenden in dieser Klasse die Content Elemente - wie bereits erwähnt - also für Artikel - Inhaltselemente
class Ansprechpartner extends \ContentElement
{
	// Das hier ist / sind die Content Template(s) als zentrale(n) String(s) für die FRONTEND AUSGABE - Werden hier zentral definiert und können dann mit $this->strTemplate verwendet werden.
	protected $strTemplate = 'ce_ansprechpartner';

	// Die Generate Funktion ist verantwortlich für die Backend Ausgabe des Elements. Also dass wir im Backend in der Elementübersicht Daten über dieses Element angezeigt bekommen. (In diesem Fall "### Ansprechpartner ###" und sein Name und Vorname)
    public function generate()
	{
        if (TL_MODE == 'BE')
		{
			// Es gibt ein Standard Template für Backend Wildcards. Dieses Template erzeugt die Standard Backend Karten - so eine wollen wir auch.
            $this->strTemplate          = 'be_wildcard';
            $this->Template             = new \BackendTemplate($this->strTemplate);
			// Die Wildcard ist der Untere Teil des Elements. Dort schreiben wir "### Ansprechpartner ###" hin
            $this->Template->wildcard   = "### Ansprechpartner ###";
			//  $this->ansprechpartnerpicker ist ein DCA Feld des Inhaltselements in der /Resources/contao/dca/tl_content.php und der /Widget/AnsprechpartnerPicker.php von hier ziehen wir uns die in dem Element gewählte Ansprechpartner Person mit seiner ID raus über diese Abfrage raus. Mit dieser ID können wir eine Datenbank Abfrage machen um in der Backend Wildcard den Vornamen und Nachnamen des ausgewählten Partners zu sehen. Wir holen uns mit einer externen Helperclass Funktion unter /Helper/Helperclass.php die aktuelle Ansprechpartner ID des ausgewählten ANsprechpartners um sie in der SQL Abfrage für weitere Ausgabe von Meta Informationen verwenden zu können.
            $ansprechpartnerId = HelperClass::getAnsprechpartnerId($this->ansprechpartnerpicker);
			// Datenbank abfrage mit den IDs ausführen
            $res = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id = ?")->execute($ansprechpartnerId)->fetchAssoc();
            // Zusatz für die Backend wildcard. Wenn der Ansprechpartner irgendwann mal ausgeblendet wurde wird es in die Wildcard hinzu geschrieben, sollte er noch ausgewählt sein.
            $publishedStatus = '';
            if ($res['published'] != 1) {
                $publishedStatus = '(wurde ausgeblendet!)';
            }
			// Den Namen und Vornamen der Person im Backend Template title ausgeben, gefolgt von dem Zusatz, wenn er ausgeblendet ist.
            $this->Template->title = $res['salutation'] . ' ' . $res['title'] . ' ' . $res['name'] . ' ' . $res['firstname'] . '' .$publishedStatus;
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

		// Wir ziehen uns aus dem DCA Feld vom Picker aus /Resources/contao/dca/tl_content.php und der /Widget/AnsprechpartnerPicker.php wieder die in dem Element gewählte Ansprechpartner Person mit seiner ID raus und speichern Sie in einer Variable über eine /Helper/Helperclass.php Funktion ab
        $ansprechpartnerId = HelperClass::getAnsprechpartnerId($this->ansprechpartnerpicker); //Array mit IDs von den ausgewählten Abteilungen im Picker unter tl_bemod_abteilungen (pids von tl_bemod_abteilungen_lang)
		// Jetzt fragen wir alle Informationen aus der Datenbank für die Kontaktperson mit dieser ID ab und speichern diese in einer Variable $res
        $res = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id = ?")->execute($ansprechpartnerId)->fetchAssoc();

		// Jetzt übergeben wir an das Frontend Template von oben (weil die function protected ist wird das Template 'ce_ansprechpartner' von oben genommen) die ganzen abgefragten Daten der Datenbank aus der $res Variable, damit sie dort für eine Frontend Verarbeitung weiter verarbeitet werden können. Das machen wir für jedes wichtige Feld der Datenbank folglich einzeln.
        $this->Template->ansprechId = $ansprechpartnerId; //Die ID vom Ansprechpartner in te_bemod_ansprechpartner
        $this->Template->tstamp = $res['tstamp']; //Timestamp der Erstellung / des letzten Updates des Datensatzes. Folgende Angaben dann nur von diesem Ansprechpartner per SQL Abfrage.
        $this->Template->salutation = $res['salutation']; //Anrede String
        $this->Template->title = $res['title']; //Titel String
        $this->Template->name = $res['name']; //Name String
        $this->Template->firstname = $res['firstname']; //Vorname String
        $this->Template->jobtitle = $res['jobtitle']; //Jobtitel String
        $this->Template->email = $res['email']; //Email String
        $this->Template->emailLinktext = $res['emailLinktext']; //Emaillinktext String
        $this->Template->phone = preg_replace('/\s+/', '', $res['phone']); //Telefonnummer String ohne Whitespaces
        $this->Template->phoneLinktext = $res['phoneLinktext']; //Telefonlinktext String
        $this->Template->mobile = preg_replace('/\s+/', '', $res['mobile']); //Telefonnummer String ohne Whitespaces
        $this->Template->mobileLinktext = $res['mobileLinktext']; //Mobilelinktext String
        $this->Template->fax = $res['fax']; //Faxnummer String
        $this->Template->more = $res['more']; //Textarea weitere Informationen String
        $this->Template->published = $res['published']; //NULL oder 1 für published Checkbox

        // HelperClass Funktionen sind separate Funktionen unter /AnsprechpartnerBundle/Helper/HelperClass.php. Solche Funktionen werden ausgelagert wenn sie separat öfters von mehreren Klassen genutzt werden oder um den Code übersichtlicher zu halten.
        $departementsIDs = HelperClass::getDepartementIds($res['departementCheckList']); // Array mit IDs von den ausgewählten Abteilungen im Picker unter tl_bemod_abteilungen (pids von tl_bemod_abteilungen_lang)
        $departementsString = HelperClass::queryDepartementNames($departementsIDs); // String mit Kommagetrennten Abteilungsnamen aus der tl_bemod_abteilungen_lang Tabelle. ALPHABETISCH ABSTEIGEND SORTIERT!
        $this->Template->departementCheckListReader = $departementsString;

        // Ein Bild wird nicht als Pfad direkt abgespeichert, sondern per UUID. Diese haben den Vorteil, dass die Datei verschoben werden kann und immer noch diese ID besitzt, man das Bild dadurch also nicht verliert im Frontend und der Datenbankzuweisung. Nachteil ist, dass wir hier noch ein bisschen proccessing machen müssen, um den Pfad wieder rauszubekommen
        // In diesem Feld wurde in einer Checkbox angehakt / abgefragt, ob ein Bild hinzugefügt werden soll.
        // Ein Objekt für das Bild erstellen anhand der UUID (findet Pfad vom Bild, sowie weitere Meta infos)
        $objFile = \FilesModel::findByUuid($res['singleSRC']);
        // Pfad zum Bild in der Variable $path ablegen, wenn das Bild existiert
        if($objFile) {
            $path = $objFile->path;
        }
        // Wert aus Checkbox, ob Bild veröffentlicht werden soll, an Template weiter geben
        $this->Template->addImage = $res['addImage'];
        // Pfad zum Bild an Template geben
        $this->Template->path = $path;
        // Alttext an Template weiter geben
        $this->Template->alt = $res['alt'];
        // Bildunterschrift an Template weiter geben
        $this->Template->caption = $res['caption'];
        // In der Variable $size stecken die Werte für die Einstellungen der resizeImage Feldern, die werden mit deserialize zu einem Array gewandelt
        $size = deserialize($res['resize']);
        //Die Funktion getImage nimmt aus den Variablen von $size (PathToDefaultImage, Width, Height, Mode) die Einstellungen für das neue resize Bild, die ausgewählt wurden und generiert dieses Bild, speichert es unter /assets/images/.. ab, sowie speichert den Pfad dort hin unter $src ab
        $src = $this->getImage($path, $size[0], $size[1], $size[2]);
        // Der Pfad zum neuen resize Bild wird an das Template gegeben
        $this->Template->resizePath = $src;
        // Die 4 Eingabefelder "margin" aus dem DCA werden hier über generateMargin und deserialize Funktion in inline CSS Format umgewandelt und im Template dann weiter- und ausgegeben
        $margin = $this->generateMargin(deserialize($res['margin']), 'margin');
        // Und ab weiter zum Template damit
        $this->Template->margin = $margin;

		// Das wars. Die ganzen Daten aus dem Backend stehen jetzt bereit für das Frontend Template. Der Rest und die Ausgabe passieren für diese(s) Element(e) nun unter /Resources/contao/templates/ce_ansprechpartner.html5 oder deinen individuellen Templates
    }
}
