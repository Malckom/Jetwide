// No-Cache Development Server
const express = require('express');
const path = require('path');

const app = express();
const PORT = 3000;

// Disable all caching
app.use((req, res, next) => {
  res.set({
    'Cache-Control': 'no-cache, no-store, must-revalidate, max-age=0',
    'Pragma': 'no-cache',
    'Expires': '0',
    'Last-Modified': new Date().toUTCString(),
    'ETag': false
  });
  next();
});

// Serve static files with no cache
app.use(express.static('.', {
  etag: false,
  lastModified: false,
  setHeaders: (res, path) => {
    res.set({
      'Cache-Control': 'no-cache, no-store, must-revalidate, max-age=0',
      'Pragma': 'no-cache',
      'Expires': '0'
    });
  }
}));

// Root redirect
app.get('/', (req, res) => {
  res.redirect('/index.html');
});

app.listen(PORT, () => {
  console.log(`🚀 No-Cache Server running at http://localhost:${PORT}`);
  console.log('📝 All files served with no-cache headers');
  console.log('🔄 Your changes will always be visible immediately');
});