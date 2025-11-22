<?php

// Secure Frontend for DVWA API Lab

$html .= "
<script>
    const USER_ID = 2; // current authenticated user (example)

    // -------------------------------
    // SECURE: Update UI safely
    // -------------------------------
    function update_username(user_json) {
        console.log(user_json);

        const user_info = document.getElementById('user_info');
        const name_input = document.getElementById('name');

        if (!user_json || !user_json.name) {
            user_info.innerHTML = 'User details: unknown';
            name_input.value = '';
            return;
        }

        const level = (user_json.level === 0) ? 'admin' : 'user';

        user_info.innerHTML = 'User details: ' + user_json.name + ' (' + level + ')';
        name_input.value = user_json.name;

        // Only show success if server returned admin
        if (user_json.level === 0) {
            const successDiv = document.getElementById('message');
            successDiv.style.display = 'block';
        }
    }

    // -------------------------------
    // SECURE GET request
    // No hardcoded vulnerable endpoint
    // API Key required
    // -------------------------------
    function get_user() {
        const url = `/vulnerabilities/api/v2/user/${USER_ID}?key=SECURE_API_KEY_2025`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('GET failed');
                }
                return response.json();
            })
            .then(data => update_username(data))
            .catch(err => console.error(err));
    }

    // -------------------------------
    // SECURE PUT request
    // Prevent privilege escalation:
    // - backend enforces user identity
    // - user cannot modify level through UI
    // -------------------------------
    function update_name() {
        const name = document.getElementById('name').value;
        const data = JSON.stringify({ name: name });

        const url = `/vulnerabilities/api/v2/user/${USER_ID}?key=SECURE_API_KEY_2025`;

        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: data
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('PUT failed');
            }
            return response.json();
        })
        .then(data => update_username(data))
        .catch(err => console.error(err));
    }
</script>
";

$html .= "
    <p>This version securely fetches and updates user data without allowing privilege escalation.</p>
    <p id='user_info'></p>

    <form>
        <p>
            <label for='name'>Name</label>
            <input type='text' id='name'>
        </p>
        <p>
            <input type='button' value='Submit' onclick='update_name();'>
        </p>
    </form>

    <div class='success' style='display:none' id='message'>You are admin (verified by backend).</div>

    <script>
        get_user();
    </script>
";

?>

