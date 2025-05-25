<template>
  <div>
    <h1>Liste des courriers</h1>
    <div v-if="courriers.length === 0">
      <p>Aucun courrier disponible.</p>
    </div>
    <ul>
      <li v-for="courrier in courriers" :key="courrier.id">
        <strong>{{ courrier.titre }}</strong> - {{ courrier.date }}
      </li>
    </ul>
    <button @click="ajouterCourrier">Ajouter un courrier</button>
  </div>
</template>

<script>
export default {
  name: 'Courriers',
  data() {
    return {
      // Liste des courriers (exemple statique)
      courriers: [
        { id: 1, titre: 'Courrier 1', date: '2025-03-01' },
        { id: 2, titre: 'Courrier 2', date: '2025-03-02' },
        { id: 3, titre: 'Courrier 3', date: '2025-03-03' },
      ],
    };
  },
  methods: {
    // Méthode pour ajouter un courrier (exemple simple)
    ajouterCourrier() {
      const newCourrier = {
        id: this.courriers.length + 1,
        titre: `Courrier ${this.courriers.length + 1}`,
        date: new Date().toISOString().split('T')[0], // Date du jour
      };
      this.courriers.push(newCourrier);
    },
  },
};
</script>

<style scoped>
h1 {
  color: #4CAF50;
  font-size: 24px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  background-color: #f4f4f4;
  margin: 8px 0;
  padding: 10px;
  border-radius: 4px;
}

button {
  background-color: #008CBA;
  color: white;
  border: none;
  padding: 10px 15px;
  font-size: 16px;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #005f6a;
}
</style>
