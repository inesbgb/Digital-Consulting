<?php

namespace App\Controller;

use App\Model\UserManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController extends AbstractController
{
    /**
     * List user
     */
    //ok
    //vérifie que l'utilisateur n'a pas fait d'erreur (saif le mail), retourne tout les erreurs
    private function verifyErrors(): array
    {
        $errors = [];
        //verifier que l'utilsateur a rempli tout les champs correctement
        if (!isset($_POST['sex'])) {
            $errors[] = "Veuillez choisir votre genre";
        }
        if (!isset($_POST['firstname']) || empty(trim($_POST['firstname']))) {
            $errors[] = "Veuillez completer notre prenom";
        } elseif (strlen($_POST['firstname']) > 100) {
            $errors[] = "Le champ prenom doit possèder moins de 100 caracteres";
        }
        if (!isset($_POST['lastname']) || empty(trim($_POST['lastname']))) {
            $errors[] = "Veuillez completer notre nom";
        } elseif (strlen($_POST['lastname']) > 100) {
            $errors[] = "Le champ nom doit possèder moins de 100 caracteres";
        }
        if (!isset($_POST['adress']) || empty(trim($_POST['adress']))) {
            $errors[] = "Veuillez completer notre adresse";
        } elseif (strlen($_POST['adress']) > 100) {
            $errors[] = "Le champ adresse doit possèder moins de 100 caracteres";
        }
        if (!isset($_POST['phone']) || empty(trim($_POST['phone']))) {
            $errors[] = "Veuillez completer notre numero de telephone";
        } elseif (strlen($_POST['phone']) > 20 || strlen($_POST['phone']) < 10 || !ctype_digit($_POST['phone'])) {
            $errors[] = "Le champ telephone est mal noté";
        }
        if (!isset($_POST['password']) || empty(trim($_POST['password']))) {
            $errors[] = "Veuillez completer notre mot de passe";
        } elseif (!isset($_POST['password']) || (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 8)) {
            $errors[] = "Le champ mot de passe doit possèder entre 8 et 20 caracteres";
        } elseif (!isset($_POST['confmdp']) || $_POST['password'] !==  $_POST['confmdp']) {
            $errors[] = "Veuillez confirmez votre mot de passe";
        }

        return $errors;
    }

    //ok
    private function verityMail() : array{
        $errors = [];
        $manager = new UserManager();
        if (!isset($_POST['mail']) || empty(trim($_POST['mail']))) {
            $errors[] = "Veuillez completer notre adresse mail";
        }
        $nbMail = $manager->nombreMail($_POST['mail']);
        if ($nbMail > 0) {
            $errors[] = 'Cette adresse mail à deja ete utilise';
        }
        return $errors;
    }

    //ok
    public function createProfil(string $role): string
    {
        //si l'utilisateur est déjà connecté, il est renvoyé vers sa page profil
        if ($this->isConnect()) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $errors = [];
        $user = [];

        if (!empty($_POST)) {
            $errors = $this->verifyErrors();
            $errors[] = $this->verityMail();

            if (!empty($errors)) {
                $user = array_map('trim', $_POST);
                $user['role'] = $role;
                $manager = new UserManager();

                if ($manager->insert($user)) {
                    return $this->login();
                }
//                $_SESSION['user_id'] = $manager->insert($user);
//                $chemin = 'Location: /profil';
//                header($chemin);
//                exit();
            }
        }
        return $this->twig->render('pages/User/createProfil.html.twig', ['errors' => $errors]);
    }

    //ok
    public function login(): string
    {
        //si l'utilisateur est déjà connecté, il est renvoyé vers sa page profil
        if ($this->isConnect()) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail']) && isset($_POST['password'])) {
            $visitor = array_map('trim', $_POST);
            $userManager = new UserManager();
            $user = $userManager->selectOneByEmail($visitor['mail']);
            if ($user && password_verify($visitor['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                if (isset($_SESSION['user_id'])) {
                    header('Location: /profil');
                    exit();
                }
            }
        }
        return $this->twig->render('pages/User/login.html.twig');
    }

    //faire le manager edit puis tester si ca fonctionne
    public function profil(): string
    {

        //si l'utilisateur est n'est pas connecté, il est renvoyé vers sa page profil
        if (!$this->isConnect()) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }

        $errors = [];
        $user = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

            $user = [];
            $errors = $this->verifyErrors();

            //enregistre les modifications
            if (empty($errors)) {
                $user = array_map('trim', $_POST);
                $user['id'] = $this->user['id'];
                $manager = new UserManager();
                $manager->edit($user);
                header('Location: /profil');
                exit();
            }
        }
        return $this->twig->render('pages/User/profil.html.twig', ['errors' => $errors]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    //ok
    public function signin(): string
    {
        //si l'utilisateur est déjà connecté, il est envoyer vers sa page profil
        //si l'utilisateur est déjà connecté, il est renvoyé vers sa page profil
        if ($this->isConnect()) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }

        $errors = [];

        if (!empty($_POST) /* && isset($_POST['buttonIsAFreelance'])*/) {
            if (!isset($_POST['isAClient']) || empty(trim($_POST['isAClient']))) {
                $errors[] = "Veuillez choissir si vous etes freelanceur ou Client";
            }
            //ici ou dans la fonction createdProfil
            if (empty($errors)) {
                $role = $_POST['isAClient'];
                $chemin = 'Location: /createProfil?role=' . $role ;
                header($chemin);
                exit();
            }
        }
        return $this->twig->render('pages/User/signin.html.twig', ['errors' => $errors]);
    }

    //ok
    public function logout()
    {
        //si l'utilisateur n'est pas connecté, il est renvoyé vers sa page profil
        if (!$this->isConnect()) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        // Détruire toutes les variables de session
        session_unset();
        // Détruire la session
        session_destroy();
        header('Location: /');
        exit();
    }
}
