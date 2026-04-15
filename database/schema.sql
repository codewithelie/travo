CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    status VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    progress INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO projects (title, status, description, progress) VALUES
('Rénovation cuisine', 'En cours', 'Remplacement du carrelage, reprise de la peinture et installation d’un nouvel îlot central.', 65),
('Réfection salle de bain', 'En attente', 'Travaux suspendus en attente de validation du choix de faïence par le propriétaire.', 35),
('Travaux électricité', 'Terminé', 'Mise aux normes du tableau électrique et remplacement de plusieurs prises murales.', 100),
('Aménagement des combles', 'En cours', 'Isolation, pose de cloisons et création d’un espace bureau sous toiture.', 50),
('Rénovation façade', 'En attente', 'Nettoyage, reprise des fissures et application d’un nouvel enduit extérieur.', 20),
('Pose de parquet salon', 'Terminé', 'Dépose de l’ancien revêtement et installation d’un parquet stratifié dans le séjour.', 100),
('Création terrasse extérieure', 'En cours', 'Préparation du sol, coulage de dalle et pose de carrelage extérieur.', 70),
('Remplacement des fenêtres', 'En attente', 'Commande des nouvelles menuiseries et planification de l’intervention.', 15),
('Réfection toiture', 'En cours', 'Remplacement de tuiles abîmées et amélioration de l’étanchéité générale.', 55),
('Peinture intérieure complète', 'Terminé', 'Préparation des murs, sous-couche et peinture des pièces principales.', 100);

CREATE TABLE project_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_updates_project
        FOREIGN KEY (project_id) REFERENCES projects(id)
        ON DELETE CASCADE
);

INSERT INTO project_updates (project_id, title, content) VALUES
(2, 'Début du chantier', 'Installation de la zone de travail et protection des surfaces.'),
(2, 'Livraison des matériaux', 'Les premiers matériaux ont été réceptionnés ce matin.'),
(2, 'Travaux suspendus', 'Le chantier est en attente de validation du choix de faïence.'),
(3, 'Fin de l’intervention', 'Les travaux électriques sont terminés et validés.');

