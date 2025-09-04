// A simple server using Node.js and the Express framework.
// This solves the CORS issue by serving files over HTTP.

// 1. Import necessary packages
const express = require('express');
const path = require('path');

// 2. Initialize the Express app and define a port
const app = express();
const PORT = 3000; // You can use any available port

// 3. Middleware to serve static files
// Serve the 'public' folder for assets like data, images, etc.
app.use('/public', express.static(path.join(__dirname, 'public')));

// Serve the 'src' folder for core files like HTML, CSS, and client-side JS.
app.use(express.static(path.join(__dirname, 'src')));

// 4. A simple route for the homepage
// This ensures that visiting the root URL serves your index.html
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'src', 'index.html'));
});

// 5. Start the server
app.listen(PORT, () => {
    console.log(`✅ Server is running smoothly at http://localhost:${PORT}`);
    console.log('Press Ctrl+C to stop the server.');
});