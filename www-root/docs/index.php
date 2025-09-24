<?php

session_start();
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Documentation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../src/styles/styles.css">
    <link rel="stylesheet" href="documentation.css">
    </style>
</head>
<body>

    <?php 
        $siteData = json_decode(file_get_contents(__DIR__ . '/../public/data/Site.json'));
        include __DIR__ . '/../navbar/index.php'; 
    ?>

    <div class="doc-container">
        <h1>Project Documentation</h1>

        <section id="overview">
            <h2>Overview</h2>
            <p>This project is a full-featured web application built with PHP and MySQL, containerized with Docker. It provides a complete user authentication system with role-based access control, an admin dashboard for managing users and content, and a public-facing API for retrieving data about seagulls. The system is designed to be modular and secure, handling everything from user registration and admin approval to API key generation and management.</p>
        </section>

        <section id="features">
            <h2>Key Features</h2>
            <ul>
                <li><strong>User Authentication:</strong> Secure signup, login, and logout functionality with hashed passwords.</li>
                <li><strong>Role-Based Access:</strong> Two user roles: 'admin' and 'user', with different permissions.</li>
                <li><strong>Admin Dashboard:</strong> A central hub for administrators to:
                    <ul>
                        <li>View and manage all registered users.</li>
                        <li>Approve or deny new admin account registrations.</li>
                        <li>View messages from the public contact form.</li>
                        <li>Generate and manage API keys.</li>
                    </ul>
                </li>
                <li><strong>API Key System:</strong>
                    <ul>
                        <li>Admins can generate API keys, either assigned to a user or unassigned.</li>
                        <li>Users without a key can manually enter and claim an unassigned key.</li>
                    </ul>
                </li>
                <li><strong>Public & Private APIs:</strong>
                    <ul>
                        <li>A public API for seagull data, secured by API keys.</li>
                        <li>Private APIs for user and contact data, secured by login sessions.</li>
                    </ul>
                </li>
                <li><strong>Dockerized Environment:</strong> The entire application (Apache/PHP server and MySQL database) is containerized with Docker Compose for easy setup and deployment.</li>
            </ul>
        </section>
        
        <section id="setup">
            <h2>Setup & Installation</h2>
            <p>The project is designed to run in a Docker environment. Ensure you have Docker and Docker Compose installed.</p>
            <ol>
                <li><strong>Configure the Application:</strong> Open the file <code>contactconfig/config.php</code>. Review the database credentials (they should work with Docker out-of-the-box). It is <strong>critical</strong> to change the default password for the <code>SUPER_ADMIN_PASSWORD</code>.</li>
                <li><strong>Build and Start Containers:</strong> In your terminal, navigate to the project's root directory and run:
                    <pre><code class="language-bash">docker-compose up --build -d</code></pre>
                </li>
                <li><strong>Access the Application:</strong> The website is now running at <a href="http://localhost:5150">http://localhost:5150</a>.</li>
            </ol>
            <div class="note">
                <strong>First-Time Admin Setup:</strong> Your first database admin must be created via the hardcoded "super admin".
                <ol>
                    <li>Log in using the <code>SUPER_ADMIN_USERNAME</code> and <code>SUPER_ADMIN_PASSWORD</code> from your /contactconfig/index.php file.</li>
                    <li>Go to the signup page and register a new account with the role "Admin".</li>
                    <li>On the admin dashboard (while logged in as super admin), you will see the new admin in the "Pending Admin Approvals" section. Click "Approve".</li>
                    <li>Log out, then log back in with your newly approved admin account.</li>
                    <li>For security, you should now comment out or remove the <code>SUPER_ADMIN</code> credentials from <code>config.php</code>.</li>
                </ol>
            </div>
        </section>

        <section id="api-docs">
            <h2>API Documentation</h2>
            
            <h3>1. Seagulls API</h3>
            <p>Provides a list of all seagull species in the database. Requires a valid API key for access.</p>
            <ul>
                <li><strong>URL:</strong> <code>/api/seagulls.php</code></li>
                <li><strong>Method:</strong> <code>GET</code></li>
                <li><strong>Authentication:</strong> API Key</li>
            </ul>
            <strong>Request:</strong>
            <p>The API key must be sent as an HTTP header or a URL parameter.</p>
            <em>Header (Recommended):</em>
            <pre><code class="language-bash">curl -X GET "http://localhost:5150/api/seagulls.php" \
-H "X-API-Key: YOUR_API_KEY_HERE"</code></pre>
            <em>URL Parameter (Alternative):</em>
            <pre><code>http://localhost:5150/api/seagulls.php?api_key=YOUR_API_KEY_HERE</code></pre>
            <strong>Success Response (200 OK):</strong>
            <pre><code class="language-json">[
    {
        "species_name": "Herring Gull",
        "description": "A large, noisy gull...",
        "habitat": "Coasts, lakes, and urban areas",
        "image_url": "public/images/seagulls/herring_gull.jpg"
    },
    ...
]</code></pre>
            <strong>Error Responses:</strong>
            <ul>
                <li><code>401 Unauthorized</code>: If the API key is missing.</li>
                <li><code>403 Forbidden</code>: If the API key is invalid or inactive.</li>
            </ul>

            <h3>2. Users API</h3>
            <p>Provides a list of all users in the database. This is a private API and requires the requester to be logged in as an admin.</p>
            <ul>
                <li><strong>URL:</strong> <code>/api/users.php</code></li>
                <li><strong>Method:</strong> <code>GET</code></li>
                <li><strong>Authentication:</strong> Admin Login Session</li>
            </ul>
            <strong>Request:</strong>
            <p>Access this URL from a browser where you are already logged in as an administrator. The session cookie handles authentication.</p>
            <strong>Success Response (200 OK):</strong>
            <pre><code class="language-json">[
    {
        "id": "1",
        "username": "Victor",
        "role": "admin",
        "status": "approved",
        "created_at": "2025-09-24 10:30:00"
    },
    ...
]</code></pre>
            <strong>Error Response:</strong>
            <ul>
                <li><code>403 Forbidden</code>: If the user is not logged in or is not an admin.</li>
            </ul>

            <h3>3. Contacts API</h3>
            <p>Provides a list of all submitted contact form messages. This is a private API and requires the requester to be logged in (any role).</p>
            <ul>
                <li><strong>URL:</strong> <code>/api/contacts.php</code></li>
                <li><strong>Method:</strong> <code>GET</code></li>
                <li><strong>Authentication:</strong> User Login Session</li>
            </ul>
            <strong>Request:</strong>
            <p>Access this URL from a browser where you are logged in as any user.</p>
            <strong>Success Response (200 OK):</strong>
            <pre><code class="language-json">[
    {
        "id": "1",
        "name": "Test User",
        "email": "Test@example.com",
        "message": "This is a test message.",
        "submission_date": "2025-09-24 11:00:00"
    },
    ...
]</code></pre>
            <strong>Error Response:</strong>
            <ul>
                <li><code>403 Forbidden</code>: If the user is not logged in.</li>
            </ul>
        </section>

    </div>

</body>
</html>