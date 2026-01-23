<?php

/**
 * DVWA - Product Search (High Difficulty SQL Injection)
 *
 * This search feature has some protections but they can be bypassed:
 * - Blacklist filtering (blocks common keywords but misses alternatives)
 * - Input length limit (but still allows injection)
 * - Basic quote escaping (incomplete)
 *
 * Bypass techniques:
 * - Use UNION with case variations: uNiOn, UnIoN
 * - Use inline comments: UN/**/ION
 * - Use alternative keywords not in blacklist
 * - Use numeric injection (no quotes needed)
 */

if( isset( $_GET[ 'search' ] ) ) {
    // Get input
    $search = $_GET[ 'search' ];

    // "Security" - Blacklist common SQL injection keywords
    $blacklist = array( 'or', 'and', 'union', 'select', 'drop', 'insert', 'delete' );
    $search_lower = strtolower( $search );

    foreach( $blacklist as $blocked ) {
        if( strpos( $search_lower, $blocked ) !== false ) {
            $html .= "<pre>Blocked: Suspicious input detected.</pre>";
            return;
        }
    }

    // "Security" - Limit input length (but 100 chars is still plenty for injection)
    if( strlen( $search ) > 100 ) {
        $html .= "<pre>Error: Search term too long.</pre>";
        return;
    }

    // "Security" - Escape single quotes (but double quotes and backslash tricks work)
    $search = str_replace( "'", "''", $search );

    switch ($_DVWA['SQLI_DB']) {
        case MYSQL:
            // Vulnerable query - search term directly concatenated
            $query = "SELECT product_id, product_name, price FROM products WHERE product_name LIKE '%{$search}%' OR product_id = {$search}";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

            if( $result && mysqli_num_rows( $result ) > 0 ) {
                while( $row = mysqli_fetch_assoc( $result ) ) {
                    $html .= "<pre>Product ID: {$row['product_id']}<br />Name: {$row['product_name']}<br />Price: \${$row['price']}</pre>";
                }
            } else {
                // Error message leaks info - also vulnerable
                $html .= "<pre>No products found for: {$_GET['search']}<br/>Debug: " . mysqli_error($GLOBALS["___mysqli_ston"]) . "</pre>";
            }
            break;

        case SQLITE:
            global $sqlite_db_connection;

            $query = "SELECT product_id, product_name, price FROM products WHERE product_name LIKE '%{$search}%' OR product_id = {$search}";

            try {
                $results = $sqlite_db_connection->query($query);
            } catch (Exception $e) {
                // Error leaks database info
                $html .= "<pre>Error: " . $e->getMessage() . "</pre>";
                return;
            }

            if ($results) {
                $found = false;
                while ($row = $results->fetchArray()) {
                    $found = true;
                    $html .= "<pre>Product ID: {$row['product_id']}<br />Name: {$row['product_name']}<br />Price: \${$row['price']}</pre>";
                }
                if (!$found) {
                    $html .= "<pre>No products found for: {$_GET['search']}</pre>";
                }
            }
            break;
    }
}

?>
