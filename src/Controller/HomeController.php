<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    /**
     * Display home page
     */
    public function index(): string
    {
        return $this->twig->render('pages/Home/home.html.twig');
    }
    public function apropos(): string
    {
        return $this->twig->render('pages/Home/apropos.html.twig');
    }
    public function signout(): string
    {
        //mettre ici l'algorimthe pour se déconnecter
        return $this->twig->render('pages/Home/home.html.twig');
    }
}
