<?php

/**
 * Contrôleur pour la page d'accueil
 */
class HomeController extends AbstractController
{
    /**
     * Afficher la page d'accueil
     */
    public function index(): void
    {
        $title = 'Accueil';
        $this->render('home', compact('title'));
    }
}
