<?php

namespace App\Controller;

use App\Model\BoxInManager;
use App\Model\MailBoxManager;
use App\Model\MessageManager;
use Cassandra\Date;

class MailboxController extends AbstractController
{
    /**
     * List boxIn
     */
    public function mailbox(): string
    {
        if (!$this->isConnect()) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $manager = new MailBoxManager();
        if ($this->verifyRole("client")) {
            $tchats = $manager->selectAllMyTchatClient($this->user['id']);
        } else {
            $tchats = $manager->selectAllMyTchatFreelance($this->user['id']);
        }
        return $this->twig->render('pages/Mailbox/mailbox.html.twig', ['tchats' => $tchats]);
    }
    public function tchatShow(int $id): string
    {
        if (!$this->isConnect()) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }
        $manager = new MessageManager();
        $messages = $manager->selectAllMyTchat($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newMessage = [];
            $newMessage['message'] = $_POST['newMessage'];
            $newMessage['user_id'] = $this->user['id'];
            $newMessage['tchat_id'] = $messages[0]['tchat_id'];
            $manager->insert($newMessage);
            $chemin='Location: /tchatShow?id=' . $id;
            header($chemin);
            exit();
        }
        return $this->twig->render('pages/Mailbox/tchatShow.html.twig', ['messages' => $messages]);
    }
    public function createTchat(int $id): string
    {

        if (!$this->isConnect() && !$this->verifyRole("client")) {
            // Si oui, le rediriger vers la page d'accueil
            header('Location: /');
            exit();
        }

        $manager = new MailBoxManager();

        if ($manager->isAlreadyBeenCreated($this->user['id'],$id)) {
            var_dump($manager->isAlreadyBeenCreated($this->user['id'],$id));
            var_dump($this->user['id']);
            var_dump($id);
            die();
            $chemin = 'Location: /tchatShow?id=' . $manager->retrieveTchatId($this->user['id'],$id);
            header($chemin);
            die();
        }

        $messages = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tchat = [];
            $tchat['annonce_id'] = $id;
            $date = date('Y-m-d H:i:s');
            $tchat['created_at'] = $date;
            $tchat['object'] = $_POST['object'];
            $tchat['user_tchat_id'] = $this->user['id'];

            $manager = new MailBoxManager();
            $manager->insert($tchat);

            $newMessage = [];
            $newMessage['message'] = $_POST['newMessage'];
            $newMessage['user_id'] = $this->user['id'];
            $newMessage['tchat_id'] = $manager->returnLastId();

            $messageManager = new messageManager();
            $messageManager->insert($newMessage);
            $chemin='Location: /tchatShow?id=' . $newMessage['tchat_id'];
            header($chemin);
        }

        return $this->twig->render('pages/Mailbox/tchatShow.html.twig', ['messages' => $messages, 'id_annonce' => $id]);
    }

}
