<?php
function search_array($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && search_array($needle, $element))
               return true;
     }
   return false;
}
/* draws a calendar */
function draw_calendar($data){
	 $return = "";
            $thisMonth = date('n');
			

         $data = json_decode($data, true);
		 //print_r($data);
         //echo "<pre>"; print_r($data);
		 //$data = $events[0]['games'];
         foreach($data as $startdate){
            $timestamp = strtotime($startdate['start']);
            $php_date = getdate($timestamp);
            //echo date("m", $timestamp);
            $date[] = array(date("m", $timestamp), date("Y", $timestamp));
         }
         $date = array_map("unserialize", array_unique(array_map("serialize", $date)));
         sort($date);
         //print_r($date);
         $months = array("01"=>"January", "02"=>"February", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July", "08"=>"August", "09"=>"September", "10"=>"October", "11"=>"November", "12"=>"December");
         foreach($date as $key=>$i){
            if(search_array($thisMonth, $date)){
               $scriptData[] = array("state"=>(($i[0] == $thisMonth) ? "enabled": "disable"), "id"=>($i[0]."-".$i[1]));
            } else {
               $scriptData[] = array("state"=>(($key ==0) ? "enabled": "disable"), "id"=>($i[0]."-".$i[1]));
            }
         }
         $return .= "<script>
                     var months = ".json_encode($scriptData,JSON_PRETTY_PRINT).";
               </script>";
         
		 
         foreach($date as $key=>$month){
            $prev = ($key == 0) ? "" : $months["0".($month[0]-1)];
            $next = ($key == (count($date)-1)) ? "" : $months[(($month[0]+1)>9) ? $month[0]+1 : "0".($month[0]+1)];
            
            

            if(search_array($thisMonth, $date)){
               $return .= ($month[0] == $thisMonth) ? "<div id='".$month[0]."-".$month[1]."' class='active'>" : "<div id='".$month[0]."-".$month[1]."' class='nonActive'>";
            } else {
               $return .= ($key == 0) ? "<div id='".$month[0]."-".$month[1]."' class='active'>" : "<div id='".$month[0]."-".$month[1]."' class='nonActive'>";
            }
            
            
            
            
            $return .= "<div class=\"col-sm-12\" style=\"text-align:center\">
                     <a  style='font-size:16px' onclick=\"prev()\">".$prev."</a>
                     <div style='font-size: 50px;display:inline; padding-left:10px; padding-right:10px'>".
                        $months[$month[0]]." ".$month[1].
                     "</div>
                     <a  style='font-size:16px' onclick=\"next()\">".$next."</a></div>";
            $return .= calendar($month[0], $month[1], $data);
            $return .= "</div>";
			
			
         }
         return $return;
         
         
         
}
function calendar($month, $year, $games){
	/* draw table */
        //echo $month;
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row-head"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
               $key = array_search($year."-".$month."-".sprintf("%02d", $list_day), array_column($games, 'start'));
               $color = ($key ? $games[$key]['color']: "");
			   $lid = ($key ? $games[$key]['lid']: "cell");
		$calendar.= '<td class="calendar-day" id="cell_'.$lid.'" style="background-color: '.$color.'; color:black; font-weight: bold; font-size: 15px;">';
			/* add in the day number */
			$calendar.= '<div  class="day-number" style="font-size: 12px; font-weight: normal">'.
                                    $list_day.
                                    '</div>';
                                    
                        if($key && (strpos($games[$key]['sold'], "PENDING") === false)){
                           $calendar.= ($games[$key]['sold'] != "SOLD") ? '<input type="checkbox" style="display:none" class="checkbox" name="'.$games[$key]['lid'].'" id="'.$games[$key]['lid'].'"/>' : "";
                           $calendar .= ($games[$key]['sold'] != "SOLD") ? '<label id="title_'.$games[$key]['lid'].'" for="'.$games[$key]['lid'].'">' : "<div class='sold'>";
                           $calendar .= $games[$key]['title'];
                           $calendar .= ($games[$key]['sold'] != "SOLD") ?  '</label>' : "</div>";
						   $calendar .= ($games[$key]['sold'] != "SOLD") ? "<span class='status' onclick=\"status('a','".$games[$key]['lid']."')\">Change Status</span>" : "";

                        } else if($key && (strpos($games[$key]['sold'], "PENDING") !== false)){
                           $calendar .= ($games[$key]['sold'] != "SOLD") ? '<span class="pending" id="title_'.$games[$key]['lid'].'" for="'.$games[$key]['lid'].'">' : "<div class='sold'>";
                           $calendar .= $games[$key]['title'];
                           $calendar .= ($games[$key]['sold'] != "SOLD") ?  '</span>' : "</div>";
						   $calendar .= '<div class="progress" style="width:60%; margin 5px; right:0px; position:relative">
											 <div class="progress-bar" style="width: 50%;"></div>
										   </div>';
						}

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}

