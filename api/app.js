const express = require('express');
const { Pool } = require('pg');

const app = express();
app.use(express.json());

// Configuration de la connexion à la base de données
const pool = new Pool({
  user: 'admin',
  host: 'db',
  database: 'mydatabase',
  password: 'securepassword',
  port: 5432,
});

// Ajoutez cette route pour gérer les requêtes GET à la racine
app.get('/', (req, res) => {
    res.send('Bienvenue sur mon API!');
  });

// Route pour récupérer les logs
app.get('/logs', async (req, res) => {
  try {
    const { rows } = await pool.query('SELECT * FROM project.logs');
    res.json(rows);
  } catch (err) {
    res.status(500).send('Erreur lors de la récupération des logs');
  }
});

// Route pour ajouter un log
app.post('/logs', async (req, res) => {
  const { user, date } = req.body;
  try {
    await pool.query('INSERT INTO project.logs (user, availability) VALUES ($1, $2)', [user, date]);
    res.status(201).send('Log ajouté');
  } catch (err) {
    res.status(500).send('Erreur lors de l\'ajout du log');
  }
});

const PORT = 8080;
app.listen(PORT, () => {
  console.log(`Serveur démarré sur le port ${PORT}`);
});
