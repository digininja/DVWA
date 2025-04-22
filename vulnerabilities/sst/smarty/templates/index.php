<?php
# Directory listing code taken from 
# https://www.the-art-of-web.com/php/directory-list/

function getFileList($dir) {
	// array to hold return value
	$retval = [];

	// add trailing slash if missing
	if(substr($dir, -1) != "/") {
	  $dir .= "/";
	}

	// open pointer to directory and read list of files
	$d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");

	while(false !== ($entry = $d->read())) {
	  // skip parent and this directory
	  if($entry == ".." || $entry == ".") {
		continue;
	  }

	  if (is_dir("{$dir}{$entry}")) {
		$retval[] = [
		  'name' => "{$entry}/",
		  'type' => filetype("{$dir}{$entry}"),
		  'size' => 0,
		  'lastmod' => filemtime("{$dir}{$entry}")
		];
	  } elseif (is_readable("{$dir}{$entry}")) {
		$retval[] = [
		  'name' => "{$entry}",
		  'type' => mime_content_type("{$dir}{$entry}"),
		  'size' => filesize("{$dir}{$entry}"),
		  'lastmod' => filemtime("{$dir}{$entry}")
		];
	  }
	} // for each file

	$d->close();

	return $retval;
}

$dirlist = getFileList(".");

echo "<table border=\"1\">\n";
echo "<thead>\n";
echo "<tr><th>Name</th><th>Type</th><th>Size</th><th>Last Modified</th></tr>\n";
echo "</thead>\n";
echo "<tbody>\n";

foreach($dirlist as $file) {
	echo "<tr>\n";
	echo "<td>{$file['name']}</td>\n";
	echo "<td>{$file['type']}</td>\n";
	echo "<td>{$file['size']}</td>\n";
	echo "<td>",date('r', $file['lastmod']),"</td>\n";
	echo "</tr>\n";
}

echo "</tbody>\n";
echo "</table>\n\n";
?>
