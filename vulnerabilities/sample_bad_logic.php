<?php
/**
 * sample_bad_logic.php — Intentionally Vulnerable PHP File
 *
 * PURPOSE: Security training / SAST-DAST testing target.
 * DO NOT deploy to any internet-facing or production system.
 *
 * Vulnerability inventory (by CWE):
 *   CWE-89   SQL Injection (multiple vectors)
 *   CWE-79   Reflected & Stored XSS
 *   CWE-78   OS Command Injection
 *   CWE-22   Path Traversal / LFI
 *   CWE-287  Broken Authentication
 *   CWE-639  IDOR / Broken Access Control
 *   CWE-918  Server-Side Request Forgery (SSRF)
 *   CWE-502  Insecure Deserialization
 *   CWE-798  Hardcoded Credentials
 *   CWE-328  Weak Cryptographic Hash (MD5 for passwords)
 *   CWE-601  Open Redirect
 *   CWE-434  Unrestricted File Upload
 *   CWE-200  Information Exposure (verbose errors)
 *   CWE-311  Missing Encryption of Sensitive Data
 *   CWE-862  Missing Authorization
 *   CWE-352  Cross-Site Request Forgery (no CSRF tokens)
 */

// ============================================================================
// CWE-798: Hardcoded Credentials
// ============================================================================
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'toor';           // Hardcoded DB password
$db_name = 'vuln_app';
$admin_api_key = 'sk-ADMIN-1234567890abcdef';  // Hardcoded API key

// ============================================================================
// CWE-200: Information Exposure — verbose error reporting in "production"
// ============================================================================
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ============================================================================
// Database connection — no TLS, no charset set, no prepared statements anywhere
// CWE-311: Missing Encryption of Sensitive Data (plaintext DB connection)
// ============================================================================
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    // Leaks internal DB error to the user
    die("Database connection failed: " . $conn->connect_error);
}

session_start();

