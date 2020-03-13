<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Wir definieren hier nun den Namespace zu dieser Datei – wird in der /EmployeeBundle/Resources/contao/config/config.php verwendet um auf diese Class Datei für die CTE Erweiterung per Namespace zu verweisen.
namespace ixtensa\EmployeeBundle\Classes;

// Wir holen uns externe Helperclass Funktionen unter /EmployeeBundle/Helper/Helperclass.php per Namespace. Diese werden immer nach dem Schema HelperClass::Funktionsname per Namespace dazu genommen. Die einzelnen Funktionen sind in der Helperclass Datei näher beschrieben und dienen dem zentralen Verwenden von Funktionen und dem gleichzeitigen Auslagern von Code zur modularen und besseren Lesbarkeit.
use ixtensa\EmployeeBundle\Helper\HelperClass;

// Es handelt sich hierbei um ein Inhalts- Content Element für Artikel (CTE), weswegen wir hier die \ContentElement Klasse von Contao erweitern – wurde in der /EmployeeBundle/Resources/contao/config/config.php so definiert und wird nun hier weiter geführt.
class Employee extends \ContentElement
{
    // Das hier ist das Content Template als zentraler String für die FRONTEND (FE) AUSGABE - Wird hier zentral definiert und kann dann mit $this->strTemplate in den private functions verwendet werden.
    protected $strTemplate = 'ce_employee';


    // ===============================================BACKEND===============================================
    // Die Generate Funktion ist verantwortlich für die Backend Ausgabe des Elements, also das was wir im Backend in der Elementübersicht als "wildcards" über dieses Element angezeigt bekommen. (In diesem Fall "### Mitarbeiter ###" und weitere Angaben je nach Typ der Ausgabe).
    public function generate()
	{
        // Nur wenn wir uns im Backend befinden wollen wir die folgenden Funktionen für das Generieren der Wildcards ausführen
        if (TL_MODE == 'BE')
		{
			// Es gibt ein Standard Template für Backend Wildcards. Dieses Template erzeugt die Standard Backend Karte - so eine wollen wir natürlich auch für eine einheitliche Darstellung.
            $this->strTemplate          = 'be_wildcard';
            $this->Template             = new \BackendTemplate($this->strTemplate);
			// Die wildcard im Template ist der untere Teil des Elements. Dort schreiben wir "### Mitarbeiter ###" aus der languages Datei default.php hin
            $this->Template->wildcard   = $GLOBALS['IX_EB']['MSC']['wildcard_header'];

            // Jetzt brauchen wir noch den Title und evtl. sonstige Angaben für die fertige Wildcard. Diese varriieren je nach dem welcher Typ der Mitarbeiter-Ausgabe gewählt wurde (Einzeln, Individuelle Checkboxen, nach Abteilungen, Alle). Für diese Fälle müssen wir per switch-case unterschiedliche Abfragen der Fälle für die Wildcard Titles erstellen.
            switch ($this->employeeType)
    		{
                // Fall Einzeln – den Namen abfragen und in wildcard ausgeben
    			case 'Einzeln':
                case 'Single':
                    // $this->employeepicker ist ein DCA Feld des Inhaltselements in der /EmployeeBundle/Resources/contao/dca/tl_content.php und der /Widget/EmployeePicker.php und trägt beispielsweise den Inhalt "[31] Herr Auszubildender Mustermann Max"
                    // Von hier ziehen wir uns den in dem Element gewählten Mitarbeiter mit seiner ID über diese Helperclass Abfrage raus. Die Funktion liest die ID(s) in den eckigen Klammern als Start und End Tag aus und liefert Sie als Array zurück.
                    $employeeId = HelperClass::getIdsByDelimiter($this->employeepicker, '[', ']');
                    // Mit der ID können wir wieder eine externe Datenbank Abfrage machen um in der Backend Wildcard den Vornamen und Nachnamen des ausgewählten Partners zu sehen. Diese Funktion liefert den kompletten Datensatz des Employees der jetzigen ID aus der entsprechenden Tabelle zurück.
                    $res = HelperClass::getEmployeeDataByIds($employeeId);
                    // Kleiner Zusatz für die Backend wildcard eines einzelenen Mitarbeiters. Wenn der Mitarbeiter irgendwann mal ausgeblendet wurde, wird es in die Wildcard hinzu geschrieben, sollte er noch ausgewählt sein.
                    $publishedStatus = '';
                    if ($res[0]['published'] != 1) {
                        $publishedStatus =  $GLOBALS['IX_EB']['MSC']['disabled'];
                    }
        			// Den Namen und Vornamen der Person im Backend Template title ausgeben, gefolgt von dem Zusatz, falls er ausgeblendet ist.
                    $this->Template->title = $res[0]['name'] . ' ' . $res[0]['firstname'] . '' .$publishedStatus;
                    break;

                // Für die individuelle Liste als Einfügeoption geben wir im Backend nur 'Individuelle Mitarbeiter' aus – bei zu vielen Mitarbeitern wird das sonst mit allen Namen zu lang und unübersichtlich für eine Wildcard
                case 'Individuell':
                case 'Individual':
                    // Falls wir es mal brauchen: $this->employeecheckboxes hat den Inhalt nach dem Schema
                    // "a:2:{i:0;s:34:"[32] Herr Doktor Mustermann Max";i:1;s:39:"[31] Herr Auszubildender Mustermann Maximilian";}"
                    // Spezielle Betextung für individuellen Einfügemodus in die wildcard schreiben
                    $this->Template->title = $GLOBALS['IX_EB']['MSC']['template_title_individual'];
    				break;

                // Die Auflistung der Abteilungen, von denen die Mitarbeiter ausgegeben werden sollen in der Wildcard für diesen Fall der Abteilungseinfügeoption ausgeben werden
                case 'Abteilungen':
                case 'Departements':
                    // Inhalt von $this->departementcheckboxes in diesem Fall Abteilungen z.B. "a:2:{i:0;s:2:"17";i:1;s:1:"4";}"
                    // Funktion der Helperclass um die IDs (befinden sich innerhalb der Anführungszeichen) der Abteilungen aus dem Feldwert als assoziatives Array zu bekommen
                    $departementsIDs = HelperClass::getIdsByDelimiter($this->departementcheckboxes, '"', '"');
                    // Hier wird in der Funktion ein string mit Kommagetrennten Abteilungsnamen aus der tl_ixe_departement_lang Tabelle zurück geben. Alphabetisch absteigend sortiert!
                    $departementsString = HelperClass::getDepartementNames($departementsIDs);
                    // Alles wieder entsprechend anhand der abgefragten Daten zum Wildcard - Title hinzufügen
                    $this->Template->title = $GLOBALS['IX_EB']['MSC']['template_title_departements'].$departementsString;
    				break;

                // Wenn der Modus zur Ausgabe aller Employee gewählt wurde schreiben wir das auch einfach so hin
                case 'Alle':
                case 'All':
                    $this->Template->title = $GLOBALS['IX_EB']['MSC']['template_title_all'];
    				break;

                // Trifft keiner der Fälle ein, setzen wir der Sauberkeit im Backend einen default Handler
    			default:
                    $this->Template->title = $GLOBALS['IX_EB']['MSC']['template_title_err'];
    				break;
    		}
			// Backend Template parsen und entsprechend weitergeben
            return $this->Template->parse();
        }
		// Funktion parsen und backend wildcard ausgeben
        return parent::generate();
    }


