<?php

namespace App\Model;

use PDO;

class UserManager extends AbstractManager
{
    /**
     * Insert new item in database
     */
    public const TABLE = 'user';

    //ok
    //permet de créer un nouveau utilisateur
    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . static::TABLE .
            " (`firstname`, `lastname`, `adress`,`mail`, `password`, `sex`, `phone`, `role`)
            VALUES (:firstname, :lastname, :adress, :mail, :password, :sex, :phone, :role )");
        $statement->bindValue(':firstname', $user ['firstname']);
        $statement->bindValue(':lastname', $user ['lastname']);
        $statement->bindValue(':adress', $user ['adress']);
        $statement->bindValue(':mail', $user ['mail']);
        $statement->bindValue(':password', password_hash($user['password'], PASSWORD_DEFAULT));
        $statement->bindValue(':sex', $user ['sex']);
        $statement->bindValue(':phone', $user ['phone']);
        $statement->bindValue(':role', $user ['role']);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    //ok
    //permet de recuperer les informations d'un utilisateur grace a son adresse mail
    public function selectOneByEmail(string $mail): array|false
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' WHERE mail=:mail';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('mail', $mail, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    //a completer
    //permet de modifier les informations d'un utilisateur
    //ATTENTION ON NE MODIFIE PAS LE ROLE A NE PAS METTRE
    public function edit(array $user): void
    {
        $query = "UPDATE " . static::TABLE .
            " SET `firstname`=:firstname, `lastname`=:lastname,`adress`=:adress, `password`=:password, `sex`=:sex, `phone`=:phone WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':firstname', $user ['firstname']);
        $statement->bindValue(':lastname', $user ['lastname']);
        $statement->bindValue(':adress', $user ['adress']);
        $statement->bindValue(':password', password_hash($user['password'], PASSWORD_DEFAULT));
        $statement->bindValue(':sex', $user ['sex']);
        $statement->bindValue(':phone', $user ['phone']);
        $statement->bindValue(':id', $user ['id']);

        $statement->execute();
    }

    //avant de la faire voir comme on peut changer la database pour supprimer l'utilisateur + tout ce qui va avec. Ou peut etre juste rajouter un type isDelete, plus simple
    public function delete(int $id): void
    {
    }
    //a vérifié si elle marche
    public function nombreMail(string $mail): int
    {
        $query = "SELECT COUNT(mail) FROM user WHERE mail=:mail";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':mail', $mail);
        $statement->execute();
        return $statement->fetchColumn();
    }
}
