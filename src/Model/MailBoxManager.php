<?php

namespace App\Model;

use PDO;

class MailBoxManager extends AbstractManager
{
    public const TABLE = 'tchat';

    /**
     * Insert new item in database
     */
    public function isAlreadyBeenCreated(int $userId, int $annonceId): bool
    {
        $query ="SELECT COUNT(*) FROM " . self::TABLE .
            " WHERE user_tchat_id=:userId AND annonce_id=:annonceId";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userId', $userId,PDO::PARAM_INT);
        $statement->bindValue(':annonceId', $annonceId,PDO::PARAM_INT);
        $statement->execute();
        $nbUserTchatId =  $statement->fetchColumn();
        if ($nbUserTchatId > 0 ) {
            return true;
        }

        return false;
    }

    public function retrieveTchatId(int $userId, int $annonceId) : int
    {
        $query = "SELECT id FROM " . self::TABLE .
            " WHERE user_tchat_id=:userId AND annonce_id=:annonceId";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
        $statement->bindValue(':annonceId', $annonceId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchColumn();
    }
    public function insert(array $tchat) : void{


        //on inset un nouveau tchat si et seulement si l'utilisateur n'en a pas envoyer avant
        if(!$this->isAlreadyBeenCreated($tchat['user_tchat_id'],$tchat['annonce_id'])){

            $query = "INSERT INTO " . self::TABLE . "(`annonce_id`, `user_tchat_id`, `created_at`, `object`) VALUES (:annonce_id,:user_tchat_id,:created_at,:object)";
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':annonce_id',$tchat['annonce_id'],PDO::PARAM_INT);
            $statement->bindValue(':user_tchat_id',$tchat['user_tchat_id'],PDO::PARAM_INT);
            $statement->bindValue(':created_at', date('Y-m-d H:i:s', strtotime($tchat['created_at'])), PDO::PARAM_STR);
            $statement->bindValue(':object',$tchat['object'],PDO::PARAM_STR);
            $statement->execute();
        }

    }
    public function selectAllMyTchatClient(int $id) : array
    {
        $query = "SELECT tchat.id, tchat.object, tchat.created_at, user.firstname, user.lastname, annonce.title FROM " .  self::TABLE . "
        JOIN annonce on tchat.annonce_id=annonce.id
        JOIN user ON annonce.user_freelance_id=user.id
        WHERE user_tchat_id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id',$id,PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectAllMyTchatFreelance(int $id) : array
    {
        $query = "SELECT tchat.id, tchat.object, tchat.created_at, user.firstname, user.lastname FROM " .  self::TABLE . "
        JOIN annonce on annonce.id=" .  self::TABLE . ".annonce_id
        JOIN user ON tchat.user_tchat_id=user.id
        WHERE annonce.user_freelance_id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id',$id,PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
    public function returnLastId():int|false
    {
        $query = 'SELECT MAX(id) FROM ' . self::TABLE ;
        $id = $this->pdo->query($query)->fetchColumn();

        return $id;
    }

}
