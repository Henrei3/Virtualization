# Utilisez une image de base Node.js
FROM node:14

# Créez un répertoire pour l'application
WORKDIR /usr/src/app

# Copiez les fichiers de package.json et package-lock.json
COPY package*.json ./

# Installez les dépendances
RUN npm install

# Copiez les fichiers et dossiers du projet dans le répertoire de travail
COPY . .

# Exposez le port sur lequel l'API s'exécute
EXPOSE 8080

# Commande pour démarrer l'application
CMD ["node", "app.js"]
