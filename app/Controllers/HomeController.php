<?php

class HomeController extends Controller {

    public function index() {
        $this->view('home/index');
    }

    public function about() {
        echo "<h1>À propos</h1>";
        echo "<p>Voici les informations sur l'entreprise.</p>";
    }
}