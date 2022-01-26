# bateam_solution
Test recrutement 

Besoin

Créer une interface de recherche et de filtre permettant de lister les informations du fichier joint avec l’exercice.

L’interface se présentera sous forme d’un tableau, avec les colonnes suivantes :
Nom du film / Réalisateur / Année de production / Nationalité / Dernière diffusion

Ajouter les filtres suivants sur les colonnes :
 - Recherche texte sur le nom du film et sur le réalisateur.
 - Mettre un sélecteur pour filtrer par nationalité, proposer que les pays disponibles dans les données.

Sur les autres colonnes, soyez force de proposition.
 - En haut à droite du tableau, ajouter une section “statistiques” avec les 3 boutons si dessous. Ils ouvriront une pop-up pour afficher les informations.
 - Top 5 des films les plus diffusés
 - Top 5 des films avec le meilleur ratio “Nombre de diffusion en 1ère partie / Nombre de diffusion total”
 - Top 5 des réalisateurs avec la meilleur moyenne de diffusion (Somme des moyennes de diffusion par an du réalisateur / nombre de film)

Spécification technique
Le choix des technologies utilisés devront se baser sur :
 - Angular 9 coté front, affichage du tableau
 - Symfony 3 ou plus coté back, pour retourner les données du JSON.
 - Ne pas se servir de base de donnée, ni de doctrine. Les données seront lu directement à partir du fichier JSON en pièce jointe (movies.json).
 - Ne pas utiliser d’authentification.

Nos attentes
Code suivant une normalisation stricte, standardisé.

Coté back :
 - Valider les connaissances de l’architecture MVC.
 - Connaissances POO
 - La gestion de dépendances.
 - Utilisation des composants symfony (Routing, Yaml etc....)

Coté front :
 - Utilisation de npm
 - Utilisation de service

UX
 - Si vous ne maîtrisez pas symfony, vous pouvez le laisser de coté et lire le json directement avec Angular.
 - Si vous n’avez pas assez de compétences, sur Angular, pour faire le code. Vous pouvez utiliser le framework de votre choix coté front.

Jeu de donnée
Classement des films les plus diffusés sur les chaînes nationales gratuites entre 2010 et 2019.
