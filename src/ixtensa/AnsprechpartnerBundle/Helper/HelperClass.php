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
        // Fertig, String mit Kommaliste im FE ausgeben.
        return $departementsString;
    }




    public function query($rootId, $productName)
    {
        $queryRep = \Database::getInstance()->prepare("SELECT productLanguage.id, productLanguage.pid FROM tl_ix_product_language AS productLanguage, tl_ix_rep AS rep, tl_page AS page, tl_ix_product AS productName WHERE page.id = ? AND rep.id = page.addRepPicker AND productLanguage.alias = ? AND productLanguage.language = rep.language AND productLanguage.pid = productName.id")->execute($rootId, $productName);

        $resRep = $queryRep->fetchAllAssoc();

        return $resRep;
    }

    // tl_article ...
    public function queryArticle($rootId, $productName)
    {
        $queryRep = \Database::getInstance()->prepare("SELECT pid FROM tl_ix_product_language WHERE alias = ?")->execute($productName);

        $resRep = $queryRep->fetchAllAssoc();

        return $resRep;
    }

    // tl_article ...
    public function productIdAliasLang($productId, $catalogLang) {
      $queryProductLangId = \Database::getInstance()->prepare("SELECT id,alias FROM tl_ix_product_language WHERE pid = ? AND language = ?")->execute($productId, $catalogLang);

      $resRep = $queryProductLangId->fetchAssoc();

      return $resRep;
    }

    public function productIdLang($productId, $catalogLang) {
      $queryProductLangId = \Database::getInstance()->prepare("SELECT id FROM tl_ix_product_language WHERE pid = ? AND language = ?")->execute($productId, $catalogLang);

      $resRep = $queryProductLangId->fetchAssoc();

      return $resRep['id'];
    }


    public function queryForRootId($pid, $ptable)
    {
        if ($ptable == 'tl_article') {
            // get parent tl_page from tl_article
            $help = \Database::getInstance()->prepare("SELECT p.pid FROM tl_page AS p, tl_article AS a WHERE a.pid = p.id AND a.id = ?")->execute($pid);
            $resH = $help->fetchAllAssoc();
            $pagePid = $resH[0]['pid'];

            // get root page id
            $help2 = \Database::getInstance()->prepare("SELECT id FROM tl_page WHERE id = ?")->execute($pagePid);
            $resH2 = $help2->fetchAllAssoc();

            $pageRid = $resH2[0]['id'];

            return $pageRid; // root id
        }
    }

    public function queryForDownloads($productId)
    {
        $query = \Database::getInstance()->prepare("SELECT files.uuid, files.name FROM tl_files AS files, tl_ix_product_language AS product WHERE files.uuid = product.uuid AND product.id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();

        return $res;
    }

    public function queryForDownloadsPDF($productId)
    {
        $query = \Database::getInstance()->prepare("SELECT files.uuid, files.name FROM tl_files AS files, tl_ix_product_language AS product WHERE files.uuid = product.enclosure AND product.addEnclosure = 1 AND product.id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();

        return $res;
    }

    public function queryForDownloadsDl($productId, $no)
    {
        $query = \Database::getInstance()->prepare("SELECT files.uuid, files.name, product.filenameDl".(int)$no." FROM tl_files AS files, tl_ix_product_language AS product WHERE files.uuid = product.enclosureDl".(int)$no." AND product.addEnclosureDl".(int)$no." = 1 AND product.id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();

        return $res;
    }

    public function queryForDownloadImages($productId)
    {
        $query = \Database::getInstance()->prepare("SELECT files.uuid, files.name FROM tl_files AS files, tl_ix_product_language AS product WHERE files.uuid = product.singleSRC AND product.addImage = 1 AND product.id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();

        return $res;
    }

    public function queryForProductImages($productId)
    {
        $query = \Database::getInstance()->prepare("SELECT files.* FROM tl_files AS files, tl_ix_product_language AS product, tl_ix_product AS p WHERE files.uuid = p.singleSRC AND p.id = product.pid AND product.id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();

        return $res;
    }

    public function queryForProductImagesElement($productId)
    {
        $query = \Database::getInstance()->prepare("SELECT files.* FROM tl_files AS files, tl_ix_product AS p WHERE files.uuid = p.singleSRC AND p.id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();

        return $res;
    }

    // Product Header Image
    public function queryForProductHeaderImagesElement($productId)
    {
        $query = \Database::getInstance()->prepare("SELECT files.uuid FROM tl_files AS files, tl_ix_product AS p WHERE files.uuid = p.singleSRCHeader AND p.id = ? AND p.addHeaderImage = 1")->execute($productId);

        $res = $query->fetchAllAssoc();

        // no img found -> select default img
        if (empty($res)) {
            $query = \Database::getInstance()->prepare("SELECT uuid FROM tl_files WHERE name = 'banner_produkt-finder-default.jpg'")->execute();
            $res = $query->fetchAllAssoc();
        }

        return $res;
    }

    public function queryForContactPerson($productId)
    {
        //var_dump($productId); exit('product id');
        $query = \Database::getInstance()->prepare("SELECT c.* FROM tl_ix_contactperson AS c, tl_ix_product_language AS p WHERE p.contactPersonPicker = c.id AND p.id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();
        //var_dump($res); exit('contact person');

        return $res;
    }


    public function queryForProductHeading($productId)
    {
        //var_dump($productId); exit('product id');
        $query = \Database::getInstance()->prepare("SELECT product.title, product.teaserText FROM tl_ix_product_language AS product WHERE product.id = ?")->execute((int)$productId);

        $res = $query->fetchAllAssoc();
        //var_dump($res); exit('heading');

        return $res;
    }


    public function queryForRepContactPerson($rootId)
    {
        //var_dump($rootId); exit('product id');
        $query = \Database::getInstance()->prepare("SELECT c.* FROM tl_ix_contactperson AS c, tl_page AS p, tl_ix_rep AS r WHERE c.id = r.contactPersonPicker AND r.id = p.addRepPicker AND p.id = ?")->execute($rootId);

        $res = $query->fetchAllAssoc();
        //var_dump($res); exit('contact person');

        return $res;
    }

    public function queryForHtmlTable($productId)
    {
        $query = \Database::getInstance()->prepare("SELECT htmlTable FROM tl_ix_product_language WHERE id = ?")->execute($productId);

        $res = $query->fetchAllAssoc();
        // var_dump($res); exit('image');
        return $res;
    }

    public function selectAllowedLanguages(\DataContainer $dc)
    {
        if ($this->User->isAdmin) {
            // return all Langs
            $query = \Database::getInstance()->prepare("SELECT id, language FROM tl_ix_language")->execute();
            $result = $query->fetchAllAssoc();

            foreach ($result as $key => $value) {
                $arrData[$value['id']] = $value['language'];
            }

            return $arrData;

        } else {

            // fetch id and value from userLangs
            $query = \Database::getInstance()->prepare("SELECT lang.id, lang.language FROM tl_ix_language AS lang, tl_ix_rel_userLanguages AS userLang WHERE userLang.userId = ? AND userLang.languageId = lang.id")->execute($this->User->id);

            $result = $query->fetchAllAssoc();

            foreach ($result as $key => $value) {
                $arrData[$value['id']] = $value['language'];
            }

            return $arrData;
        }
    }

    public function getAllowedRepProducts($repId) {

        $res = \Database::getInstance()->prepare("SELECT productId FROM tl_ix_rel_repProducts WHERE repId = ?")->execute($repId)->fetchAllAssoc();

        foreach ($res as $key => $value) {
            $products[] = $value['productId'];
        }

        return $products;
    }

    public function generatePdfFromTable($tabelle, $dc)
    {
         // generate FilePath
        $lang = $dc->activeRecord->language;
        $productId = $dc->activeRecord->pid;
        $productLangId = $dc->activeRecord->id;

        // when creating a new language entry for product
        if (empty($lang)) {
            $languagePickerId = $dc->activeRecord->languagePicker;

            $result = \Database::getInstance()->prepare("SELECT language FROM tl_ix_language WHERE id = ?")->execute($languagePickerId);
            $res = $result->fetchAllAssoc();

            foreach ($res as $value) {
                $languageName = $value['language'];
            }

            $lang = $languageName;
        }

        $result = \Database::getInstance()->prepare("SELECT title FROM tl_ix_product WHERE id = ?")->execute($productId);
        $res = $result->fetchAllAssoc();

        foreach ($res as $value) {
            $productTitle = standardize($value['title']);
        }
        if (substr($productTitle, 0, 3) == 'id-') {
            $productTitle = substr($productTitle, 3);
        }

        $userFilesDir = TL_ROOT . '/files/userFiles/';
        $productsDir = TL_ROOT . '/files/userFiles/products/';
        $productPath = TL_ROOT . '/files/userFiles/products/' . $productTitle;
        $saveFileDir = '/files/userFiles/products/' . $productTitle . '/' . $lang;

        $syncFileDir = 'files/userFiles/products/' . $productTitle . '/' . $lang . '/' . $productTitle .'.pdf';

        $saveFilePathComplete = TL_ROOT . '/files/userFiles/products/' . $productTitle . '/' . $lang . '/' . $productTitle .'.pdf';
        $saveFilePath = '/files/userFiles/products/' . $productTitle . '/' . $lang . '/' . $productTitle .'.pdf';

        // echo $saveFileDir . '<br>';
        // echo $saveFilePath; //exit;

        // create new userFiles folder if not exist
        if (!is_dir($userFilesDir)) {
            mkdir($userFilesDir, 0777, true);
        }

        // create new product folder if not exist
        if (!is_dir($productsDir)) {
            mkdir($productsDir, 0777, true);
        }

        // create new product Title folder if not exist
        if (!is_dir($productPath)) {
            mkdir($productPath, 0777, true);
        }

        // create new language folder if not exist
        if (!is_dir(TL_ROOT .$saveFileDir)) {
            mkdir(TL_ROOT .$saveFileDir, 0777, true);
        }


        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Meta-Infos
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Schenck GmbH');
        $pdf->SetTitle($productTitle);
        $pdf->SetSubject($productTitle);
        $pdf->SetKeywords('Product', 'Table');

        // header / footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetMargins(20, 20, 10);

        // ab PDF_MARGIN_BOTTOM Abstand wird Seitenumbruch eingef�gt
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // font
        $pdf->SetFont('helvetica', '', 10);

        // page 1
        $pdf->AddPage();

        $tabelle = utf8_encode($tabelle);
        $pdf->writeHTMLCell(0, 0, '', '', $tabelle, 0, 1, 0, true, '', true);

        //$pdf->lastPage();

        // DEBUG:
        //$pdf->Output('pdf_test.pdf', 'I'); // an Browser senden


        //$pdf->Output('pdf_test.pdf', 'D'); // Download der PDF-Datei erzwingen
        $pdf->Output(TL_ROOT .$saveFilePath, 'F'); // Download der PDF-Datei

        // insert PDF-File-Path into tl_ix_product_language
        \Database::getInstance()->prepare("UPDATE tl_ix_product_language SET pdfTableFilePath = ? WHERE id = ?")->execute($saveFilePath, $productLangId);



        $pdfFile = \Dbafs::addResource($syncFileDir);
        $pdfUuid = $pdfFile->uuid;

        // save uuid into table
        \Database::getInstance()->prepare("UPDATE tl_ix_product_language SET uuid = ? WHERE id = ?")->execute($pdfUuid, $productLangId);

        // sync DB & File System
        //\Dbafs::syncFiles();

        //var_dump($pdfFile); exit;
    }

    /**
     * Get current language from catalog
     *
     * @return string
     */
    public function getCatalogLanguage()
    {
        global $objPage;
        $query = \Database::getInstance()->prepare("SELECT l.language FROM tl_page AS p, tl_ix_rep AS r, tl_ix_language AS l WHERE p.addRepPicker=r.id AND r.languagePicker=l.id AND p.id=?")
            ->execute($objPage->rootId);


        $data = $query->fetchAssoc();
        if (!empty($data['language'])) {
            $lang = $data['language'];
        }
        else {
            // Get fallback
            $query = \Database::getInstance()->prepare("SELECT l.language FROM tl_ix_language AS l WHERE l.defaultLanguage=1")
                ->execute();
            $data = $query->fetchAssoc();
            $lang = $data['language'];
        }
        if (empty($lang)) {
            $lang = '';
        }
        return $lang;
    }

    public function getFallBackLanguage() {
      global $objPage;
      $query = \Database::getInstance()->prepare("SELECT l.language FROM tl_ix_language AS l WHERE l.defaultLanguage=1")
          ->execute();
      $data = $query->fetchAssoc();
      $lang = $data['language'];

      if (empty($lang)) {
          $lang = '';
      }

      return $lang;
    }

    public static function getDataprotectionFields()
	{
		$dp = array();

		// Field Dataprotection Save
		$objWidgit = new \FormCheckBox();
		$objWidgit->name 		= 'dataprotection1';
		$objWidgit->mandatory	= true;
		$objWidgit->strError	= 'Du bist ein Fehler';
		$objWidgit->id = 'dp'.uniqid();
		$objWidgit->options 	= array(
			array('value' => '1', 'label' => $GLOBALS['TL_LANG']['MSC']['dsgvo']['txt01']),
		);
		$objWidgit->value		= \Input::post('dataprotection1');

		$dp[0]['widget'] = $objWidgit;
		$dp[0]['label'] = $GLOBALS['TL_LANG']['MSC']['dsgvo']['datenschutz'];

		// Field Dataprotection News
		$objWidgit = new \FormCheckBox();
		$objWidgit->name 		= 'dataprotection2';
		$objWidgit->mandatory	= false;
		$objWidgit->id = 'dp'.uniqid();
		$objWidgit->options 	= array(
			array('value' => '1', 'label' => $GLOBALS['TL_LANG']['MSC']['dsgvo']['txt02']),
		);
		$objWidgit->value		= \Input::post('dataprotection2');

		$dp[1]['widget'] = $objWidgit;
		$dp[1]['label'] = $GLOBALS['TL_LANG']['MSC']['dsgvo']['newsletter'];

		return $dp;
	}

	public static function convertDataprotectionArray($fields)
	{
		$arr = array();

		foreach ($fields as $k => $v) {
			$arr['dp'.$k] = $v['widget'];
			$arr['dp'.$k.'Label'] = $v['label'];
		}

		return $arr;
	}
}
