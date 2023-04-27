<?php

namespace App\Model;

use PDO;

class MessageManager extends AbstractManager
{
    public const TABLE = 'message';

    /**
     * Insert new message in database
     */
    public function insert(array $message) : void
    {
        $query = "INSERT INTO " . self::TABLE . "(`message`, `user_id`, `tchat_id`) VALUES (:message,:user_id,:tchat_id)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':message',$message['message']);
        $statement->bindValue(':user_id',$message['user_id'],PDO::PARAM_INT);
        $statement->bindValue(':tchat_id',$message['tchat_id'],PDO::PARAM_INT);

        $statement->execute();
    }
    public function selectAllMyTchat(int $idTchat) : array
    {
        $query = "SELECT * FROM " .  self::TABLE . "
        JOIN user ON message.user_id=user.id
        WHERE tchat_id=:idTchat
        ORDER BY message.id ";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':idTchat',$idTchat,PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }


}
