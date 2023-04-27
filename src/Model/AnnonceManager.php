<?php

namespace App\Model;

use PDO;

class AnnonceManager extends AbstractManager
{
    public const TABLE = 'annonce';

    /**
     * Insert new annonce in database
     */


    public function insert(array $annonce): void
    {
        $query = "INSERT INTO " . self::TABLE . "(`service_id`, `user_freelance_id`, `title`, `price`, `description`) VALUES (:service_id,:user_freelance_id,:title,:price,:description)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':service_id', $annonce['service_id'], PDO::PARAM_INT);
        $statement->bindValue(':user_freelance_id', $annonce['user_freelance_id'], PDO::PARAM_INT);
        $statement->bindValue(':title', $annonce['title']);
        $statement->bindValue(':price', $annonce['price'], PDO::PARAM_INT);
        $statement->bindValue(':description', $annonce['description']);

        $statement->execute();
    }

    public function selectAnnonceByUserId(int $userId): array
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE user_freelance_id=:user_freelance_id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':user_freelance_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    //pour modifier une annonce
    public function edit(int $id, array $newAnnonce): void
    {

        $query = "UPDATE " . self::TABLE . " SET `service_id` = :service_id, `title` = :title, `price` = :price, `description` = :description WHERE id = :id";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':service_id', $newAnnonce['service_id'], PDO::PARAM_INT);
        $statement->bindValue(':title', $newAnnonce['title'], PDO::PARAM_STR);
        $statement->bindValue(':price', $newAnnonce['price'], PDO::PARAM_INT);
        $statement->bindValue(':description', $newAnnonce['description'], PDO::PARAM_STR);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    //pour supprimer definitivement une annonce
    public function delete(int $id): void
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE id = :id" ;
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectByService(string $nameService): array
    {
        $query = "SELECT *,annonce.id as id, service.id as service_id FROM " . self::TABLE . " INNER JOIN service ON annonce.service_id = service.id WHERE is_delete = 0 AND service.name = :nameService";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':nameService', $nameService);
        $statement->execute();
        $statement = $statement->fetchAll();
        return $statement;
    }

}
