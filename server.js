const express = require('express');
const path = require('path');


const app = express();
const PORT = 3000; 

app.use('/public', express.static(path.join(__dirname, 'public')));

app.use(express.static(path.join(__dirname, 'src')));

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'src', 'index.html'));
});

app.listen(PORT, () => {
    console.log(`✅ Server is running smoothly at http://localhost:${PORT}`);
    console.log('Press Ctrl+C to stop the server.');
});