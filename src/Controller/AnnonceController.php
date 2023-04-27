<?php

namespace App\Controller;

use App\Model\AnnonceManager;
use App\Model\ServiceManager;
use App\Controller\S_SESSION;
use MongoDB\Driver\Manager;

class AnnonceController extends AbstractController
{
    /**
     * List Annonce
     */
    private function verifyErrors(): array
    {
        $errors = [];
        if (!isset($_POST['title']) || empty(trim($_POST['title']))) {
            $errors[] = "Veuillez mettre un titre à votre annonce";
        } elseif (strlen($_POST['title']) > 100) {
            $errors = "Votre titre doit faire moins de 100 caracteres";
        } if (!isset($_POST['description']) || empty(trim($_POST['description']))) {
            $errors[] = "Veuillez completer le description de votre annonce";
        }
        if (!isset($_POST['service_id'])) {
            $errors[] = "Veuillez choissir le service que vous desirez offrir à vos futurs clients";
        }
        if (!isset($_POST['price']) || empty(trim($_POST['price']))) {
            $errors[] = "Veuillez remplir votre champ prix";
        } elseif (intval($_POST['price']) <= 0) {
            $errors[] = "Veuillez mettre un nombre positif";
        } elseif (intval($_POST['price']) > 10000) {
            $errors[] = "N'abusez pas sur les prix, vous n'êtes pas mozart et encore moins Elon MUSK ";
        }
        $serviceManager = new ServiceManager();
        if (isset($_POST['service_id'])) {
            $service = $serviceManager->selectOneById($_POST['service_id']);
            if (in_array($service['name'], $serviceManager->selectAll())) {
                $errors[] = 'Il y a une erreur avec le champ service';
            }
        }
        if (!isset($_SESSION['user_id'])) {
            $errors = 'Vous n\'êtes pas connectés désolée je ne peux pas accepter votre annonce';
            echo 'probleme connection';
            die();
        }
        return $errors;
    }

    //ok
    public function createAd(): string
    {
        //si l'utilisateur est déjà connecté et que c'est bien un freelanceur , il est renvoyé vers sa page profil
        if (!$this->isConnect() && !$this->verifyRole("frelance")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && isset($_POST['buttonCreateAd'])) {
            $errors = $this->verifyErrors();

            if (empty($errors)) {
                $manager = new AnnonceManager();
                $annonce = array_map("trim", $_POST);
                $annonce['service_id'] = $_POST['service_id'];
                $annonce['user_freelance_id'] = $_SESSION['user_id'];
                $manager->insert($annonce);
                header('Location: /myAds');
                exit();
            }
        }
        $serviceManager = new ServiceManager();
        $services = $serviceManager->selectAll("name");

        return $this->twig->render('pages/Annonce/createAd.html.twig', ["services" => $services, "errors" => $errors]);
    }

    public function designAndCreation(): string
    {
        //si l'utilisateur est déjà connecté et que c'est bien un freelanceur , il est renvoyé vers sa page profil
        if (!$this->isConnect() && !$this->verifyRole("frelance")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }

        $manager = new AnnonceManager();
        $annonces = $manager->selectAnnonceByUserId($_POST['service']);
        return $this->twig->render('pages/Annonce/designAndCreation.html.twig', ['annonces' => $annonces]);
    }
    public function myAds(): string
    {
        //si l'utilisateur est déjà connecté et que c'est bien un freelanceur , il est renvoyé vers sa page profil
        if (!$this->isConnect() && !$this->verifyRole("frelance")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }

        $manager = new AnnonceManager();
        $myAnnonces = $manager->selectAnnonceByUserId($_SESSION['user_id']);
        $services = new ServiceManager();
        $services = $services->selectAll("name");
        $page = 'pages/Annonce/myAds.html.twig';
        return $this->twig->render($page, ['myAnnonces' => $myAnnonces,'services' => $services]);
    }

    public function edit(int $id): string
    {
        //si l'utilisateur est déjà connecté et que c'est bien un freelanceur , il est renvoyé vers sa page profil
        if (!$this->isConnect() && !$this->verifyRole("frelance")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $errors = $this->verifyErrors();

        if (empty($errors)) {

            $newAnnonce = array_map('trim', $_POST);
            $manager = new AnnonceManager();
            $manager->edit($id, $newAnnonce);
            $manager->selectAnnonceByUserId($_SESSION['user_id']);
            header('Location: /myAds');
            exit();
        }
        return $this->twig->render('pages/Annonce/MyAds.html.twig');
    }


    public function delete(int $id): void
    {
        //si l'utilisateur est déjà connecté et que c'est bien un freelanceur , il est renvoyé vers sa page profil
        if (!$this->isConnect() && !$this->verifyRole("frelance")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }

        $manager = new AnnonceManager();
        $manager->delete($id);
        $manager->selectAnnonceByUserId($_SESSION['user_id']);
        header('Location: /myAds');
        exit();
    }

    public function theAds(string $service) : string
    {
        $manager = new AnnonceManager();
        $annonces = $manager->selectByService($service);
        $services = new ServiceManager();
        $services = $services->selectAll("name");
        return $this->twig->render('pages/Annonce/theAds.html.twig',['annonces' => $annonces, "services" => $services ] );
    }
}
