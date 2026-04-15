<?php

class ProjectController extends Controller {

    private Project $projectModel;

    public function __construct()
    {
        $this->projectModel = new Project();
    }

    public function index() {
        $projects = $this->projectModel->getAll();

        $this->view('projects/index', ['projects' => $projects]);
    }

    public function show($id): void
    {   
        $project = $this->projectModel->getById($id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $this->view('projects/show', ['project' => $project]);
    }

    public function create(): void
    {
        $this->view('projects/create');
    }

    function store(): void{
        $validator = Validator::make($_POST, [
            'title' => 'required|max:255',
            'status' => 'required|in:En cours,Terminé,En attente',
            'description' => 'required',
            'progress' => 'required|integer|between:0,100'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            Notification::setFlash('error', implode('<br>', $errors));
            header('Location: ' . BASE_URL . '/projects/create');
            exit;
        }

        if(!$this->projectModel->create([
            'title' => $title,
            'status' => $status,
            'description' => $description,
            'progress' => $progress
        ])) {
            Notification::setFlash('error', 'Une erreur est survenue lors de la création du projet.');
            header('Location: ' . BASE_URL . '/projects/create');
            exit;
        }

        Notification::setFlash('success', 'Projet créé avec succès.');
        header('Location: '. BASE_URL . '/projects');
        exit;
    }

    public function edit($id): void
    {
        $project = $this->projectModel->getById((int) $id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $this->view('projects/edit', ['project' => $project]);
    }

    public function update($id): void
    {
        $project = $this->projectModel->getById((int) $id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $progress = (int) ($_POST['progress'] ?? 0);

        $validator = Validator::make($_POST, [
            'title' => 'required|max:255',
            'status' => 'required|in:En cours,Terminé,En attente',
            'description' => 'required',
            'progress' => 'required|integer|between:0,100'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            Notification::setFlash('error', implode('<br>', $errors));
            header('Location: ' . BASE_URL . '/projects/edit/' . (int) $id);
            exit;
        }

        $this->projectModel->update((int) $id, [
            'title' => $title,
            'status' => $status,
            'description' => $description,
            'progress' => $progress
        ]);

        Notification::setFlash('success', 'Projet mis à jour avec succès.');
        header('Location: ' . BASE_URL . '/projects/' . (int) $id);
        exit;
    }

    public function destroy($id): void
    {
        $project = $this->projectModel->getById((int) $id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $this->projectModel->delete((int) $id);

        Notification::setFlash('success', 'Projet supprimé avec succès.');
        header('Location: ' . BASE_URL . '/projects');
        exit;
    }
}