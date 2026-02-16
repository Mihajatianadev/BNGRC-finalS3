<?php
class ObjetRepository {
  private $pdo;
  public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public static function getAllList()
    {
        $pdo = Flight::db();

        $sql = "SELECT o.* , i.lien_image FROM objet o join image i on i.id_obj = o.id_objet group by o.id_objet";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
