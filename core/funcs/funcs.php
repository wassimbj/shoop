<?php
function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

/* ================== Error messages function =============== */
    // ----> Error message
function error_msg($msg){
      if( isset($msg) ){
         $err  = "<div class='main'>
                   <div id='DIV_1' class='warning'>
                      <div id='DIV_2'>
                        <img src='https://cdn4.iconfinder.com/data/icons/materia-flat-arrows-symbols-vol-8/24/018_385_alert_error_warning-128.png' id='IMG_3'>
                      </div>
                        <div id='DIV_4'>
                         {$msg}
                        </div>
                      </div>
                   </div>";
          echo $err;
      }
}  

// ----> Success message
function success_msg($msg){
      if( isset($msg) ){
         $success  = "<div class='main'>
                         <div id='DIV_1' class='success'>
                         <div id='DIV_2'>
                           <img src='https://cdn2.iconfinder.com/data/icons/greenline/512/check-512.png' style='border-radius: 50%' id='IMG_3'/>
                         </div>
                         <div id='DIV_4'>
                          {$msg}
                         </div>
                        </div>
                    </div>";
         
          echo $success;
      }
     
}
 


// ----> Info message
function info_msg($msg){
      if( isset($msg) ){
         $info  = "<div class='main'>
                       <div id='DIV_1' class='info'>
                       <div id='DIV_2'>
                         <img src='https://www.journeycheck.com/resources/common/wwwv3/i/Info.png' id='IMG_3'/>
                       </div>
                       <div id='DIV_4'>
                        {$msg}
                       </div>
                      </div>
                  </div>";
         
          echo $info;
      }
}
 
/* ================= time ago function ================= */
 ///////////////////// simple and easy huh /////////////////////
function time_ago($date){
    
     $time_ago = strtotime($date);
     $current_time  = time();
     $diff = $current_time - $time_ago;
     
            global $sec;     
            global $min;
            global $hours;
            global $day;   
            global $week; 
       $sec    = $diff;
       $min    = round($diff / 60); // 1min = 60 seconds 
       $hours  = round($diff / 3600); // 3600 = 60min * 60min 
       $day    = round($diff / 86400); // 86400 = 24h * 60min * 60min
       $week   = round($diff / 604800); // 604800 = 7d * 24h * 60min * 60min
       $month  = round($diff / 2629440); // months
       $year   = round($diff / 31553280); // years
       // secondes
       if($sec <= 60){ 
          if($sec <= 5){
            return 'Just now';
          }else{
              return $sec.'s ago';
          }
       }elseif($min <= 60){
           return $min .'min ago';
      }elseif($hours <= 24){
            return $hours.'hrs ago';
         }elseif($day <= 7){
            return $day.'d ago';
         }elseif($week <= 4.3){
            return $week.'w ago';
         }elseif($month <=  12){
            if($month == 1)
              return '1 month ago';
            else
              return $month.' Months ago';
         }else{
            return $year.' Year ago';
         }
         
}//end of the time ago function


// ################ Shorter string ###############
function short_text($text, $end){
   $da_text = trim($text);
   if(strlen($da_text) > $end){
     return substr($da_text, 0, $end).'...';
   }else{
    return $text;
   }
}