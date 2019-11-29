<?php

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

        // $this->db = $db;
    }

    // Get Departement IDs from BLOB like:
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


    public function queryDepartementNames($arrIds)
    {
        // Der global $objPage beinhaltet abrufbare Metainformationen der Seite, wir nutzen ihn für die 'language' der Seite später
        global $objPage;

        // Globals
        // Wichtig für die for Schleifen. Irgendwann muss diese Abbrechen -- per default auf 1000 gesetzt weil unwahrscheinlich hoch und irgendwann muss Schleife aufhören und String wird für FE zu groß
        $maxAbteilungen = 1000;
        $language = "'".$objPage->language."'";
        $fallbackLanguage = "'de'";

        // Wir bauen eine query für die Datenbank zusammen, wir haben die IDs der Abteilungen und damit auch PIDs von der Übersetzungstabelle
        $query = "SELECT abtname_bez FROM tl_bemod_abteilungen_lang WHERE ";
        // Die $fallbackQuery ist dazu da, wenn in der entsprechenden Sprache keine Übersetzungen vorliegen, nehmen wir die DE Übersetzungen
        $fallbackQuery = "SELECT abtname_bez FROM tl_bemod_abteilungen_lang WHERE ";

        // Wir bauen eine IN query für PIDs separat auf und hängen Sie dann an die Query dran
        $pidQuery = "pid IN(";
        // Das Array mit den IDs (z.B. Array ( [0] => 7 [1] => 9 [2] => 4 )) durchlaufen wir jetzt so oft, bis wir kein neues Element mehr 'poppen', also abhängen können -> Bis das Array leer ist
        // Für jeden gefunden Eintrag unter IDs erweitern wir die SQL Query um eine WHERE Klausel mit OR, damit bei mehreren PIDs mehrere Einträge ausgegeben werden.
        for ($counter=0; $counter < $maxAbteilungen; $counter++) {
            // Abhängen letztes Element
            $currentId = array_pop($arrIds);
            // Wenn es nicht leer ist, bau eine WHERE Klausel mit IN daraus
            if (!empty($currentId)) {
                $pidQuery .= " $currentId";
                $pidQuery .= ",";
            // Wenn das Array leer ist, brich die FOR Schleife ab und hänge die erstellte WHERE IN Klausel an die Queries an und schließe Sie mit einem ) ab nachdem du das letzte Komma entfernt hast
            } else {
                $pidQuery = substr($pidQuery, 0, -1);
                $pidQuery .= ")";
                // Wir sind durch alle IDs durch oder durch die maximale erlaubte Anzahl an IDs ($maxAbteilungen) -> die erweiterte $pidQuery zu den Queries anhängen
                $query .= $pidQuery;
                $fallbackQuery .= $pidQuery;
                break;
            }
        }

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


        // String aus Array aufbereiten
        $departementsString = "";
        for ($counter=0; $counter < $maxAbteilungen; $counter++) {
            $departementName = $fetchRes[$counter]['abtname_bez'];
            if (!empty($departementName)) {
                $departementsString .= "$departementName";
                $departementsString .= ", ";
            } else {
                $departementsString = substr($departementsString, 0, -2);
                break;
            }
        }
        // Fertig, String mit Kommaliste im FE ausgeben. ("Development, Technik, Yes")
        return $departementsString;
    }
}
