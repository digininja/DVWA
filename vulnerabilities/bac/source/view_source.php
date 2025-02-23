<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated'));

$page = dvwaPageNewGrab();
$page[ 'title' ] = 'Source: Broken Access Control';

switch (dvwaSecurityLevelGet()) {
    case 'low':
        $page[ 'title' ] .= ' (Low)';
        $file = 'low.php';
        break;
    case 'medium':
        $page[ 'title' ] .= ' (Medium)';
        $file = 'medium.php';
        break;
    case 'high':
        $page[ 'title' ] .= ' (High)';
        $file = 'high.php';
        break;
    default:
        $page[ 'title' ] .= ' (Impossible)';
        $file = 'impossible.php';
        break;
}

$page[ 'title' ] .= $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'source';

$source = file_get_contents(DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/bac/source/{$file}");

if ($source !== false) {
    $source = str_replace(array('<?php', '?>'), array('&lt;?php', '?&gt;'), $source);
    $page[ 'body' ] .= "
        <div class=\"body_padded\">
            <h1>Source: {$file}</h1>
            <div class=\"vulnerable_code_area\">
                <div class=\"code_title\">{$file}</div>" . 
                highlight_string($source, true) . "
            </div>
            <br />
            <br />
            <br />
        </div>";
}

dvwaSourceHtmlEcho($page);
?>
