<?php

namespace App\Controller;

use App\Model\BillManager;
use App\Model\ServiceManager;

class BillController extends AbstractController
{
    /**
     * List boxIn
     */
    public function index():string
    {
        if (!$this->isConnect() && !$this->verifyRole("client")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $billManager= new BillManager();
        $bills= $billManager->selectAllById($this->user['id']);
        $services = new ServiceManager();
        $services = $services->selectAll("name");
        return $this->twig->render("pages/Bill/myBills.html.twig",['bills' => $bills, 'services' => $services]);
    }
    public function add(int $id):void
    {
        if (!$this->isConnect() && !$this->verifyRole("client")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $billManager= new BillManager();
        $facture['user_client_id'] = $this->user['id'];
        $facture['annonce_id'] = $id ;
        $bills= $billManager->insert($facture);
        header('Location: /Bill');
        exit();
    }

    public function order(): string
    {
        if (!$this->isConnect() && !$this->verifyRole("frelance")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $billManager= new BillManager();
        $bills= $billManager->selectByAnnonce($this->user['id']);
        $services = new ServiceManager();
        $services = $services->selectAll("name");
        return $this->twig->render("pages/Bill/myOrders.html.twig",['bills' => $bills, 'services' => $services]);
    }
}
