<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */
 
/**
 * Namespace
 */
namespace ixtensa\AnsprechpartnerBundle\Helper;

class HelperClass extends \Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->import('Database');
    }


    // Liefert Resultat einer SQL Abfrage der tl_bemod_ansprechpartner mit einer entsprechenden ID zurück
    public function getAnsprechpartnerDataById($ansprechpartnerId) {
        if (empty($ansprechpartnerId)) {
            $ansprechpartnerId = "NULL";
        }
        $res = \Database::getInstance()->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id = ?")->execute($ansprechpartnerId)->fetchAssoc();
        return $res;
    }


    // Liefert Resultat einer SQL Abfrage der tl_bemod_ansprechpartner mit mehreren entsprechenden IDs zurück
    public function getMultipleAnsprechpartnerDataByIds($arrIds) {
        // Der global $objPage beinhaltet abrufbare Metainformationen der Seite, wir nutzen ihn für die 'language' der Seite später
        global $objPage;

        // Wir bauen eine query für die Datenbank zusammen. Wir haben die IDs der Ansprechpartner in der Variable $arrIds an uns übergeben bekommen, die wir jetzt nutzen können um eine SQL Abfrage für die Tabelle der Ansprechpartner zu bauen
        $query = "SELECT * FROM tl_bemod_ansprechpartner WHERE ";
        $pidQuery = "id IN(";

        // Wenn das Array mir den IDs von Anfang an leer ist, schreiben wir in die ID Abfrage eine NULL für einen leeren Wert, damit wir keine SQL Errors bekommen und schließen die Abfrage mit einem ')' ab
        if (empty($arrIds)) {
            $pidQuery .= "NULL";
            $pidQuery .= ")";

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
        $query .= " AND published = 1 ORDER BY `sortingIndex` DESC, `name` ASC";

        // Datenbankabfrage abfeuern und geparstes Resultat zurück geben
        $res = \Database::getInstance()->prepare($query)->execute()->fetchAllAssoc();
        return $res;
    }

    // Liefert Resultat einer SQL Abfrage der tl_bemod_ansprechpartner mit und für alle(n) Daten zurück
    public function getAllAnsprechpartnerData() {
        $res = \Database::getInstance()->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE published = 1 ORDER BY `sortingIndex` DESC, `name` ASC")->execute()->fetchAllAssoc();
        return $res;
    }


    // Aus einem String: "[ID] Begrüßung Titel Name Vorname (...)" ein Array mit den entzogenen IDs des Ansprechpartners machen.
    public function getAnsprechpartnerIds($str)
    {
        // We see the IDs are always imbedded within these Symbols. These are our start and end inicators
        $startDelimiter = '[';
        $endDelimiter = ']';
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


    // Departement IDs aus einem BLOB in ein Array entziehen:
    // a:3:{i:0;s:1:"7";i:1;s:1:"9";i:2;s:1:"4";}
    // -> Array ( [0] => 7 [1] => 9 [2] => 4 )
    public function getDepartementIds($str)
    {
        // We see the IDs are always imbedded within " Symbols. These are our start and end inicators
        $startDelimiter = '"';
        $endDelimiter = '"';
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


    // Aus einem Array mit IDs die Namen aus der Tabelle tl_bemod_abteilungen_lang ziehen und Kommagetrennt in einem String speichern
    public function getDepartementNames($arrIds)
    {
        // Der global $objPage beinhaltet abrufbare Metainformationen der Seite, wir nutzen ihn für die 'language' der Seite später
        global $objPage;

        // Wir bauen eine query für die Datenbank zusammen, wir haben die IDs der Abteilungen und damit auch PIDs von der Übersetzungstabelle
        $query = "SELECT abtname FROM tl_bemod_abteilungen WHERE ";

        // Wir bauen eine IN query für PIDs separat auf und hängen Sie dann an die Query dran
        $pidQuery = "id IN(";
        // Das Array mit den IDs (z.B. Array ( [0] => 7 [1] => 9 [2] => 4 )) durchlaufen wir jetzt
        // Wenn das Array mir den IDs von Anfang an leer ist, schreiben wir in die ID Abfrage eine NULL für einen leeren Wert, damit wir keine SQL Errors bekommen und schließen die Abfrage mit einem ')' ab
        if (empty($arrIds)) {
            $pidQuery .= "NULL";
            $pidQuery .= ")";

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

        // Feuer die SQL Abfrage ab und speichere das Ergebnis unter einer $fetchRes Variable
        $res = \Database::getInstance()->prepare($query)->execute()->fetchAllAssoc();
        // Jetzt haben wir ein Array mit den Abteilungsnamen und müssen jetzt noch einen String daraus machen, damit wir leichter verarbeiten können
        // Array ( [0] => Array ( [abtname_bez] => Development ) [1] => Array ( [abtname_bez] => Technik ) [2] => Array ( [abtname_bez] => Yes ) )

        // String aus Array aufbereiten
        $departementsString = "";
        $counter = 0;
        $arrLen = count($arrIds);
        foreach($res as $key => $currentArray) {
            // Für jeden Array Durchlauf die Abgefragen Abteilungsnamen an den String anknüpfen und mit Komma trennen
            $departementName = $res[$key]['abtname'];
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


    // Aus einem Array mit IDs die Namen aus der Tabelle tl_bemod_abteilungen_lang ziehen und Kommagetrennt in einem String speichern
    public function getDepartementTranslations($arrIds)
    {
        // Der global $objPage beinhaltet abrufbare Metainformationen der Seite, wir nutzen ihn für die 'language' der Seite später
        global $objPage;

        // Sprache in String wandeln und abspeichern – wird ganz zum Schluss an SQL query angehängt.
        $language = "'".$objPage->language."'";
        $fallbackLanguage = "'de'";

        // Wir bauen eine query für die Datenbank zusammen, wir haben die IDs der Abteilungen und damit auch PIDs von der Übersetzungstabelle
        $query = "SELECT abtname_bez FROM tl_bemod_abteilungen_lang WHERE ";
        // Die $fallbackQuery ist dazu da, wenn in der entsprechenden Sprache keine Übersetzungen vorliegen, nehmen wir als Standard die DE Übersetzungen
        $fallbackQuery = "SELECT abtname_bez FROM tl_bemod_abteilungen_lang WHERE ";

        // Wir bauen eine IN query für PIDs separat auf und hängen Sie dann an die Query dran
        $pidQuery = "pid IN(";
        // Das Array mit den IDs (z.B. Array ( [0] => 7 [1] => 9 [2] => 4 )) durchlaufen wir jetzt und bauen eine SQL IN(xx,yy,zz) query für departement PIDs daraus
        // Wenn das Array mir den IDs von Anfang an leer ist, schreiben wir in die ID Abfrage eine NULL für einen leeren Wert, damit wir keine SQL Errors bekommen und schließen die Abfrage mit einem ')' ab
        if (empty($arrIds)) {
            $pidQuery .= "NULL";
            $pidQuery .= ")";

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
        $fallbackQuery .= $pidQuery;

        // Jetzt hängen wir noch eine AND Klausel für die Sprache an, sowie eine Sortierung der Abteilungsnamen von A - Z
        $query .= " AND abtname_lang = $language ORDER BY abtname_bez ASC";
        $fallbackQuery .= " AND abtname_lang = $fallbackLanguage ORDER BY abtname_bez ASC";

        // Feuer die SQL Abfrage ab und speichere das Ergebnis unter einer $fetchRes Variable
        $res = \Database::getInstance()->prepare($query)->execute();
        $fetchRes = $res->fetchAllAssoc();
        // Wenn es kein Resultat in der Sprache der Seite gibt, feuer die $fallbackQuery für die deutschen 'de' Übersetzungen ab, diese sollten auf jeden Fall angelegt sein... Wenn nicht muss hier noch Redakteursarbeit ran
        if (empty($fetchRes)) {
            $res = \Database::getInstance()->prepare($fallbackQuery)->execute();
            $fetchRes = $res->fetchAllAssoc();
        }
        // Jetzt haben wir ein Array mit den Abteilungsnamen und müssen jetzt noch einen String daraus machen, damit wir leichter verarbeiten können
        // Array ( [0] => Array ( [abtname_bez] => Development ) [1] => Array ( [abtname_bez] => Technik ) [2] => Array ( [abtname_bez] => Yes ) )
        if (!empty($fetchRes)) {
            // String aus Array aufbereiten
            $departementsString = "";

            $counter = 0;
            $arrLen = count($fetchRes);
            foreach($fetchRes as $key => $currentArray) {
                // Für jeden Array Durchlauf die Abgefragen Abteilungsnamen an den String anknüpfen und mit Komma trennen
                $departementName = $fetchRes[$key]['abtname_bez'];
                $departementsString .= "$departementName".", ";
                // Im letzten errechneten Durchlauf das letzte Leerzeichen und Komma entfernen
                if ($counter == $arrLen - 1) {
                    $departementsString = substr($departementsString, 0, -2);
                }
                $counter++;
            }
        } else {
            $departementsString = "Keine Abteilung zur jetzigen Sprache vorhanden.";
        }

        // Fertig, String mit Kommaliste im FE ausgeben. ("Development, Technik, Yes")
        return $departementsString;
    }

    // Bearbeitet die generalisierten Daten aus einer SQL Abfrage der Tabelle (Bild pfade rausziehen, margin setzen etc.) und liefert diese dann an die /Classes Funktion zurück, damit wir hier die Daten ans Template übergeben können
    public function prepareArrayDataForTemplate($res)
    {
        foreach($res as $key => $currentArray) {
            // Ein paar Variablen werden noch ein bisschen bearbeitet / verarbeitet, bevor sie dann abgeändert aber richtig ans Template übergeben werden
            $departementsIDs = HelperClass::getDepartementIds($currentArray['departementCheckList']); // Array mit IDs von den ausgewählten Abteilungen im Picker unter tl_bemod_abteilungen (pids von tl_bemod_abteilungen_lang)
            $departementsString = HelperClass::getDepartementTranslations($departementsIDs); // String mit Kommagetrennten Abteilungsnamen aus der tl_bemod_abteilungen_lang Tabelle. ALPHABETISCH ABSTEIGEND SORTIERT!

            // Bearbeitete Variablen im $res überschreiben und dann das überarbeitete Array an Template geben
            $res[$key]['departementCheckList'] = $departementsString;

            // Ein Bild wird nicht als Pfad direkt abgespeichert, sondern per UUID. Diese haben den Vorteil, dass die Datei verschoben werden kann und immer noch diese ID besitzt, man das Bild dadurch also nicht verliert im Frontend und der Datenbankzuweisung. Nachteil ist, dass wir hier noch ein bisschen proccessing machen müssen, um den Pfad wieder rauszubekommen
            // Ein Objekt für das Bild erstellen anhand der UUID (findet Pfad vom Bild, sowie weitere Meta infos)
            $objFile = \FilesModel::findByUuid($currentArray['singleSRC']);
            // Pfad zum Bild in der Variable $path ablegen, wenn das Bild existiert
            if($objFile) {
                $path = $objFile->path;

                // In der Variable $size stecken die Werte für die Einstellungen der resizeImage Feldern, die werden mit deserialize zu einem Array gewandelt
                $size = deserialize($currentArray['resize']);
                // Die Funktion getImage nimmt aus den Variablen von $size (PathToDefaultImage, Width, Height, Mode) die Einstellungen für das neue resize Bild, die ausgewählt wurden und generiert dieses Bild, speichert es unter /assets/images/.. ab, sowie speichert den Pfad dort hin unter $src ab
                $src = \Controller::getImage($path, $size[0], $size[1], $size[2]);
                // Die 4 Eingabefelder "margin" aus dem DCA werden hier über generateMargin und deserialize Funktion in inline CSS Format umgewandelt und im Template dann weiter- und ausgegeben

                $margin = \Controller::generateMargin(deserialize($currentArray['margin']), 'margin');

                // Pfad zum Bild an Template geben
                $res[$key]['singleSRC'] = $path;
                // Der Pfad zum neuen resize Bild wird an das Template gegeben
                $res[$key]['resize'] = $src;
                $res[$key]['margin'] = $margin;
            }
        }
        return $res;
    }

    // Wie prepareArrayDataForTemplate, nur dass hier noch die IDs der Abteilungen berücksichtigt werden müssen
    public function prepareArrayDataForTemplateByDepartementpicker($departementCheckboxesIDs, $res)
    {
        foreach($res as $key => $currentArray) {
            // Ein paar Variablen werden noch ein bisschen bearbeitet / verarbeitet, bevor sie dann abgeändert aber richtig ans Template übergeben werden
            $departementsIDs = HelperClass::getDepartementIds($currentArray['departementCheckList']); // Array mit IDs von den ausgewählten Abteilungen im Picker unter tl_bemod_abteilungen (pids von tl_bemod_abteilungen_lang)

            $checkIfIsInDepartement = array_intersect($departementCheckboxesIDs, $departementsIDs);
            if (!empty($checkIfIsInDepartement)) {
                $departementsString = HelperClass::getDepartementTranslations($departementsIDs); // String mit Kommagetrennten Abteilungsnamen aus der tl_bemod_abteilungen_lang Tabelle. ALPHABETISCH ABSTEIGEND SORTIERT!

                // Bearbeitete Variablen im $res überschreiben und dann das überarbeitete Array an Template geben
                $res[$key]['departementCheckList'] = $departementsString;

                // Ein Bild wird nicht als Pfad direkt abgespeichert, sondern per UUID. Diese haben den Vorteil, dass die Datei verschoben werden kann und immer noch diese ID besitzt, man das Bild dadurch also nicht verliert im Frontend und der Datenbankzuweisung. Nachteil ist, dass wir hier noch ein bisschen proccessing machen müssen, um den Pfad wieder rauszubekommen
                // Ein Objekt für das Bild erstellen anhand der UUID (findet Pfad vom Bild, sowie weitere Meta infos)
                $objFile = \FilesModel::findByUuid($currentArray['singleSRC']);
                // Pfad zum Bild in der Variable $path ablegen, wenn das Bild existiert
                if($objFile) {
                    $path = $objFile->path;

                    // In der Variable $size stecken die Werte für die Einstellungen der resizeImage Feldern, die werden mit deserialize zu einem Array gewandelt
                    $size = deserialize($currentArray['resize']);
                    // Die Funktion getImage nimmt aus den Variablen von $size (PathToDefaultImage, Width, Height, Mode) die Einstellungen für das neue resize Bild, die ausgewählt wurden und generiert dieses Bild, speichert es unter /assets/images/.. ab, sowie speichert den Pfad dort hin unter $src ab
                    $src = \Controller::getImage($path, $size[0], $size[1], $size[2]);
                    // Die 4 Eingabefelder "margin" aus dem DCA werden hier über generateMargin und deserialize Funktion in inline CSS Format umgewandelt und im Template dann weiter- und ausgegeben

                    $margin = \Controller::generateMargin(deserialize($currentArray['margin']), 'margin');

                    // Pfad zum Bild an Template geben
                    $res[$key]['singleSRC'] = $path;
                    // Der Pfad zum neuen resize Bild wird an das Template gegeben
                    $res[$key]['resize'] = $src;
                    $res[$key]['margin'] = $margin;
                }
            } else {
                unset($res[$key]);
            }
        }
        return $res;
    }
}
