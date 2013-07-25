<?php
class Personnage
{
  protected $atout,
            $degats,     
            $id,
            $nom,
            $timeEndormi,
            $type;
  
  const CEST_MOI = 1;
  const PERSONNAGE_TUE = 2;
  const PERSONNAGE_FRAPPE = 3;
  const PERSONNAGE_ENSORCELE = 4;
  const PAS_DE8MAGIE = 5;
  const PERSO_ENDORMI = 6;

  //constructeur
  public function __construct(array $donnees){
    $this->hydrate($donnees);
    $this->type = strtolower(get_class($this));
  }

  // HYDRATATION
  public function hydrate($donnees){
    foreach ($donnees as $key => $value) {
      $method = 'set'.ucfirst($key);

      if(method_exists($this, $method)){
        $this->$method($value);
      }
    }
  }

  //METHODES

  public function estEndormi(){
    return $this->timeEndormi > time();
  }

  public function frapper(Personnage $perso){
    if($perso->id() == $this->id){
      return self::CEST_MOI;
    }
    if ($this->estEndormi()){
      return self::PERSO_ENDORMI;
    }
    return $perso->recevoirDegats();
  }
  
  public function recevoirDegats(){
    $this->degats += 5;

    if($this->degats >= 100){
      return self::PERSONNAGE_TUE;
    }
    return self::PERSONNAGE_FRAPPE;
  }

  public function reveil(){
    $secondes = $this->timeEndormi;
    $secondes -= time();

    $heures   = floor($secondes / 3600);
    $secondes -= $heures * 3600;
    $minutes  = floor($secondes / 60);
    $secondes -= $minutes * 60;

    return $heures . 'h, ' . $minutes . 'min et ' . $secondes . 'sec';
  }

  public function nomValide(){
    return !empty($this->nom);
  }


  // GETTERS
  public function atout(){
    return $this->atout;
  }

  public function id(){
    return $this->id;
  }

  public function degats(){
    return $this->degats;
  }

  public function nom(){
    return $this->nom;
  }

  public function type(){
    return $this->type;
  }

  public function timeEndormi(){
    return $this->timeEndormi;
  }

  // SETTERS
  public function setAtout($atout){
    $atout = (int) $atout;
    if ($atout >= 0 && $atout <= 100){
      $this->atout = $atout;
    }
  }

  public function setId($id){
    $id = (int) $id;
    if ($id > 0){
      $this->id = $id;
    }
  }

  public function setDegats($degats){
    $degats = (int) $degats;
    if ($degats >= 0 && $degats <= 100){
      $this->degats = $degats;
    }
  }

  public function setNom($nom){
    if (is_string($nom)){
      $this->nom = $nom;
    }
  }
  
  public function setTimeEndormi($time){
    $this->timeEndormi = (int) $time;
  }

}
?>
