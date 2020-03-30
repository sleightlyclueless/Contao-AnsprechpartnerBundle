<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Wir definieren hier nun den Namespace zu dieser Datei – wird in der /Classes/Employee.php verwendet um auf diese Datei für die CTE Erweiterung per Namespace zu verweisen.
namespace ixtensa\EmployeeBundle\Helper;


// Inhaltsverzeichnis der Funktionen:
// 1. __construct()
// --> Datenbankinstanz und Benutzer bekommen

// 2. getIdsByDelimiter()
// --> Aus einem String in bestimmte Zeichen eingeschlossene IDs als assoziatives Array ausbereiten

// 3. getEmployeeDataByIds()
// --> Datenbankabfrage der tl_ixe_employeedata für alle Daten der Ansprechpartner mit bestimmten IDs

// 4. getAllEmployeeData()
// --> Datenbankabfrage der tl_ixe_employeedata für alle Daten aller Ansprechpartner

// 5. getDepartementNames()
// --> Übergeordnete Abteilungsnamen nach Department IDs aus der tl_ixe_departement bekommen

// 6. getDepartementTranslations()
// --> Abteilungsnamen - Übersetzungen nach Department PIDs aus der tl_ixe_departement_lang bekommen

// 7. prepareArrayDataForTemplate()
// --> Assoziatives Array der Ansprechpartner per foreach für jeden Ansprechpartner einzeln für Templateausgabe aufbereiten

// 8. prepareArrayDataForTemplateByDepartementpicker()
// --> Assoziatives Array der Ansprechpartner per foreach für jeden Ansprechpartner einzeln für Templateausgabe unter berücksichtigung der Zugehörigkeit einer bestimmten Abteilung aus der tl_ixe_departement aufbereiten

// 9. prepareArrayForLanguage()
// --> Aus MultiColumWizard BLOBs per assoziative Arrays den Sprachinhalt für die entsprechende Sprachverseion der Seite aufbereiten