    // ===============================================FRONTEND===============================================
	// Die compile Funktion erzeugt die Ausgabe für das Frontend, bzw. gibt die Werte an das obig definierte standard Template weiter und dieses gibt es dann im Frontend weiter aus.
    protected function compile()
    {
        // An das Frontend Template wird immer ein Array der Mitarbeiter Daten weiter gegeben – je nach dem welcher Einfügemodus und welche Mitarbeiter zur Ausgabe gewählt wurden, varriieren die weitergegebenen Daten, befinden sich aber immer in dem selben Schema des Arrays.
        // Das Frontend Template wertet diese Daten aus dem Array dann aus und gibt sie per foreach im Frontend aus.
        // Je nach Einfügemodus müssen wieder leicht unterschiedliche Datenbankabfragen getätigt werden, wesewegen wir zunächst wieder einen Switch Case Aufbau verwenden, für jeden Modus individuell das assoziative Array für das Template aufbereiten und dieses zum Schluss ans Template weiter geben.
        switch ($this->employeeType)
        {
            // Fall einzelner Employee
            case 'Einzeln':
            case 'Single':
                // Wir ziehen uns aus dem DCA Feld vom Picker (/EmployeeBundle/Resources/contao/dca/tl_content.php und /Widget/EmployeePicker.php) wieder die ID heraus und speichern Sie als Array in einer Variable über eine Helperclass Funktion ab.
                $employeeId = HelperClass::getIdsByDelimiter($this->employeepicker, '[', ']');
                // Jetzt fragen wir alle Informationen aus der Datenbank für die Kontaktperson mit dieser ID per SQL ab und speichern diese in einer als Array definierten Variable $res[].
                // Ein Array wird verwendet, damit wir diese Helperclass sowohl für einzelene, als auch mehrere Mitarbeiter verwenden können.
                $res = HelperClass::getEmployeeDataByIds($employeeId);
                // Diese Funktion verarbeitet nun die per SQL abgefragten Datensätze im Array und bereitet einige Daten (Pfade zum Bild, rendern der eingegebenen Margins etc.) noch zentral in der Helperclass auf und gibt diese als Array(s) wieder aus.
                $arrData = HelperClass::prepareArrayDataForTemplate($res, $this->Template);

                break;

            // Fall individuelle Mitarbeiter Checkliste
            case 'Individuell':
            case 'Individual':
                // Wir ziehen uns aus dem DCA Feld der Checkliste (/EmployeeBundle/Resources/contao/dca/tl_content.php und der /Widget/EmployeePicker.php) wieder die in dem Element gewählten Personen mit ihren IDs nach dem Schema [ID] raus und speichern Sie als Array in einer Variable über eine Helperclass Funktion ab
                $employeeIds = HelperClass::getIdsByDelimiter($this->employeecheckboxes, '[', ']');
                // Jetzt fragen wir alle Informationen aus der Datenbank für die Kontaktperson(en) mit den mehreren IDs per SQL ab und speichern diese in einer Variable $res[].
                $res = HelperClass::getEmployeeDataByIds($employeeIds);
                // Diese Funktion verarbeitet nun die per SQL abgefragten Datensätze im Array und bereitet einige Daten (Pfade zum Bild, rendern der eingegebenen Margins etc.) noch zentral in der Helperclass auf und gibt diese als Array(s) wieder aus.
                $arrData = HelperClass::prepareArrayDataForTemplate($res, $this->Template);

                break;

            // Fall Mitarbeiter einer bestimmten Abteilung ausgeben
            case 'Abteilungen':
            case 'Departements':
                // Funktion der Helperclass um die IDs der Abteilungen aus dem Feldwert des Pickers als Array zu bekommen
                $departementCheckboxesIDs = HelperClass::getIdsByDelimiter($this->departementcheckboxes, '"', '"');
                // Für diesen Fall nach gewählten Abteilungen müssen wir aus allen Mitarbeitern rausfiltern, welcher zu den entsprechenden Abteilungen dazugehört. Dafür fragen wir erst einmal ALLE Mitarbeiter aus der Tabelle der DB ab. In der spezielen Aufbereitungsfunktion des Arrays geben wir dann die IDs der gewählten Abteilungen, sowie das Array aller Mitarbeiter weiter. Dann prüfen wir in der Aufbereitung welche Mitarbeiter aus allen möglichen deine ID der gewählten Abteilungen enthalten und geben diese weiter.
                $res = HelperClass::getAllEmployeeData();
                // Diese Funktion verarbeitet nun die per SQL abgefragten Datensätze und gibt diese als assoziatives Array wieder aus. Dabei vergleichen wir wie bereits erwähnt auch ob die gewählten Abteilungen deckend mit dem entsprechenden Mitarbeiter sind.
                $arrData = HelperClass::prepareArrayDataForTemplateByDepartementpicker($departementCheckboxesIDs, $res);

                break;

            // Wenn der Modus zur Ausgabe aller Mitarbeiter gewählt wurde, geben wir auch einfach alle aus.
            case 'Alle':
            case 'All':
                // Ganz simpel in diesem letzten Fall – wir müssen für alle Mitarbeiter die Daten abfragen und nur schauen welche Mitarbeiter veröffentlicht sind – anschließend im Array ablegen.
                $res = HelperClass::getAllEmployeeData();
                // Dieses Array wird dann wieder per Helperclass Funktion mit foreach aufbereitet
                $arrData = HelperClass::prepareArrayDataForTemplate($res, $this->Template);

                break;

            // Trifft keiner der Fälle ein, setzen wir einen leeren default Wert für das Template, damit nicht NULL weiter gegeben wird, was zu Fehlern führen wird.
            default:
                $arrData = [];
                break;
        }
        // Hier wird nur noch das in jedem Fall aufbereitete assoziative Array unter $arrData zum Template weiter übergeben – bereit zur Frontend Ausgabe durch das Template.
        // Die ganzen Daten aus dem Backend stehen jetzt bereit für das Frontend Template in /EmployeeBundle/Resources/contao/templates/ce_employee.html5 oder evtl. deinem individuellen Template
        $this->Template->arrAnsprechData = $arrData;
    }
}
