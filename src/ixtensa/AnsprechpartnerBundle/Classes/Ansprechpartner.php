<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */


// Wir definieren hier nun fest den Namespace des Bundles zu dieser Datei – wird in der /Resources/contao/config/config.php verwendet um auf diese Class für die CTE Erweiterung per namespace zu verweisen
namespace ixtensa\AnsprechpartnerBundle\Classes;

// Wir holen uns externe Helperclass Funktionen durch die unter /Helper/Helperclass.php erstellten Funktionen immer nach dem Schema HelperClass::Funktionsname per Namespace dazu. Die einzelnen Funktionen sind in der Helperclass Datei näher beschrieben und dienen dem zentralen Verwenden und auslagern von Code zur modularen und besseren Lesbarkeit.
use ixtensa\AnsprechpartnerBundle\Helper\HelperClass;


// Es handelt sich hierbei um ein Inhalts Element für Artikel (CTE) - Inhaltselemente, weswegen wir hier die \ContentElement Klasse von Contao erweitern – wurde in der /Resources/contao/config/config.php so festgelegt
class Ansprechpartner extends \ContentElement
{
    // Das hier ist das Content Template als zentraler String für die FRONTEND AUSGABE - Werden hier zentral definiert und können dann mit $this->strTemplate in private functions verwendet werden.
    protected $strTemplate = 'ce_ansprechpartner';

    // ===============================================BACKEND===============================================

    // Die Generate Funktion ist verantwortlich für die Backend Ausgabe des Elements, also dass wir im Backend in der Elementübersicht Daten über dieses Element angezeigt bekommen. (In diesem Fall "### Ansprechpartner ###" und weitere Angaben je nach Typ der Ausgabe)
    public function generate()
	{
        if (TL_MODE == 'BE')
		{
			// Es gibt ein Standard Template für Backend Wildcards. Dieses Template erzeugt die Standard Backend Karte - so eine wollen wir natürlich auch für eine harmonische Darstellung.
            $this->strTemplate          = 'be_wildcard';
            $this->Template             = new \BackendTemplate($this->strTemplate);
			// Die Wildcard ist der Untere Teil des Elements. Dort schreiben wir "### Ansprechpartner ###" hin
            $this->Template->wildcard   = "### Ansprechpartner ###";

            // Jetzt brauchen wir noch den Title / andere Angaben. Diese varriieren je nach dem welcher Typ der Ansprechpartner Ausgabe gewählt wurde (Einzeln, Individuelle Checkboxen, nach Abteilungen, Alle). Für diese Fälle müssen wir per switch-case Fällen auch unterschiedliche Abfragen und Wildcard Titles erstellen
            switch ($this->ansprechpartnerType)
    		{
                // Fall Einzeln – den Namen abfragen und in wildcard ausgeben
    			case 'Einzeln':
                    // $varValue Inhalt im Fall Einzeln: "[31] Herr Auszubildender Mustermann Max"
    				$varValue = $this->ansprechpartnerpicker;
                    // $this->ansprechpartnerpicker ist ein DCA Feld des Inhaltselements in der /Resources/contao/dca/tl_content.php und der /Widget/AnsprechpartnerPicker.php. Von hier ziehen wir uns den in dem Element gewählten Ansprechpartner mit seiner ID über diese Helperclass Abfrage raus. Die Funktion liest die ID in den eckigen Klammern aus und liefert Sie als Array zurück.
                    $ansprechpartnerId = HelperClass::getAnsprechpartnerIds($varValue);
                    // Mit der ID können wir wieder eine externe Datenbank Abfrage machen um in der Backend Wildcard den Vornamen und Nachnamen des ausgewählten Partners zu sehen. Diese Funktion liefert den kompletten Datensatz des Ansprechpartners der jetzigen ID aus der entsprechenden Tabelle zurück.
                    $res = HelperClass::getAnsprechpartnerDataById($ansprechpartnerId);
                    // Kleiner Zusatz für die Backend wildcard. Wenn der Ansprechpartner irgendwann mal ausgeblendet wurde, wird es in die Wildcard hinzu geschrieben, sollte er noch ausgewählt sein.
                    $publishedStatus = '';
                    if ($res['published'] != 1) {
                        $publishedStatus = ' (wurde ausgeblendet!)';
                    }
        			// Den Namen und Vornamen der Person im Backend Template title ausgeben, gefolgt von dem Zusatz, falls er ausgeblendet ist.
                    $this->Template->title = $res['salutation'] . ' ' . $res['title'] . ' ' . $res['name'] . ' ' . $res['firstname'] . '' .$publishedStatus;
                    break;

                // Für die individuelle Liste als Einfügeoption geben wir im Backend nur 'Individuelle Ansprechpartner' aus – bei mehreren Ansprechpartnern wird das sonst mit allen Namen etc. zu lang für eine Wildcard
                case 'Individuell':
                    // Falls wir es mal brauchen: Value von dem Auswahlfeld
    				$varValue = $this->ansprechpartnercheckboxes;
                    // "a:2:{i:0;s:34:"[32] Herr Doktor Mustermann Max";i:1;s:39:"[31] Herr Auszubildender Mustermann Markus";}"
                    $this->Template->title = 'Individuelle Ansprechpartner';
    				break;

                // Die Auflistung der Abteilungen von denen die Ansprechpartner ausgegeben werden sollen in der Wildcard für diesen Fall der Abteilungseinfügeoption ausgeben werden
                case 'Abteilungen':
                    // $varValue Inhalt im Fall Abteilungen "a:2:{i:0;s:2:"17";i:1;s:1:"4";}"
                    $varValue = $this->departementcheckboxes;
                    // Funktion der Helperclass um die IDs der Abteilungen aus dem Feldwert als Array zu bekommen
                    $departementsIDs = HelperClass::getDepartementIds($varValue);
                    // Hier wird in der Funktion ein string mit Kommagetrennten Abteilungsnamen aus der tl_bemod_abteilungen_lang Tabelle zurück geben. ALPHABETISCH ABSTEIGEND SORTIERT!
                    $departementsString = HelperClass::getDepartementNames($departementsIDs);
                    // Alles wieder entsprechend anhand der abgefragten Daten zum Wildcard - Title hinzufügen
                    $this->Template->title = 'Ansprechpartner aus Abteilung(en): ' . $departementsString;
    				break;

                // Wenn der Modus zur Ausgabe aller Ansprechpartner gewählt wurde schreiben wir das auch einfach so hin
                case 'Alle':
                    $this->Template->title = 'Alle Ansprechpartner';
    				break;

                // Trifft keiner der Fälle ein, setzen wir der Sauberkeit im Backend einen default Handler
    			default:
                    $this->Template->title = 'Es wurde kein Einfügemodus ausgewählt! Bitte versuchen Sie es erneut!';
    				break;
    		}

			// Backend Template parsen und entsprechend weitergeben
            return $this->Template->parse();
        }
		// Funktion parsen und backend wildcard ausgeben
        return parent::generate();
    }