class HelperClass extends \Backend
{
    // Contao Funktion um den derzeitigen Backend Nutzer für Rechteabfragen und die Datenbank für weitere Abfragen zu bekommen
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->import('Database');
    }

    // Aus einem String den Inhalt (hier: immer IDs) eingeschlossen in bestimmte Delimiter (z.B. Eckige Klammern für [ID]) entziehen und in einem Assoziativem Array zurück geben
    // Departement IDs aus einem BLOB: a:3:{i:0;s:1:"7";i:1;s:1:"9";i:2;s:1:"4";}
    // -> Array ( [0] => 7 [1] => 9 [2] => 4 )
    // Mitarbeiter ID aus String: [31] Herr Auszubildender Mustermann Max
    // -> Array ( [0] => 31 )
    public function getIdsByDelimiter($str, $startDelimiter, $endDelimiter)
    {
        // We pre - create an array to add our contents to
        $contents = array();
        // We need the length of the start and end strings for our math to find the generic content within
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        // Reset values of Start to 0 when we first start
        $startFrom = $contentStart = $contentEnd = 0;
        // If we find new content to add to our array, determined by out start and end symbols, we will find the string in between the delimeters and add it to our array
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            // Find Start of content
            $contentStart += $startDelimiterLength;
            // Find End of content
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
                if (false === $contentEnd) {
                    break;
                }
            // ADD the found ID to Array $contents
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            // Reset the start element to after the element we just found and repeat the loop
            $startFrom = $contentEnd + $endDelimiterLength;
        }
        return $contents;
    }


    // Liefert Resultat einer SQL Abfrage der tl_ixe_employeedata mit entsprechenden IDs der Mitarbeiter als assoziatives Array zurück
    public function getEmployeeDataByIds($arrIds) {
        // Wir bauen eine query für die Datenbank zusammen. Wir haben die ID(s) der Mitarbeiter in der Variable $arrIds an uns übergeben bekommen, die wir jetzt nutzen können um eine SQL Abfrage für die Tabelle der Employee zu bauen
        $query = "SELECT * FROM tl_ixe_employeedata WHERE ";
        $idQuery = "id IN(";

        // Wenn das Array mit den IDs von Anfang an leer ist, schreiben wir in die ID Abfrage eine NULL für einen leeren Wert, damit wir keine SQL Errors bekommen und schließen die Abfrage mit einem ')' ab
        if (empty($arrIds)) {
            $idQuery .= "NULL";
            $idQuery .= ")";

        // Wenn wir ein existierendes Array übergeben bekommen haben, dann bauen wir daraus eine IN(xx,yy,zz) Abfrage für die IDs
        } else {
            // Wir brauchen noch einen $counter und eine $arrLen Gesamtlänge des Arrays, um den letzten Durchlauf der foreach Schleife abfragen zu können, um die Klammer zu setzen zu können und das letzte Komma zu entfernen
            $counter = 0;
            $arrLen = count($arrIds);
            foreach($arrIds as $key => $currentArray) {
                // Füge die jetzige ID aus dem Array an die idQuery
                $currentId = $arrIds[$key];
                $idQuery .= "$currentId".",";
                // Wenn wir den letzten Durchlauf haben wie gesagt: Klammer schließen und Komma entfernen
                if ($counter == $arrLen - 1) {
                    $idQuery = substr($idQuery, 0, -1);
                    $idQuery .= ")";
                }
                // Erhöhe den Counter nach jedem Durchlauf des Arrays, um den Vergleich mit $arrLen für den letzten Durchlauf vollziehen zu können
                $counter++;
            }
        }
        $query .= $idQuery;
        // Jetzt hängen wir noch eine AND Klausel an welche nach Veröffentlichung prüft, sowie eine Sortierung der Mitarbeiter nach Sortierindex und Nachname von A - Z durchführt
        $query .= " AND published = 1 ORDER BY `sortingIndex` DESC, `name` ASC";

        // Datenbankabfrage abfeuern und geparstes Resultat zurück geben
        $res = \Database::getInstance()->prepare($query)->execute()->fetchAllAssoc();
        return $res;
    }


    // Liefert Resultat einer SQL Abfrage der tl_ixe_employeedata für alle Ansprechpartner Daten zurück
    public function getAllEmployeeData() {
        // Frage alle Daten aus der tl_ixe_employeedata ab und gib sie als Ausgabe zurück
        $res = \Database::getInstance()->prepare("SELECT * FROM tl_ixe_employeedata WHERE published = 1 ORDER BY `sortingIndex` DESC, `name` ASC")->execute()->fetchAllAssoc();
        return $res;
    }


    // Aus einem Array mit IDs die Namen aus der Tabelle tl_ixe_departement_lang ziehen und Kommagetrennt in einem String speichern
    public function getDepartementNames($arrIds)
    {
        // Wir bauen eine query für die Datenbanktabelle tl_ixe_departement für die Namen der Abteilungen zusammen - wir haben die IDs der Abteilungen und damit auch PIDs von der Übersetzungstabelle
        $query = "SELECT departementname FROM tl_ixe_departement WHERE ";

        // Wir bauen eine IN query für PIDs separat auf und hängen Sie dann an die Query dran
        $idQuery = "id IN(";
        // Das Array mit den IDs (z.B. Array ( [0] => 7 [1] => 9 [2] => 4 )) durchlaufen wir jetzt
        // Wenn das Array mir den IDs von Anfang an leer ist, schreiben wir in die ID Abfrage eine NULL für einen leeren Wert, damit wir keine SQL Errors bekommen und schließen die Abfrage mit einem ')' ab
        if (empty($arrIds)) {
            $idQuery .= "NULL";
            $idQuery .= ")";

        // Wenn wir ein existierendes Array übergeben bekommen haben, dann bauen wir daraus eine IN(xx,yy,zz) Abfrage für die IDs
        } else {
            // Wir brauchen noch einen $counter und eine $arrLen Gesamtlänge des Arrays, um den letzten Durchlauf der foreach Schleife abfragen zu können, um die Klammer zu setzen zu können und das letzte Komma zu entfernen
            $counter = 0;
            $arrLen = count($arrIds);
            foreach($arrIds as $key => $currentArray) {
                // Füge die jetzige ID aus dem Array an die idQuery
                $currentId = $arrIds[$key];
                $idQuery .= "$currentId".",";
                // Wenn wir den letzten Durchlauf haben wie gesagt: Klammer schließen und Komma entfernen
                if ($counter == $arrLen - 1) {
                    $idQuery = substr($idQuery, 0, -1);
                    $idQuery .= ")";
                }
                // Erhöhe den Counter nach jedem Durchlauf des Arrays, um den Vergleich mit $arrLen für den letzten Durchlauf vollziehen zu können
                $counter++;
            }
        }
        $query .= $idQuery;

        // Feuer die SQL Abfrage ab und speichere das Ergebnis unter einer $fetchRes Variable
        $res = \Database::getInstance()->prepare($query)->execute()->fetchAllAssoc();
        // Jetzt haben wir ein Array mit den Abteilungsnamen und müssen jetzt noch einen String daraus machen, damit wir leichter verarbeiten können
        // Array ( [0] => Array ( [departementname_title] => Development ) [1] => Array ( [departementname_title] => Technik ) [2] => Array ( [departementname_title] => Yes ) )

        // String aus Array aufbereiten
        $departementsString = "";
        $counter = 0;
        $arrLen = count($arrIds);
        foreach($res as $key => $currentArray) {
            // Für jeden Array Durchlauf die Abgefragen Abteilungsnamen an den String anknüpfen und mit Komma trennen
            $departementName = $res[$key]['departementname'];
            $departementsString .= "$departementName".", ";
            // Im letzten errechneten Durchlauf das letzte Leerzeichen und Komma entfernen
            if ($counter == $arrLen - 1) {
                $departementsString = substr($departementsString, 0, -2);
            }
            $counter++;
        }
        // Fertig, String mit Kommaliste im FE ausgeben. ("Development, Technik, Yes")
        return $departementsString;
    }


    // Aus einem Array mit IDs die Namen aus der Tabelle tl_ixe_departement_lang ziehen und Kommagetrennt in einem String speichern
    public function getDepartementTranslations($arrIds)
    {
        // Sprache der Seite aus den $GLOBALS auslesen um Inhalte entsprechend für diese Sprache auszugeben
        $language = '"'.$GLOBALS['TL_LANGUAGE'].'"';
        $langToUpper = strtoupper($language);
        if (empty($language)) {
            $language = "NULL";
        }

        // String für Ausgabe instanzieren
        $departementsString = "";

        // Wir bauen eine query für die Datenbank zusammen, wir haben die IDs der Abteilungen und damit auch PIDs von der Übersetzungstabelle
        $query = "SELECT departementname_title FROM tl_ixe_departement_lang WHERE ";

        // Wir bauen eine IN query für PIDs separat auf und hängen Sie dann an die Query dran
        $pidQuery = "pid IN(";
        // Das Array mit den IDs (z.B. Array ( [0] => 7 [1] => 9 [2] => 4 )) durchlaufen wir jetzt und bauen eine SQL IN(xx,yy,zz) query für departement PIDs daraus
        // Wenn das Array mir den IDs von Anfang an leer ist, schreiben wir in die ID Abfrage eine NULL für einen leeren Wert, damit wir keine SQL Errors bekommen und schließen die Abfrage mit einem ')' ab
        if (empty($arrIds)) {
            $pidQuery .= "NULL";
            $pidQuery .= ")";
            // Wenn keine Abteilung gewählt wurde, in die Fehlermeldung schreiben
            $departementsString = $GLOBALS['IX_EB']['MSC']['no_departement_defined'];

        // Wenn wir ein existierendes Array übergeben bekommen haben, dann bauen wir daraus eine IN(xx,yy,zz) Abfrage für die IDs
        } else {
            // Wir brauchen noch einen $counter und eine $arrLen Gesamtlänge des Arrays, um den letzten Durchlauf der foreach Schleife abfragen zu können, um die Klammer zu setzen zu können und das letzte Komma zu entfernen
            $counter = 0;
            $arrLen = count($arrIds);
            foreach($arrIds as $key => $currentArray) {
                // Füge die jetzige ID aus dem Array an die pidQuery
                $currentId = $arrIds[$key];
                $pidQuery .= "$currentId".",";
                // Wenn wir den letzten Durchlauf haben wie gesagt: Klammer schließen und Komma entfernen
                if ($counter == $arrLen - 1) {
                    $pidQuery = substr($pidQuery, 0, -1);
                    $pidQuery .= ")";
                }
                // Erhöhe den Counter nach jedem Durchlauf des Arrays, um den Vergleich mit $arrLen für den letzten Durchlauf vollziehen zu können
                $counter++;
            }
        }
        $query .= $pidQuery;

        // Jetzt hängen wir noch eine AND Klausel für die Sprache an, sowie eine Sortierung der Abteilungsnamen von A - Z
        $query .= " AND departementname_lang = $language ORDER BY departementname_title ASC";

        // Feuer die SQL Abfrage ab und speichere das Ergebnis unter einer $fetchRes Variable
        $res = \Database::getInstance()->prepare($query)->execute();
        $fetchRes = $res->fetchAllAssoc();

        // Wenn es kein Resultat gibt und der String noch leer ist, gibt es keine Übersetzungen
        if (empty($fetchRes) && empty($departementsString)) {
            $departementsString = sprintf($GLOBALS['IX_EB']['MSC']['no_departement_for_language'], $langToUpper);
        }

        // Array ( [0] => Array ( [departementname_title] => Development ) [1] => Array (...) [2] => Array (...)
        // Jetzt haben wir ein Array mit den Abteilungsnamen und müssen jetzt noch einen String daraus machen, damit wir leichter verarbeiten können
        if ($fetchRes) {
            $counter = 0;
            $arrLen = count($fetchRes);
            foreach($fetchRes as $key => $currentArray) {
                // Für jeden Array Durchlauf die Abgefragen Abteilungsnamen an den String anknüpfen und mit Komma trennen
                $departementName = $fetchRes[$key]['departementname_title'];
                $departementsString .= "$departementName".", ";
                // Im letzten errechneten Durchlauf das letzte Leerzeichen und Komma entfernen
                if ($counter == $arrLen - 1) {
                    $departementsString = substr($departementsString, 0, -2);
                }
                $counter++;
            }
        }

        // Fertig, String mit Kommaliste im FE ausgeben. ("Development, Technik, Yes")
        return $departementsString;
    }


    // Bearbeitet die generalisierten Daten aus einer SQL Abfrage der Tabelle (Bild pfade rausziehen, margin setzen etc.) und liefert diese dann an die /Classes Funktion zurück, damit wir hier die Daten ans Template übergeben können
    public function prepareArrayDataForTemplate($res, &$Template)
    {
        foreach($res as $key => $currentArray) {
            // Ein paar Variablen werden noch ein bisschen bearbeitet / verarbeitet, bevor sie dann abgeändert aber richtig ans Template übergeben werden
            $departementsIDs = HelperClass::getIdsByDelimiter($currentArray['departementCheckList'], '"', '"'); // Array mit IDs von den ausgewählten Abteilungen im Picker unter tl_ixe_departement (pids von tl_ixe_departement_lang)
            $departementsString = HelperClass::getDepartementTranslations($departementsIDs); // String mit Kommagetrennten Abteilungsnamen aus der tl_ixe_departement_lang Tabelle. ALPHABETISCH ABSTEIGEND SORTIERT!
            // Bearbeitete Variablen im $res überschreiben und dann das überarbeitete Array an Template geben
            $res[$key]['departementCheckList'] = $departementsString;
            // In externer Funktion für die momentane Sprache die Sprachenabhängigen Eingabe - BLOBS aufbereiten und in $res überschreiben
            $res[$key] = HelperClass::prepareArrayForLanguage($res[$key]);
            // Ein Bild wird nicht als Pfad direkt abgespeichert, sondern per UUID. Diese haben den Vorteil, dass die Datei verschoben werden kann und immer noch diese ID besitzt, man das Bild dadurch also nicht verliert im Frontend und der Datenbankzuweisung. Nachteil ist, dass wir hier noch ein bisschen proccessing machen müssen, um den Pfad wieder rauszubekommen
            // Ein Objekt für das Bild erstellen anhand der UUID (findet Pfad vom Bild, sowie weitere Meta infos)
            // Wenn addImage angekreuzt ist und es eine UUID gibt, plus das eine Datei zu dieser UUID gefunden wurde, überschreibe den Pfad und übergebe die Variable an das Ausgabearray
            if ($currentArray['singleSRC'] != '') {
                // Finde den Standort des Bildes
                $objFile = \FilesModel::findByUuid($currentArray['singleSRC']);
                if($objFile) {
                    $objFile = \FilesModel::findByUuid($currentArray['singleSRC']);
                    $res[$key]['singleSRC'] = $objFile->path;
                }
                // Schreibe die entsprechenden Daten und Einstellungen des Bildes (bei findByUuid abgefragt) in ein Objekt über die Contao Funktion addImageToTemplate (Funktion innerhalb /vendor/contao/core-bundle/src/Resources/contao/library/Contao/Controller.php)
                $obj = new \stdClass();
                \Controller::addImageToTemplate($obj, $res[$key], null, null, $objModel[$key]);
                // Bei der Caption braucht das Objekt noch einen Schubser an die richtige Stelle, damit das Template mit der insert image Funktion die Caption findet
                $obj->caption = $currentArray['caption'];
                // Schreibe das aufbereitete Objekt in ein eigenes neues Array Feld 'arrPicture' für $res
                $res[$key]['arrPicture'][] = $obj;

                // Nicht mehr benötigte Inhalte des Arrays nach Bild Aufbereitung noch kurz aufräumen für sauberes assoziatives Array
                unset($res[$key]['singleSRC']);
                unset($res[$key]['size']);
                unset($res[$key]['imagemargin']);
                unset($res[$key]['overwriteMeta']);
                unset($res[$key]['alt']);
                unset($res[$key]['caption']);
                unset($res[$key]['imageTitle']);
                unset($res[$key]['imageUrl']);
            }
        }
        return $res;
    }


    // Wie prepareArrayDataForTemplate, nur dass hier noch die IDs der Abteilungen berücksichtigt werden müssen
    public function prepareArrayDataForTemplateByDepartementpicker($departementCheckboxesIDs, $res)
    {
        foreach($res as $key => $currentArray) {
            // Ein paar Variablen werden noch ein bisschen bearbeitet / verarbeitet, bevor sie dann abgeändert aber richtig ans Template übergeben werden
            $departementsIDs = HelperClass::getIdsByDelimiter($currentArray['departementCheckList'], '"', '"'); // Array mit IDs von den ausgewählten Abteilungen im Picker unter tl_ixe_departement (pids von tl_ixe_departement_lang)
            $checkIfIsInDepartement = array_intersect($departementCheckboxesIDs, $departementsIDs);
            if ($res[$key]['addDepartement'] == 1 && !empty($checkIfIsInDepartement)) {
                $departementsString = HelperClass::getDepartementTranslations($departementsIDs); // String mit Kommagetrennten Abteilungsnamen aus der tl_ixe_departement_lang Tabelle. ALPHABETISCH ABSTEIGEND SORTIERT!

                // Bearbeitete Variablen im $res überschreiben und dann das überarbeitete Array an Template geben
                $departementsString = HelperClass::getDepartementTranslations($departementsIDs); // String mit Kommagetrennten Abteilungsnamen aus der tl_ixe_departement_lang Tabelle. ALPHABETISCH ABSTEIGEND SORTIERT!
                // Bearbeitete Variablen im $res überschreiben und dann das überarbeitete Array an Template geben
                $res[$key]['departementCheckList'] = $departementsString;

                // In externer Funktion für die momentane Sprache die Sprachenabhängigen Eingabe - BLOBS aufbereiten und in $res überschreiben
                $res[$key] = HelperClass::prepareArrayForLanguage($res[$key]);

                // Ein Bild wird nicht als Pfad direkt abgespeichert, sondern per UUID. Diese haben den Vorteil, dass die Datei verschoben werden kann und immer noch diese ID besitzt, man das Bild dadurch also nicht verliert im Frontend und der Datenbankzuweisung. Nachteil ist, dass wir hier noch ein bisschen proccessing machen müssen, um den Pfad wieder rauszubekommen
                // Ein Objekt für das Bild erstellen anhand der UUID (findet Pfad vom Bild, sowie weitere Meta infos)
                // Wenn addImage angekreuzt ist und es eine UUID gibt, plus das eine Datei zu dieser UUID gefunden wurde, überschreibe den Pfad und übergebe die Variable an das Ausgabearray
                if ($currentArray['singleSRC'] != '') {
                    // Finde den Standort des Bildes
                    $objFile = \FilesModel::findByUuid($currentArray['singleSRC']);
                    if($objFile) {
                        $objFile = \FilesModel::findByUuid($currentArray['singleSRC']);
                        $res[$key]['singleSRC'] = $objFile->path;
                    }
                    // Schreibe die entsprechenden Daten und Einstellungen des Bildes (bei findByUuid abgefragt) in ein Objekt über die Contao Funktion addImageToTemplate (Funktion innerhalb /vendor/contao/core-bundle/src/Resources/contao/library/Contao/Controller.php)
                    $obj = new \stdClass();
                    \Controller::addImageToTemplate($obj, $res[$key], null, null, $objModel[$key]);
                    // Bei der Caption braucht das Objekt noch einen Schubser an die richtige Stelle, damit das Template mit der insert image Funktion die Caption findet
                    $obj->caption = $currentArray['caption'];
                    // Schreibe das aufbereitete Objekt in ein eigenes neues Array Feld 'arrPicture' für $res
                    $res[$key]['arrPicture'][] = $obj;

                    // Nicht mehr benötigte Inhalte des Arrays nach Bild Aufbereitung noch kurz aufräumen für sauberes assoziatives Array
                    unset($res[$key]['singleSRC']);
                    unset($res[$key]['size']);
                    unset($res[$key]['imagemargin']);
                    unset($res[$key]['overwriteMeta']);
                    unset($res[$key]['alt']);
                    unset($res[$key]['caption']);
                    unset($res[$key]['imageTitle']);
                    unset($res[$key]['imageUrl']);
                }
            } else {
                unset($res[$key]);
            }
        }
        return $res;
    }


    // Aus MultiColumWizard BLOBs per assoziative Arrays den Sprachinhalt für die entsprechende Sprachverseion der Seite aufbereiten
    public function prepareArrayForLanguage($res)
    {
        // Sprache der Seite aus den $GLOBALS auslesen um Inhalte entsprechend für diese Sprache auszugeben
        $language = $GLOBALS['TL_LANGUAGE'];
        $langToUpper = strtoupper($language);

        // Wir bereiten aus dem gelieferten BLOB ein assoziatives Array mit den Inhalten des BLOBS für jede Sprache mit der Contao Funktion deserialize vor, um dieses Array dann per foreach durchlaufen zu können
        $tmpSalutation = \StringUtil::deserialize($res['salutation'], true);
        // Vor dem Durchlauf des Arrays nehmen wir einen boolean pointer auf false. Dieser Pointer soll uns zeigen ob ein Inhalt für die jetzige $language gefunden wurde, denn falls nicht setzen wir den Inhalt auf einen leeren String um nicht den wirrwar BLOB im Frontend auszugeben
        $tmpSalutationstatus = false;
        foreach($tmpSalutation as $key => $currentArray) {
            if ($language == $currentArray['salutation_lang']) {
                // Wir geben dem Redakteur die Möglichkeit per non break spaces einen "leeren" Inhalt in die Verwaltung einzugeben (auf kosten der rgex Überprüfung für Telefonnummern etc.), denn dann wird der String leer gesetzt
                if ($currentArray['salutation_content'] == "[nbsp]" || $currentArray['salutation_content'] == "&nbsp" || $currentArray['salutation_content'] == "&nbsp;") {
                    $currentArray['salutation_content'] = "";
                }
                $res['salutation'] = $currentArray['salutation_content'];
                $tmpSalutationstatus = true;
            }
        }
        if ($tmpSalutationstatus==false) {
            unset($res['salutation']);
            $res['salutation_empty'] = sprintf($GLOBALS['IX_EB']['MSC']['no_language'], '"Salutation"', '"'.$langToUpper.'"');
        }

        // Wir bereiten aus dem gelieferten BLOB ein assoziatives Array mit den Inhalten des BLOBS für jede Sprache mit der Contao Funktion deserialize vor, um dieses Array dann per foreach durchlaufen zu können
        $tmpTitle = \StringUtil::deserialize($res['title'], true);
        // Vor dem Durchlauf des Arrays nehmen wir einen boolean pointer auf false. Dieser Pointer soll uns zeigen ob ein Inhalt für die jetzige $language gefunden wurde, denn falls nicht setzen wir den Inhalt auf einen leeren String um nicht den wirrwar BLOB im Frontend auszugeben
        $tmpTitlestatus = false;
        foreach($tmpTitle as $key => $currentArray) {
            if ($language == $currentArray['title_lang']) {
                // Wir geben dem Redakteur die Möglichkeit per non break spaces einen "leeren" Inhalt in die Verwaltung einzugeben (auf kosten der rgex Überprüfung für Telefonnummern etc.), denn dann wird der String leer gesetzt
                if ($currentArray['title_content'] == "[nbsp]" || $currentArray['title_content'] == "&nbsp" || $currentArray['title_content'] == "&nbsp;") {
                    $currentArray['title_content'] = "";
                }
                $res['title'] = $currentArray['title_content'];
                $tmpTitlestatus = true;
            }
        }
        if ($tmpTitlestatus==false) {
            unset($res['title']);
            $res['title_empty'] = sprintf($GLOBALS['IX_EB']['MSC']['no_language'], '"Title"', '"'.$langToUpper.'"');
        }

        // Wir bereiten aus dem gelieferten BLOB ein assoziatives Array mit den Inhalten des BLOBS für jede Sprache mit der Contao Funktion deserialize vor, um dieses Array dann per foreach durchlaufen zu können
        $tmpJobtitle = \StringUtil::deserialize($res['jobtitle'], true);
        // Vor dem Durchlauf des Arrays nehmen wir einen boolean pointer auf false. Dieser Pointer soll uns zeigen ob ein Inhalt für die jetzige $language gefunden wurde, denn falls nicht setzen wir den Inhalt auf einen leeren String um nicht den wirrwar BLOB im Frontend auszugeben
        $tmpJobtitlestatus = false;
        foreach($tmpJobtitle as $key => $currentArray) {
            if ($language == $currentArray['jobtitle_lang']) {
                // Wir geben dem Redakteur die Möglichkeit per non break spaces einen "leeren" Inhalt in die Verwaltung einzugeben (auf kosten der rgex Überprüfung für Telefonnummern etc.), denn dann wird der String leer gesetzt
                if ($currentArray['jobtitle_content'] == "[nbsp]" || $currentArray['jobtitle_content'] == "&nbsp" || $currentArray['jobtitle_content'] == "&nbsp;") {
                    $currentArray['jobtitle_content'] = "";
                }
                $res['jobtitle'] = $currentArray['jobtitle_content'];
                $tmpJobtitlestatus = true;
            }
        }
        if ($tmpJobtitlestatus==false) {
            unset($res['jobtitle']);
            $res['jobtitle_empty'] = sprintf($GLOBALS['IX_EB']['MSC']['no_language'], '"Jobtitle"', '"'.$langToUpper.'"');
        }

        // Wir bereiten aus dem gelieferten BLOB ein assoziatives Array mit den Inhalten des BLOBS für jede Sprache mit der Contao Funktion deserialize vor, um dieses Array dann per foreach durchlaufen zu können
        $tmpPhone = \StringUtil::deserialize($res['phone'], true);
        // Vor dem Durchlauf des Arrays nehmen wir einen boolean pointer auf false. Dieser Pointer soll uns zeigen ob ein Inhalt für die jetzige $language gefunden wurde, denn falls nicht setzen wir den Inhalt auf einen leeren String um nicht den wirrwar BLOB im Frontend auszugeben
        $tmpPhonestatus = false;
        foreach($tmpPhone as $key => $currentArray) {
            if ($language == $currentArray['phone_lang']) {
                // Wir geben dem Redakteur die Möglichkeit per non break spaces einen "leeren" Inhalt in die Verwaltung einzugeben (auf kosten der rgex Überprüfung für Telefonnummern etc.), denn dann wird der String leer gesetzt
                if ($currentArray['phone_content'] == "[nbsp]" || $currentArray['phone_content'] == "&nbsp" || $currentArray['phone_content'] == "&nbsp;") {
                    $currentArray['phone_content'] = "";
                }
                $res['phone'] = $currentArray['phone_content'];
                $res['phoneLinktext'] = $currentArray['phoneLinktext'];
                $tmpPhonestatus = true;
            }
        }
        if ($tmpPhonestatus==false) {
            unset($res['phone']);
            $res['phone_empty'] = sprintf($GLOBALS['IX_EB']['MSC']['no_language'], '"Phone"', '"'.$langToUpper.'"');
            $res['phoneLinktext'] = "";
        }

        // Wir bereiten aus dem gelieferten BLOB ein assoziatives Array mit den Inhalten des BLOBS für jede Sprache mit der Contao Funktion deserialize vor, um dieses Array dann per foreach durchlaufen zu können
        $tmpMobile = \StringUtil::deserialize($res['mobile'], true);
        // Vor dem Durchlauf des Arrays nehmen wir einen boolean pointer auf false. Dieser Pointer soll uns zeigen ob ein Inhalt für die jetzige $language gefunden wurde, denn falls nicht setzen wir den Inhalt auf einen leeren String um nicht den wirrwar BLOB im Frontend auszugeben
        $tmpMobilestatus = false;
        foreach($tmpMobile as $key => $currentArray) {
            if ($language == $currentArray['mobile_lang']) {
                // Wir geben dem Redakteur die Möglichkeit per non break spaces einen "leeren" Inhalt in die Verwaltung einzugeben (auf kosten der rgex Überprüfung für Telefonnummern etc.), denn dann wird der String leer gesetzt
                if ($currentArray['mobile_content'] == "[nbsp]" || $currentArray['mobile_content'] == "&nbsp" || $currentArray['mobile_content'] == "&nbsp;") {
                    $currentArray['mobile_content'] = "";
                }
                $res['mobile'] = $currentArray['mobile_content'];
                $res['mobileLinktext'] = $currentArray['mobileLinktext'];
                $tmpMobilestatus = true;
            }
        }
        if ($tmpMobilestatus==false) {
            unset($res['mobile']);
            $res['mobile_empty'] = sprintf($GLOBALS['IX_EB']['MSC']['no_language'], '"Mobile"', '"'.$langToUpper.'"');
            $res['mobileLinktext'] = "";
        }

        // Wir bereiten aus dem gelieferten BLOB ein assoziatives Array mit den Inhalten des BLOBS für jede Sprache mit der Contao Funktion deserialize vor, um dieses Array dann per foreach durchlaufen zu können
        $tmpFax = \StringUtil::deserialize($res['fax'], true);
        // Vor dem Durchlauf des Arrays nehmen wir einen boolean pointer auf false. Dieser Pointer soll uns zeigen ob ein Inhalt für die jetzige $language gefunden wurde, denn falls nicht setzen wir den Inhalt auf einen leeren String um nicht den wirrwar BLOB im Frontend auszugeben
        $tmpFaxstatus = false;
        foreach($tmpFax as $key => $currentArray) {
            if ($language == $currentArray['fax_lang']) {
                // Wir geben dem Redakteur die Möglichkeit per non break spaces einen "leeren" Inhalt in die Verwaltung einzugeben (auf kosten der rgex Überprüfung für Telefonnummern etc.), denn dann wird der String leer gesetzt
                if ($currentArray['fax_content'] == "[nbsp]" || $currentArray['fax_content'] == "&nbsp" || $currentArray['fax_content'] == "&nbsp;") {
                    $currentArray['fax_content'] = "";
                }
                $res['fax'] = $currentArray['fax_content'];
                $res['faxLinktext'] = $currentArray['faxLinktext'];
                $tmpFaxstatus = true;
            }
        }
        if ($tmpFaxstatus==false) {
            unset($res['fax']);
            $res['fax_empty'] = sprintf($GLOBALS['IX_EB']['MSC']['no_language'], '"Fax"', '"'.$langToUpper.'"');
            $res['faxLinktext'] = "";
        }

        // Wir bereiten aus dem gelieferten BLOB ein assoziatives Array mit den Inhalten des BLOBS für jede Sprache mit der Contao Funktion deserialize vor, um dieses Array dann per foreach durchlaufen zu können
        $tmpMail = \StringUtil::deserialize($res['email'], true);
        // Vor dem Durchlauf des Arrays nehmen wir einen boolean pointer auf false. Dieser Pointer soll uns zeigen ob ein Inhalt für die jetzige $language gefunden wurde, denn falls nicht setzen wir den Inhalt auf einen leeren String um nicht den wirrwar BLOB im Frontend auszugeben
        $tmpMailstatus = false;
        foreach($tmpMail as $key => $currentArray) {
            if ($language == $currentArray['email_lang']) {
                // Wir geben dem Redakteur die Möglichkeit per non break spaces einen "leeren" Inhalt in die Verwaltung einzugeben (auf kosten der rgex Überprüfung für Telefonnummern etc.), denn dann wird der String leer gesetzt
                if ($currentArray['email_content'] == "[nbsp]" || $currentArray['email_content'] == "&nbsp" || $currentArray['email_content'] == "&nbsp;") {
                    $currentArray['email_content'] = "";
                }
                $res['email'] = $currentArray['email_content'];
                $res['emailLinktext'] = $currentArray['emailLinktext'];
                $tmpMailstatus = true;
            }
        }
        if ($tmpMailstatus==false) {
            unset($res['email']);
            $res['email_empty'] = sprintf($GLOBALS['IX_EB']['MSC']['no_language'], '"E-Mail"', '"'.$langToUpper.'"');
            $res['emailLinktext'] = "";
        }
        return $res;
    }
}
