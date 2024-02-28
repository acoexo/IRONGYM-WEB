const express = require('express');

const { exec } = require('child_process');

const app = express();

const port = process.env.PORT || 3000;

// Configurar el servidor web para cargar la aplicación PHP

app.use(express.static('public'));

app.listen(port, () => {

  console.log(`Servidor web Node.js en ejecución en http://localhost:${port}`);

});

// Ejecutar la aplicación PHP con el servidor incorporado de PHP

exec('php -S localhost:8080 -t public/');