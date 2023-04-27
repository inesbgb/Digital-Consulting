<?php

namespace App\Model;

use PDO;

class BillManager extends AbstractManager
{
    public const TABLE = 'facture';

    public function selectAllById(int $userId): array
    {
        $query =  'SELECT facture.id as facture_id, annonce.id as id,annonce.* FROM ' . static::TABLE .
            ' JOIN annonce ON annonce.id = facture.annonce_id  '.
            'LEFT JOIN user ON user.id=facture.user_client_id
                WHERE user_client_id=:userId';

        $stmt=$this->pdo->prepare($query);
        $stmt->bindValue(':userId',$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insert(array $facture): void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (`user_client_id`,`annonce_id`) VALUE (:user_client_id,:annonce_id)';
        $stmt=$this->pdo->prepare($query);
        $stmt->bindValue(':user_client_id',$facture['user_client_id'],PDO::PARAM_INT);
        $stmt->bindValue(':annonce_id',$facture['annonce_id'],PDO::PARAM_INT);

        $stmt->execute();
    }

    public function selectByAnnonce(int $userId) : array
    {
        $query =  'SELECT facture.id as facture_id, annonce.id as id,annonce.* FROM ' . static::TABLE .
            ' JOIN annonce ON annonce.id = facture.annonce_id  '.
            'LEFT JOIN user ON user.id=facture.user_client_id
                WHERE annonce.user_freelance_id=:userId';

        $stmt=$this->pdo->prepare($query);
        $stmt->bindValue(':userId',$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
