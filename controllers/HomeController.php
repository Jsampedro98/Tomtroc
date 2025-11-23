<?php

/**
 * Contrôleur pour la page d'accueil
 *
 * Gère l'affichage de la landing page avec les derniers livres ajoutés
 *
 * @package    TomTroc
 * @subpackage Controllers
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
class HomeController extends AbstractController
{
    /**
     * Instance du modèle Book
     *
     * @var Book
     */
    private Book $bookModel;

    /**
     * Constructeur du contrôleur d'accueil
     *
     * Initialise le modèle Book pour récupérer les derniers livres
     *
     * @return void
     */
    public function __construct()
    {
        $this->bookModel = new Book();
    }

    /**
     * Afficher la page d'accueil
     *
     * Récupère les 4 derniers livres ajoutés pour l'affichage
     * sur la page d'accueil
     *
     * @return void
     */
    public function index(): void
    {
        // Récupérer les 4 derniers livres ajoutés
        $latestBooks = $this->bookModel->getLatestBooks(4);

        $this->render('home', [
            'latestBooks' => $latestBooks
        ]);
    }
}
