import React, { useState, useEffect } from 'react';
import './App.css';

function App() {
  const [taches, setTaches] = useState([]);
  const [newTache, setNewTache] = useState('');
  const API_URL = 'http://localhost/Backend/api/taches.php';

  useEffect(() => {
    fetchTaches();
  }, []);

  const fetchTaches = async () => {
    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      console.log('Données rafraîchies:', data);
      setTaches(data);
    } catch (error) {
      console.error('Erreur de rafraîchissement:', error);
    }
  };
  

  const addTache = async (e) => {
    e.preventDefault();
    await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ nom: newTache }),
    });
    setNewTache('');
    fetchTaches();
  };

  const toggleTache = async (id, terminee) => {
    const currentStatus = parseInt(terminee);
    const newStatus = currentStatus === 1 ? 0 : 1;
    try {
      const response = await fetch(API_URL, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id, terminee: newStatus }),
      });
      const data = await response.json();
      fetchTaches();
    } catch (error) {
      console.error('Erreur:', error);
    }
  };
  
  
  

  const deleteTache = async (id) => {
    await fetch(API_URL, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id }),
    });
    fetchTaches();
  };

  return (
    <div className="App">
      <h1>Liste des taches</h1>
      <form onSubmit={addTache}>
        <input
          type="text"
          value={newTache}
          onChange={(e) => setNewTache(e.target.value)}
          placeholder="Ajouter une nouvelle tache"
        />
        <button type="submit">Ajouter</button>
      </form>
      <ul>
        {taches.map((tache) => (
          <li key={tache.id}>
            <input
              type="checkbox"
              checked={parseInt(tache.terminee) === 1}
              onChange={() => toggleTache(tache.id, tache.terminee)}
            />

            <span style={{ textDecoration: tache.terminee === "1" ? 'line-through' : 'none' }}>
              {tache.nom}
            </span>
            <button onClick={() => deleteTache(tache.id)}>Supprimer</button>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;