// ============================================================================
// ROUTING — all actions via a single GET parameter, no CSRF protection anywhere
// CWE-352: No anti-CSRF tokens on any state-changing operation
// ============================================================================
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {

    // ========================================================================
    // LOGIN — CWE-89 (SQL Injection) + CWE-328 (MD5 password hashing)
    // ========================================================================
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = md5($_POST['password']);  // CWE-328: MD5 is not suitable for passwords

            // CWE-89: Direct string concatenation in SQL query
            $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['is_admin']  = $user['is_admin'];
                // CWE-311: Storing sensitive role flag in client-controllable session
                // without re-verification on each request
                echo "Welcome, " . $user['username'];
            } else {
                // CWE-200: Reveals whether the username or password was wrong
                echo "Invalid username or password for user: " . htmlspecialchars($username);
            }
        } else {
            // CWE-352: No CSRF token in form
            echo '<form method="POST" action="?action=login">
                    <input name="username" placeholder="Username">
                    <input name="password" type="password" placeholder="Password">
                    <button type="submit">Login</button>
                  </form>';
        }
        break;

    // ========================================================================
    // SEARCH — CWE-79 (Reflected XSS) + CWE-89 (SQL Injection)
    // ========================================================================
    case 'search':
        $query = isset($_GET['q']) ? $_GET['q'] : '';

        // CWE-79: User input reflected without encoding
        echo "<h2>Search results for: $query</h2>";

        // CWE-89: Unsanitised input in SQL
        $sql = "SELECT * FROM products WHERE name LIKE '%$query%'";
        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // CWE-79: Stored XSS — DB content rendered without encoding
                echo "<div>" . $row['name'] . " — $" . $row['price'] . "</div>";
            }
        }
        break;

    // ========================================================================
    // PROFILE — CWE-639 (IDOR) + CWE-862 (Missing Authorization)
    // ========================================================================
    case 'profile':
        // CWE-287: No session check — unauthenticated access possible
        // CWE-639: User-supplied ID used directly; no ownership verification
        $user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

        // CWE-89: SQL injection via id parameter
        $sql = "SELECT id, username, email, ssn, credit_card FROM users WHERE id = $user_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // CWE-200: Exposes SSN and credit card to any requester
            echo "<pre>" . print_r($user, true) . "</pre>";
        }
        break;

    // ========================================================================
    // PING — CWE-78 (OS Command Injection)
    // ========================================================================
    case 'ping':
        if (isset($_GET['host'])) {
            $host = $_GET['host'];
            // CWE-78: Unsanitised user input passed to shell
            $output = shell_exec("ping -c 3 " . $host);
            echo "<pre>$output</pre>";
        } else {
            echo '<form method="GET" action="?">
                    <input type="hidden" name="action" value="ping">
                    <input name="host" placeholder="Enter hostname">
                    <button type="submit">Ping</button>
                  </form>';
        }
        break;

    // ========================================================================
    // FILE VIEW — CWE-22 (Path Traversal / Local File Inclusion)
    // ========================================================================
    case 'view_file':
        if (isset($_GET['file'])) {
            $file = $_GET['file'];
            // CWE-22: No path canonicalization, no allowlist, no chroot
            // Allows ../../etc/passwd
            $path = "uploads/" . $file;
            if (file_exists($path)) {
                echo "<pre>" . htmlspecialchars(file_get_contents($path)) . "</pre>";
            } else {
                echo "File not found.";
            }
        }
        break;

    // ========================================================================
    // FILE UPLOAD — CWE-434 (Unrestricted File Upload)
    // ========================================================================
    case 'upload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $upload_dir  = 'uploads/';
            // CWE-434: Uses client-supplied filename, no extension check,
            // no MIME validation, no size limit, stored in web-accessible dir
            $target_file = $upload_dir . basename($_FILES['file']['name']);

            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                echo "File uploaded to: <a href='$target_file'>" . htmlspecialchars($target_file) . "</a>";
            } else {
                echo "Upload failed.";
            }
        } else {
            echo '<form method="POST" enctype="multipart/form-data" action="?action=upload">
                    <input type="file" name="file">
                    <button type="submit">Upload</button>
                  </form>';
        }
        break;

    // ========================================================================
    // COMMENT — CWE-79 (Stored XSS) + CWE-89 (SQL Injection)
    // ========================================================================
    case 'comment':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = $_POST['comment'];
            $author  = $_POST['author'];
            // CWE-89: Direct interpolation
            // CWE-79: Stored XSS when comments are rendered later
            $sql = "INSERT INTO comments (author, body) VALUES ('$author', '$comment')";
            $conn->query($sql);
            echo "Comment added!";
        }

        // Render all comments — CWE-79: no output encoding
        $result = $conn->query("SELECT * FROM comments ORDER BY id DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                echo "<div><b>" . $row['author'] . "</b>: " . $row['body'] . "</div>";
            }
        }

        echo '<form method="POST" action="?action=comment">
                <input name="author" placeholder="Name">
                <textarea name="comment" placeholder="Comment"></textarea>
                <button type="submit">Post</button>
              </form>';
        break;

    // ========================================================================
    // REDIRECT — CWE-601 (Open Redirect)
    // ========================================================================
    case 'redirect':
        if (isset($_GET['url'])) {
            // CWE-601: No validation of target URL; attacker controls destination
            header("Location: " . $_GET['url']);
            exit;
        }
        break;

    // ========================================================================
    // FETCH — CWE-918 (Server-Side Request Forgery)
    // ========================================================================
    case 'fetch':
        if (isset($_GET['url'])) {
            // CWE-918: Arbitrary URL fetched server-side; can reach internal
            // services (169.254.169.254, localhost, internal APIs)
            $content = file_get_contents($_GET['url']);
            echo "<pre>" . htmlspecialchars($content) . "</pre>";
        } else {
            echo '<form method="GET">
                    <input type="hidden" name="action" value="fetch">
                    <input name="url" placeholder="URL to fetch">
                    <button type="submit">Fetch</button>
                  </form>';
        }
        break;

    // ========================================================================
    // DESERIALIZE — CWE-502 (Insecure Deserialization)
    // ========================================================================
    case 'deserialize':
        if (isset($_GET['data'])) {
            // CWE-502: Unserializing user-controlled data — allows object
            // injection, RCE via POP chains if autoloaded classes exist
            $obj = unserialize(base64_decode($_GET['data']));
            echo "<pre>" . print_r($obj, true) . "</pre>";
        }
        break;

    // ========================================================================
    // ADMIN — CWE-862 (Missing Authorization) + CWE-287 (Broken Auth)
    // ========================================================================
    case 'admin':
        // CWE-862: Authorization check relies on session flag that is set
        // once at login and never re-verified against the DB
        // CWE-287: No session integrity check; session fixation possible
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            if (isset($_GET['delete_user'])) {
                // CWE-89: SQLi in admin action
                $conn->query("DELETE FROM users WHERE id = " . $_GET['delete_user']);
                echo "User deleted.";
            }
            // Dump all users — CWE-200
            $result = $conn->query("SELECT * FROM users");
            if ($result) {
                echo "<table border='1'><tr><th>ID</th><th>User</th><th>Email</th><th>Admin</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['email']}</td>"
                       . "<td>{$row['is_admin']}</td>"
                       . "<td><a href='?action=admin&delete_user={$row['id']}'>Delete</a></td></tr>";
                }
                echo "</table>";
            }
        } else {
            echo "Access denied.";
        }
        break;

    // ========================================================================
    // PASSWORD RESET — CWE-640 (Weak Password Recovery) + CWE-89
    // ========================================================================
    case 'reset_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email        = $_POST['email'];
            $new_password = md5($_POST['new_password']);  // CWE-328

            // CWE-640: No token, no email verification, no rate limiting
            // CWE-89: SQLi
            $sql = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
            $conn->query($sql);
            echo "Password updated for $email";  // CWE-79 + CWE-200
        } else {
            echo '<form method="POST" action="?action=reset_password">
                    <input name="email" placeholder="Email">
                    <input name="new_password" type="password" placeholder="New Password">
                    <button type="submit">Reset</button>
                  </form>';
        }
        break;

    // ========================================================================
    // EXPORT — CWE-22 (Path Traversal write) + CWE-862
    // ========================================================================
    case 'export':
        if (isset($_GET['filename'])) {
            // CWE-22: Attacker-controlled filename for write — can overwrite
            // arbitrary files (e.g., .htaccess, config.php)
            $filename = $_GET['filename'];
            $data     = "Exported at " . date('Y-m-d H:i:s');
            file_put_contents("exports/" . $filename, $data);
            echo "Exported to exports/$filename";  // CWE-79
        }
        break;

    // ========================================================================
    // HOME
    // ========================================================================
    case 'home':
    default:
        echo "<h1>Vulnerable Sample App</h1>";
        echo "<p>Training target — do not expose to untrusted networks.</p>";
        echo "<ul>
                <li><a href='?action=login'>Login</a> — SQLi, weak hash</li>
                <li><a href='?action=search&q=test'>Search</a> — SQLi, reflected XSS</li>
                <li><a href='?action=profile&id=1'>Profile</a> — IDOR, info leak</li>
                <li><a href='?action=ping'>Ping</a> — command injection</li>
                <li><a href='?action=view_file&file=readme.txt'>View File</a> — path traversal</li>
                <li><a href='?action=upload'>Upload</a> — unrestricted upload</li>
                <li><a href='?action=comment'>Comments</a> — stored XSS, SQLi</li>
                <li><a href='?action=redirect&url=https://example.com'>Redirect</a> — open redirect</li>
                <li><a href='?action=fetch'>Fetch URL</a> — SSRF</li>
                <li><a href='?action=deserialize'>Deserialize</a> — insecure deserialization</li>
                <li><a href='?action=admin'>Admin Panel</a> — broken access control</li>
                <li><a href='?action=reset_password'>Reset Password</a> — weak recovery</li>
                <li><a href='?action=export&filename=report.txt'>Export</a> — path traversal write</li>
              </ul>";
        break;
}

$conn->close();
?>
