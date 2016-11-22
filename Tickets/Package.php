<?php

class Package{
    
    private $tickets;
    private $pid;
    private $id;
    
    private $sport;
    private $section;
    private $seatQty;
    private $year;
    private $team;
    private $price;
    private $activeState;
    
    public function __construct($id, $pid){
        $this->tickets = $this->_getPackages($id, $pid);
        $this->pid = $pid;
        $this->userId = $id;
        $this->_getPackageInfo();
    }
    
    public function menu(){
        $view = (isset($_GET['view'])) ? $_GET['view'] : "cal";
        echo '
        <div class="col-xs-4" style="text-align:center">
	    <div class="btn-toolbar">
		<div class="btn-group">
                    <a class="btn btn-primary " href="ticket.php?pid='.$this->pid.'&view=cal">Calendar</a>
                    <a class="btn btn-midnight-blue btn-primary" href="ticket.php?pid='.$this->pid.'&view=list">Bulk edit</a>
		</div>
            </div>
	</div>
	<div class="col-xs-6" style="text-align:center">
            <div class="btn-toolbar">
		<div class="btn-group">';
                    foreach($this->tickets as $ticket){
			$teams = getTeam($ticket['Team']);
			$active = ($this->pid == $ticket['Package_Id']) ? "current" : "";
			echo "<a class='teams-top-bar ".$active."' href='".basename($_SERVER['PHP_SELF'])."?pid=".$ticket['Package_Id']."&view=".$view."'>".$teams['Team_Name']."</a>";
                    }
	echo '
                </div>
            </div>
	</div>
	<div class="col-xs-2" style="text-align:right; margin-bottom:10px;">
			<a  href="ticketreport.php?pid='.$this->pid.'" class="hover14"><figure><img src="flat-ui/images/icons/png/reports.png" width="32px" ></figure></a>
            <a  href="ticketedit.php?pid='.$this->pid.'" class="hover06"><img src="flat-ui/images/icons/png/settings.png" width="32px" ></a>
	</div>';
    }
    
    public function getPackageId(){
        return $this->pid;
    }
    
    public function getPackageSport(){
        return $this->getTeam($this->team)['sport'];
    }
    
    public function getPackageSection(){
        return $this->section;
    }
    
    public function getPackageQty(){
        return $this->seatQty;
    }
    
    public function getPackageYear(){
        return $this->year;
    }
    
    public function getPackageTeam(){
        return $this->team;
    }
    
    public function getPackagePrice(){
        return $this->price;
    }
    
    public function getPackageState(){
        return $this->activeState;
    }
    
    public function getPackageTeamName(){
	return getTeam($this->team)['Team_Name'];
    }
    
    public function getPackageTeamId(){
	return getTeam($this->team)['Stubhub_Performer_Id'];
    }
    
    public function getPackageTeamCity(){
	return $this->getTeam($this->team)['City'];
    }
    
    public function getPackageTeamVenue(){
	return $this->getTeam($this->team)['Venue_Id'];
    }
    
    
    private $getPackages_Cache=array();
    private function _getPackages($user){
    
		global $conn, $id, $getPackages_Cache;
		if(!isset($getPackages_Cache[$user])){
			if($user != $id){
				$msg = 'User '.$id.' tried to impersonate user '.$user.'\nSecond line of text';    
				$msg = wordwrap($msg,70);
				mail("mplushnick@gmail.com","Security Alert!",$msg);
			} else {
				//$sql = getTableSearch("package","*", "User_Id", $user);
				foreach($sql as $row1){
					if($row1['Active'] != '0'){
						$row[] = $row1;
					}
				}
				//print_r($sql);
				$getPackages_Cache[$user] = $row;
				return $row;
			}
		} else {
			return $getPackages_Cache[$user];
		}
    }
    private function _getPackageInfo(){
	$sql = getTableSearch("package","*", "Package_Id", $this->pid);
        $this->year = $sql[0]['Season_Year'];
        $this->section = $sql[0]['Venue_Section'];
        $this->team = $sql[0]['Team'];
        $this->price = $sql[0]['Price'];
        $this->seatQty = $sql[0]['Qty'];
        $this->activeState = $sql[0]['Active'];
    }
    
    private $getTeam_Cache=array();
    public function getTeam($id){
	global $getTeam_Cache;
	
	if(!isset($getTeam_Cache[$id])){
		$sports = getTableSearch("team", "*", "Stubhub_Performer_Id", $id);
		$getTeam_Cache[$id]=$sports[0];
		return $sports[0];
	} else {
		return $getTeam_Cache[$id];
	}   
}
}