    // ===============================================FRONTEND===============================================
	// Die compile Funktion erzeugt die Ausgabe für das Frontend, bzw. gibt die Werte an das obig definierte Template weiter und dieses gibt es dann im Frontend weiter aus.
    protected function compile()
    {
        // An das Frontend Template wird immer ein Array der Ansprechpartner Daten weiter gegeben – je nach dem welcher Einfügemodus gewählt wurde, welche Ansprechpartner betroffen sind, oder etwas im Nachhinein ein- oder ausgebländet wurde, varriieren die weitergegebenen Arrays je nach Einfügemodus etc.
        // Das Frontend Template wertet diese Daten aus dem Array dann aus / fragt Sie ab und gibt sie im Frontend aus.
        // Je nach Einfügemodus müssen wieder leicht unterschiedliche Datenbankabfragen getätigt werden, wesewegen wir zunächst wieder einen Switch Case Aufbau verwenden, den wir zum Schluss zusammenführen und ans Template weiter führen.
        switch ($this->ansprechpartnerType)
        {
            // Fall einzelner Ansprechpartner
            case 'Einzeln':
                // Wir ziehen uns aus dem DCA Feld vom Picker aus /Resources/contao/dca/tl_content.php und der /Widget/AnsprechpartnerPicker.php wieder die in dem Element gewählte Ansprechpartner Person mit seiner ID nach dem Schema [ID] raus und speichern Sie als Array in einer Variable über eine Helperclass Funktion ab
                $ansprechpartnerId = HelperClass::getAnsprechpartnerIds($this->ansprechpartnerpicker);
                // Jetzt fragen wir alle Informationen aus der Datenbank für die Kontaktperson mit dieser ID per SQL ab und speichern diese in einer als Array definierten Variable $res[].
                // Wir definieren $res[] hier für eine Person auch als Array, damit wir in der Helperfunktion als auch im Template sowohl für eine Person (ein Array), als auch mehrere Personen (mehrere Arrays) keinen unterschiedlichen Aufbau für direkte Datensätze oder Arrays machen müssen und immer eine foreach Schleife verwenden können.
                $res[] = HelperClass::getAnsprechpartnerDataById($ansprechpartnerId);
                // Diese Funktion verarbeitet nun die per SQL abgefragten Datensätze im Array und bereitet einige Daten (Pfade zum Bild, rendern der eingegebenen Margins etc.) noch zentral in der Helperclass auf und gibt diese als Array(s) wieder aus.
                $arrData = HelperClass::prepareArrayDataForTemplate($res);

                // Hier wird nur noch das aufbereitete Array zum Template weiter übergeben – bereit zur Frontend Ausgabe
                $this->Template->arrAnsprechData = $arrData;

                // Das wars. Die ganzen Daten aus dem Backend stehen jetzt bereit für das Frontend Template. Der Rest und die Ausgabe passieren für diese(s) Element(e) nun unter /Resources/contao/templates/ce_ansprechpartner.html5 oder deinen individuellen Templates
                break;

            // Fall individuelle Ansprechpartner Checkliste
            case 'Individuell':
                // Wir ziehen uns aus dem DCA Feld der Checkliste aus /Resources/contao/dca/tl_content.php und der /Widget/AnsprechpartnerPicker.php wieder die in dem Element gewählten Ansprechpartner Personen mit ihren IDs nach dem Schema [ID] raus und speichern Sie als Array in einer Variable über eine Helperclass Funktion ab
                $ansprechpartnerIds = HelperClass::getAnsprechpartnerIds($this->ansprechpartnercheckboxes);
                // Jetzt fragen wir alle Informationen aus der Datenbank für die Kontaktperson(en) mit den mehreren IDs per SQL ab und speichern diese in einer Variable $res[].
                $res = HelperClass::getMultipleAnsprechpartnerDataByIds($ansprechpartnerIds);
                // Diese Funktion verarbeitet nun die per SQL abgefragten Datensätze im Array und bereitet einige Daten (Pfade zum Bild, rendern der eingegebenen Margins etc.) noch zentral in der Helperclass auf und gibt diese als Array(s) wieder aus.
                $arrData = HelperClass::prepareArrayDataForTemplate($res);
                // Hier wird nur noch das aufbereitete Array zum Template weiter übergeben – bereit zur Frontend Ausgabe
                $this->Template->arrAnsprechData = $arrData;
                break;

            // Fall Auflistung der Abteilungen von denen die individuellen Ansprechpartner ausgegeben werden sollen
            case 'Abteilungen':
                // Funktion der Helperclass um die IDs der Abteilungen aus dem Feldwert des Pickers als Array zu bekommen
                $departementCheckboxesIDs = HelperClass::getDepartementIds($this->departementcheckboxes);
                // Für diesen Fall nach gewählten Abteilungen müssen wir aus allen Ansprechpartnern rausfiltern, welcher der / den entsprchenden Abteilungen dazugehört. Dafür fragen wir erst einmal ALLE Ansprechpartner aus der Tabelle der DB ab. In der spezielen Aufbereitungsfunktion des Arrays geben wir dann die IDs der gewählten Abteilungen, sowie das Array aller Ansprechpartner weiter. Dann prüfen wir in der Aufbereitung welche Ansprechpartner aus allen möglichen deine ID der gewählten Abteilungen enthalten und geben diese weiter
                $res = HelperClass::getAllAnsprechpartnerData();
                // Diese Funktion verarbeitet nun die per SQL abgefragten Datensätze im Array und bereitet einige Daten (Pfade zum Bild, rendern der eingegebenen Margins etc.) noch zentral in der Helperclass auf und gibt diese als Array(s) wieder aus und vergleicht wie bereits erwähnt auch ob die gewählten Abteilungen deckend mit dem entsprechenden Ansprechpartner sind
                $arrData = HelperClass::prepareArrayDataForTemplateByDepartementpicker($departementCheckboxesIDs, $res);

                // Hier wird nur noch das aufbereitete Array zum Template weiter übergeben – bereit zur Frontend Ausgabe
                $this->Template->arrAnsprechData = $arrData;
                break;

            // Wenn der Modus zur Ausgabe aller Ansprechpartner gewählt wurde schreiben wir das auch einfach so
            case 'Alle':
                // Ganz simpel in diesem letzten Fall – wir müssen für alle Ansprechpartner einfach nur alle gewählten Ansprechpartner aus der Datenbank abfragen, die veröffentlicht sind und im Array ablegen.
                $res = HelperClass::getAllAnsprechpartnerData();
                // Dieses Array wird dann wieder per Helperclass Funktion mit foreach aufbereitet
                $arrData = HelperClass::prepareArrayDataForTemplate($res);
                // Hier wird nur noch das aufbereitete Array zum Template weiter übergeben – bereit zur Frontend Ausgabe
                $this->Template->arrAnsprechData = $arrData;
                break;

            // Trifft keiner der Fälle ein, setzen wir einen leeren default Wert für das Template, damit nicht NULL weiter gegeben wird, was zu Fehlern führen kann.
            default:
                $emptyArray = [];
                $this->Template->arrAnsprechData = $emptyArray;
                break;
        }
    }
}
