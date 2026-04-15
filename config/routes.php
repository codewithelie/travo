<?php

//Routes pour la page d'accueil et autre pages statiques
$router->get('/', [$homeController, 'index']);
$router->get('/about', [$homeController, 'about']);

// Routes pour les projets
$router->get('/projects', [$projectController, 'index']);
$router->get('/projects/create', [$projectController, 'create']); //Afficher le formulaire de création
$router->post('/projects/store', [$projectController, 'store']); //Ajouter le projet en BDD
$router->get('/projects/{id}', [$projectController, 'show']);


