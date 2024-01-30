<?php
class DateC{
    private $datec;
    private $storage = "dataCalendrier.json";
    private $folderDate = "dataDate/";
    private $stored_datec;
    private $new_datec;



    public function __construct($datec){
        $this->datec = $datec;
        $this->stored_datec = json_decode(file_get_contents($this->storage), true);
        $this->new_datec = ["datec" => $this->datec];
        if(true){
            $this->insertDatec();
        }
        
    }
    
    public function datec_existe(){
        $this->stored_datec = json_decode(file_get_contents($this->storage),true);
        foreach ($this->stored_datec as $dat) {
            if($this->datec == $dat['datec']) {
                return true;
            }
        } 
        return false;
    }

    private function insertDatec(){
        $tempsArray = array();
        $horaireArray= array();
        /**
         * test
         */
        $chrono = 8;
        $h="h";
        $timer="00";
        $horaire = $chrono.$h.$timer;
        for($i=0;$i<45;$i++){
            array_push($horaireArray,$horaire);
            $timer = $timer+15;
            if($timer == 60){
                $chrono = $chrono+1;
                $timer = "00";
            }
            $horaire = $chrono.$h.$timer;
            
        }
        /***/
        if($this->datec_existe() == FALSE){
            $this->stored_datec[] = $this->new_datec;
            if(file_put_contents($this->storage, json_encode($this->stored_datec, JSON_PRETTY_PRINT))){
                for($i =0; $i<count($horaireArray);$i++){
                    $tempsArray[$horaireArray[$i]] = array(
                                        'Lundi'=>array(
                                            'Groupe 1'=>array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            ), 
                                            'Groupe 2' => array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            )
                                            ),           
                                        'Mardi' => array(
                                            'Groupe 1'=>array(
                                                'val' => 0,
                                                'Cours' => ""  ,
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            ), 
                                            'Groupe 2' => array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            
                                            )
                                        ),   
                                        'Mercredi'=>array(
                                            'Groupe 1'=>array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            ), 
                                            'Groupe 2' => array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            )
                                        ),  
                                        'Jeudi'=>array(
                                            'Groupe 1'=>array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            ), 
                                            'Groupe 2' => array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            )
                                        ),  
                                        'Vendredi'=>array(
                                            'Groupe 1'=>array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            ), 
                                            'Groupe 2' => array(
                                                'val' => 0,
                                                'Cours' => "",
                                                'Class' => "caseNormal",
                                                'id' => 0
                                            )
                                        )           
                    );
                }
            }
            
                file_put_contents($this->folderDate.$this->datec, json_encode($tempsArray, JSON_PRETTY_PRINT));
                chmod($this->folderDate.$this->datec, 0777);
                       
                return true;
                }
           
            return false;
        
    }



}
?>
