<?php
require_once("Package.php");

class Seats{
    private $package;
    private $seats;
    private $listings;
    
    function __construct($package){
        $this->package = $package;
        $this->_getSeats();
    }
    
    public function getSeats(){
        return $this->seats;
    }
    
    public function getListings(){
        return $this->listings;
    }

    public function getSoldListings(){
        $arr = array();
        $listings = $this->listings;
        //print_r($listings);
        foreach($listings as $listing){
            if($listing['Active'] == '2'){
                $arr[] = $listing;
            }
        }
        return $arr;
    }
    
    
    
    
    private function _getSeats(){
		$this->seats = getTableSearch('seat', '*', 'Package_Id', $this->package);
        $this->listings = getTableSearch('listing', '*', 'Package_Id', $this->package);
    }
}