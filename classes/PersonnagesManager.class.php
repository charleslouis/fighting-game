<?php
class PersonnagesManager
{
  private $_db; // Instance de PDO
 
  public function __construct($db)
  {
    $this->setDb($db);
  }
 
  public function add(Personnage $perso)
  {
    $q = $this->_db->prepare('INSERT INTO personnages_v2 SET nom = :nom, type = :type');
 
    $q->bindValue(':nom', $perso->nom());
    $q->bindValue(':type', $perso->type());

    $q->execute();

    $perso->hydrate(array(
      'id' => $this->_db->lastInsertId(),
      'degats' => 0,
      'atout' => 0,
    ));
  }
  
  public function count(){
    return $this->_db->query('SELECT COUNT(*) FROM personnages_v2')->fetchColumn();
  }


  public function delete(Personnage $perso){
    $this->_db->exec('DELETE FROM personnages_v2 WHERE id = '.$perso->id());
  }

 
  public function exists($info){
    if(is_int($info)){
      return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages_v2 WHERE id =' . $info)->fetchColumn();
    }

    $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages_v2 WHERE nom = :nom');
    $q->execute(array(':nom' => $info));

    return (bool) $q->fetchColumn();
  }


  public function get($info){
    if(is_int($info)){
      $q = $this->_db->query('SELECT id, nom, degats, timeEndormi, type, atout FROM personnages_v2 WHERE id =' . $info);
      $perso = $q->fetch(PDO::FETCH_ASSOC);
    }
    else {
      $q = $this->_db->prepare('SELECT id, nom, degats, timeEndormi, type, atout FROM personnages_v2 WHERE nom = :nom');
      $q->execute(array(':nom' => $info));
      $perso = $q->fetch(PDO::FETCH_ASSOC);
    }

    switch ($perso['type']) {
      case 'guerrier': return new Guerrier($perso);
      case 'magicien': return new Magicien($perso);    
      default: return null;
    }
  }
 
  public function getList($nom){
    $persos = array();

    $q = $this->_db->prepare('SELECT id, nom, degats, timeEndormi, type, atout FROM personnages_v2 WHERE nom <> :nom ORDER BY nom');
    $q->execute(array(':nom' => $nom));

    while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
      switch ($donnees['type']) {
        case 'guerrier': $persos[] = new Guerrier($donnees);
          break;
        case 'magicien': $persos[] = new Magicien($donnees);
          break;
      }
    }
    return $persos;
  }
 
  public function update(Personnage $perso){
    $q = $this->_db->prepare('UPDATE personnages_v2 SET degats = :degats, timeEndormi = :timeEndormi, atout = :atout  WHERE id = :id');
    $q->bindvalue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindvalue(':timeEndormi', $perso->timeEndormi(), PDO::PARAM_INT);
    $q->bindvalue(':atout', $perso->atout(), PDO::PARAM_INT);
    $q->bindvalue(':id', $perso->id(), PDO::PARAM_INT);

    $q->execute();
  }
 
  public function setDb(PDO $db){
    $this->_db = $db;
  }
}
?>