<?php
class db{
    
    
    private $file = "db.json";
    private $json;
    
    public function __construct(){
        $this->json = json_decode(file_get_contents($this->file),true);
    }
    public function save(){
    
        if(file_put_contents($this->file, json_encode($this->json))){
            return true;
        } else
            return false;
    }
    public function add($instaId, $id, $code){
        if(isset($this->json[$id])){
            $this->update($id, $instaId, $code);
        } else {
            $this->json[$id] = array("instaId" => $instaId,
                                "code" => $code);
            if($this->save())
                return true;
            else
                return false;
        }
    }
    public function update($id, $instaId = null, $code = null){
        if($instaId != null){
            $this->json[$id]['instaId'] = $instaId;
        }
        if($code != null){
            $this->json[$id]['code'] = $code;
        }
    }
    public function get($id){
        if($this->json[$id]){
            return $this->json[$id];
        } else {
            return false;
        }
    }
    public function getALL(){
        return $this->json;
    }

}
?>