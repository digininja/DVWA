<?php
/*--------------------------------------------------------------------------+
This file is part of eStudy.
- Modulgruppe:  PHP-IDS
- Beschreibung: Klasse für das automatische aktualisieren der PHP-IDS Regeln
- Version:      0.2, 20-06-2011
- Autor(en):    Matthias Hecker <matthias.hecker@mni.fh-giessen.de>
                modified: Sami Mußbach <mussbach@uni.lueneburg.de>
+---------------------------------------------------------------------------+
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.
+---------------------------------------------------------------------------+
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
+--------------------------------------------------------------------------*/
 
class l_phpidsUpdate {
        const FILENAME_RULES = "default_filter.xml";
        const FILENAME_CONVERTER = "Converter.php";
       
        /**
         * Url returns SHA-1 Hash of the current version
         * @var string
         */
        const HASH_BASE_URL = "https://phpids.org/hash.php?f=";
 
        /**
         * The base url of the rules and converter file
         * @var string
         */
        const DOWNLOAD_BASE_URL = "https://dev.itratos.de/projects/php-ids/repository/raw/trunk/lib/IDS/";
 
        /**
         * Base URL for retreiving last modification information. %s -> filename
         * @var string
         */
        const FEED_BASE_URL = "http://dev.itratos.de/projects/php-ids/repository/revisions/1/revisions/trunk/%s?format=atom";
 
        /**
         * Path to phpids library
         * @var string
         */
        private $phpids_base;
 
        /**
         * Cache for remote file hashes
         * @var string
         */
        private $hash_cache;
       
        public function __construct () {
                $this->phpids_base = "YOUR_PATH_TO/phpids/lib/IDS/";
                $this->hash_cache = array();
        }
 
        private function fetchUrl($url) {
                $curl = curl_init();
               
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
                // phpids is using cacert
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
               
                $data = curl_exec($curl);
 
                if ($data === false) {
            try{
                throw new Exception("PHPIDS-Update: Some error occurred: ".curl_error($curl));
            } catch (Exception $e){
                return false;
            }
                }
               
                curl_close($curl);
               
                return $data;
        }
       
        /**
         * Perform a Rule and Converter update if necessary
         *
         * @return boolean returns false if an error occured
         */
        public function update() {
                $ok = true;
               
                // perform updates...
                $ok = $this->updateFile(self::FILENAME_RULES);
               
                if ($ok)
                        $ok = $this->updateFile(self::FILENAME_CONVERTER);
 
                return $ok;
        }
       
        /**
         * Download current file and replaces local
         *
         * @param string FILENAME_RULES or FILENAME_CONVERTER
         */
        private function updateFile($filename) {
                // fetch remote file:
                $file_contents = $this->fetchUrl(self::DOWNLOAD_BASE_URL.$filename);
 
        if ($file_contents === false) return false;
 
        if (sha1($file_contents) != $this->getCurrentFileHash($filename)) {
            try{
                throw new Exception("PHPIDS-Update: SHA1-hash of the downloaded file ($filename) is incorrect! (Download failed or Man-in-the-Middle). SHA1 of the file in the trunk: ".sha1($file_contents).", SHA1, provided by phpids.org: ".$this->getCurrentFileHash($filename));
            } catch (Exception $e){
                return false;
            }
                }
 
                if (strlen($file_contents) <= 100) {
                        return false;
                }
 
                // overwrite file contents
                if (!file_put_contents($this->phpids_base.$filename, $file_contents)) {
                        return false;
                }
 
                return true;
        }
 
        /**
         * Retreive current SHA-1 hash from php-ids.org
         *
         * @param string FILENAME_RULES or FILENAME_CONVERTER
         * @return mixed SHA-1 hash or false if unavailible
         */
        private function getCurrentFileHash($filename) {
                if (!empty($hash_cache[$filename])) {
                        return $hash_cache[$filename];
                }
 
                $url = self::HASH_BASE_URL.$filename;
 
        $hash_response = $this->fetchUrl($url);
                if ($hash_response === false) return false;
                $hash = trim($hash_response);
 
                if (preg_match("/^[0-9a-f]{40}$/", $hash)) {
                        $hash_cache[$filename] = $hash;
                        return $hash;
                } else {
                        return false;
                }
        }
 
        /**
         * Generate SHA-1 hash for local files
         *
         * @param string FILENAME_RULES or FILENAME_CONVERTER
         * @return mixed SHA-1 hash of local file or false if file does not exists
         */
        private function getLocalFileHash($filename) {
                $path = $this->phpids_base . $filename;
                if (file_exists($path)) {
                        return sha1_file($path);
                } else {
                        return false;
                }
        }
 
        /**
         * Compare local and remote version of ids rules.
         *
         * @return boolean returns true if rules are uptodate.
         */
        public function isRulesUpdated() {
                if ($this->getCurrentFileHash(self::FILENAME_RULES) ==
                        $this->getLocalFileHash(self::FILENAME_RULES)) {
                        return true;
                } else {
                        return false;
                }
        }
 
        /**
         * Compare local and remote version of ids converter.
         *
         * @return boolean returns true if rules are uptodate.
         */
        public function isConverterUpdated() {
                if ($this->getCurrentFileHash(self::FILENAME_CONVERTER) ==
                        $this->getLocalFileHash(self::FILENAME_CONVERTER)) {
                        return true;
                } else {
                        return false;
                }
        }
 
        /**
         * Check for existing rules and converter and for write permissions
         *
         * @return boolean returns true if both files are writable
         */
        public function isWritable() {
                if (file_exists($this->phpids_base.self::FILENAME_RULES) &&
                        is_writable($this->phpids_base.self::FILENAME_RULES) &&
                        file_exists($this->phpids_base.self::FILENAME_CONVERTER) &&
                        is_writable($this->phpids_base.self::FILENAME_CONVERTER)) {
                        return true;
                } else {
            return false;
                }
               
        }
 
        /**
         * Returns a date string with last time the rules file was modified
         * @return string
         */
        private function getLastRulesUpdate() {
                return date("d.m.Y H:i", filectime($this->phpids_base.self::FILENAME_RULES));
        }
 
        /**
         * Returns a date string with last time the rules file was modified
         * @return string
         */
        private function getLastConverterUpdate() {
                return date("d.m.Y H:i", filectime($this->phpids_base.self::FILENAME_CONVERTER));
        }
 
        /**
         * Show version status table
         */
        public function showVersionStatus() {
                $update_needed = false;
 
        $output = "<table class='tableBorder'>";
                $output .= "<tr><td class='tableHead' colspan='2'>IDS Version</td></tr>";
 
                $output .= "<tr><td class='tableCell' valign='top'>Filter:</td>\n<td class='tableCell'>";
                if ($this->isRulesUpdated()) {
                        $output .=  "<span style='color: green;'>aktuell.</span>";
                } else {
                        $output .=  "<span style='color: red;'>nicht aktuell.</span>";
                        $update_needed = true;
                }
                $output .= "<br />Letzte lokale &Auml;nderung: <strong>".$this->getLastRulesUpdate()."</strong><br />";
                $output .= "Letzte &Auml;nderung auf php-ids.org: <strong>".$this->getLastFileUpdate(self::FILENAME_RULES)."</strong><br />";
                $output .= "SHA-1 Hash: <br /> <code>".$this->getLocalFileHash(self::FILENAME_RULES)."</code>";
                if (!$this->isRulesUpdated()) {
                        $output .= "(local)<br /> <code>".$this->getCurrentFileHash(self::FILENAME_RULES)."</code>(remote)";
                }
                $output .= "</td></tr>";
               
                $output .= "<tr><td class='tableCell' valign='top'>Converter:</td>\n<td class='tableCell'>";
                if ($this->isConverterUpdated()) {
                        $output .=  "<span style='color: green;'>aktuell.</span>";
                } else {
                        $output .=  "<span style='color: red;'>nicht aktuell.</span>";
                        $update_needed = true;
                }
                $output .= "<br />Letzte lokale &Auml;nderung: <strong>".$this->getLastConverterUpdate()."</strong><br />";
                $output .= "Letzte &Auml;nderung auf php-ids.org: <strong>".$this->getLastFileUpdate(self::FILENAME_CONVERTER)."</strong><br />";
                $output .= "SHA-1 Hash: <br /> <code>".$this->getLocalFileHash(self::FILENAME_CONVERTER)."</code>";
                if (!$this->isConverterUpdated()) {
                        $output .= "(local)<br /> <code>".$this->getCurrentFileHash(self::FILENAME_CONVERTER)."</code>(remote)";
                }
                $output .= "</td></tr>";
 
                // is update possible?
                if (!$this->isRulesUpdated() || !$this->isConverterUpdated()) {
                        $output .= "<tr><td class='tableCell'> </td>\n<td class='tableCell'>";
                        if ($this->isWritable() && function_exists("curl_init")) {
                                $output .= "<form method='POST'>";
                                $output .= "<input type='submit' name='update_phpids' value='Automatisch Aktualisieren' />";
                                $output .= "</form>";
                        } else {
                                $output .= "Kein automatisches Update verf&uuml;gbar. (Dateien beschreibbar/ Curl-Extension verf&uuml;gbar?)";
                        }
                        $output .= "</td></tr>";
                }
 
                $output .= "</table>";
 
                return $output;
        }
       
        /**
         * Returns last
         *
         * @param string filename
         * @return mixed date of last change or if an error occured, false
         */
        private function getLastFileUpdate($filename) {
                $feed_url = sprintf(self::FEED_BASE_URL, $filename);
 
                $content = $this->fetchUrl($feed_url);
                if (preg_match("/<pubDate>([^<]+)<\/pubDate>/", $content, $match)) {
                        return date("d.m.Y H:i", strtotime($match[1]));
                } else {
                        return false;
                }
        }
}
