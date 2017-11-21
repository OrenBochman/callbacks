<?php /*Template Name: CallBack Page*/?>

<?php
    $debug=false;
    $log=true;
    //init vars
    $MobilePhone=$device= $lpurl=$gclid=$utm_term=$uid=$cid=$utm_content=$title=$utm_source=$utm_medium=$utm_campaign=$label="";
    $isDynamic=$isStatic=false;
    $missingData=empty($_POST);

    date_default_timezone_set('Asia/Jerusalem');
    ini_set("log_errors", 1);
    $logFileName = './'.basename(__FILE__, '.php').'.log';  
    ini_set("error_log", $logFileName);

    if($debug) 
    {
            error_log("---------------------------- Post DATA ---------------------");   
            error_log(print_r($_POST, true));    
            error_log("--------------------  costum_client_var ---------------------");       
            error_log(print_r($_POST['costum_client_var'], true));            
            error_log("------------------------- Foreach DATA -------------------");
    }
    if ( isset($_POST['costum_client_var']) )
    {
        $isDynamic =true;
    }
    else if(!empty($_POST))
    {
        $isStatic =true;
    }
        
    /**
    * post data using curl
    */
    function httpsPost($Url, $strRequest)
    {
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strRequest);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $Response = curl_exec($ch);
        curl_close($ch);
        return $Response;
    }

if($missingData) {
  //  echo("missing data");
  if($debug) error_log("no post data");
}else if($isDynamic){
  if($debug) error_log("processing - dynamic number");

    //extract cid
    $cid=substr($_POST['key_google_analytics_data'], strrpos($_POST['key_google_analytics_data'], "=") + 1);
    if($debug) {
        error_log("cid:" . $cid);
        error_log("\nArray:costum_client_var\n");
    }
    //extract orther attribution data
    //if (gettype($_POST['costum_client_var'])=="array")
    foreach(json_decode($_POST['costum_client_var'],1) as $key => $value)
    {
        if(is_array($value)){
            if($debug) error_log("\nArray\n");
            foreach($value as $k => $v)
                if($debug){
                    error_log("\ndata[".$key."][".$k ."] = ". $v); 
                    error_log("\nEnd Array\n");
                }
        }         
        else {
            if($debug)
                error_log("\ndata[".$key ."] = ". $value);

            switch ($key) {
                case 'uid':
                    $uid = $value;
                    if($debug) error_log("uid:" . $uid);
                    break; 
                case 'utm_source':                
                    $utm_source = $value;
                    if($debug) error_log("utm_source:" . $utm_source);
                    break;
                case 'utm_medium':
                    $utm_medium = $value;
                    if($debug) error_log("utm_medium:" . $utm_medium);
                    break;
                case 'utm_campaign':
                    $utm_campaign = $value;
                    if($debug) error_log("utm_campaign:" . $utm_campaign);
                    break; 
                case 'label':
                    $label = $value;
                    if($debug) error_log("label:" . $label);
                    break;
                case 'utm_term':
                    $utm_term = $value;
                    if($debug) error_log("utm_term:" . $utm_term);
                    break;
                case 'utm_content':
                    $utm_content = $value;
                    if($debug) error_log("utm_content:" . $utm_content);
                    break;
                case 'gclid':
                    $gclid = $value;
                    if($debug) error_log("gclid:" . $gclid);
                    break;
                case 'lpurl':
                    $lpurl = $value;
                    if($debug) error_log("lpurl:" . $lpurl);
                    break;                                                                      
                case 'title':
                    $title = $value;
                    if($debug) error_log("title:" . $title);
                    break;
                case 'device':
                    $device = $value;
                    if($debug) error_log("device:" . $device);
                    break;                               
                default:
                   //if($debug) error_log("unused: data[".$key ."] = ". $value);
                    # code...
                    break;
            }
        }        
    }//foreach
}
if($debug){
        error_log("\n\n");
        error_log("---------------------------- End DATA ---------------------");        
}


if(!$missingData){
  //  echo("missing data");
   if($debug) error_log("generating response");

    $LeadWizeUri = urlencode('http://salesonlineinternalsite:4041/Car/Public/MokdanLandingPage/?guid=Ld_From_Atomi&ia=false&utm_source='. $utm_source.'&utm_medium='. $utm_medium.'&utm_campaign='. $utm_campaign.'&utm_content='.  $utm_content.'&utm_term='. $utm_term.'&lpurl='. $lpurl.'&label='.  $label.'&gclid='. $gclid.'&uid='. $uid.'&cid='. $cid.'&device='. $device.'&title='. $title);
    
    $url = 'https://leadswize.com/api/leadswize/PostCarInsuranceLead_He';
    
    $LeadTypeID = '37';
    $CampaignID = '418';
    $ProviderLeadPK = '0';
    $FirstName = 'שיחת';
    $LastName = 'טלפון';
    if ( isset($_POST['key_call_callerid']) ){
         $MobilePhone = $_POST['key_call_callerid'];
    }else{
         if($debug) error_log("key_call_callerid -  not set");
    }

      
    $Email = '';
    $strRequest = 'LeadTypeID='.urlencode($LeadTypeID).
                  '&CampaignID='.urlencode($CampaignID).
                  '&ProviderLeadPK='.urlencode($ProviderLeadPK).
                  '&FirstName='.urlencode($FirstName).
                  '&LastName='.urlencode($LastName).
                  '&MobilePhone='.urlencode($MobilePhone).
                  '&Email='.urlencode($Email).
                  '&utm_source='.urlencode($utm_source).
                  '&utm_medium='.urlencode($utm_medium).
                  '&utm_term='.urlencode($utm_term).
                  '&utm_content='.urlencode($utm_content).
                  '&utm_campaign='.urlencode($utm_campaign).
                  '&NotesFromLeadProvider='. $LeadWizeUri;
  
      $Response = httpsPost($url, $strRequest);
      if($log)
          error_log('lw-response:'.$Response.'   lw-request:'.$strRequest);

        $google_analytics_result = file_get_contents(
            'https://www.google-analytics.com/collect?v=1&tid=UA-1458255-7&'.
            'cid={$cid}&'.
            't=event&'.
            'ec=monitor&'.
            'ea='.'car-dynamic'. //urlencode($LeadWizeUri) .
            '&el='. urlencode($Response) 
        );    
    //$google_analytics_result = file_get_contents('https://www.google-analytics.com/collect?v=1&tid=UA-1458255-7&cid=11111.11111&t=event&ec=QA-Leadwise&ea='.urlencode($NotesFromLeadProvider).'&el='.$Response );
    //$google_analytics_result = file_get_contents('https://www.google-analytics.com/collect?v=1&tid=UA-1458255-7&cid={$cid}&t=event&ec=QA-Leadwise&ea=' .urlencode($LeadWizeUri) .'&el='.$Response );
  }
 
?>
<head>
    <!--script>alert("test");</script-->
</head><body><BR>
<?php  echo  ($missingData ? "missing lead data" : "res: " . $Response . "<br> lw uri: " . $LeadWizeUri); ?>
<?php if($missingData && isset($_GET['debug']) ): ?>
<br>Simulate dynamic call<br>
<form  method="post" action="/callback/msq-callback.php">

  key_call_callerid:<br>
  <input type="text" style="width:100%" name="key_call_callerid" value="0544440001">

  key_google_analytics_data:<br>
  <input type="text" style="width:100%" name="key_google_analytics_data" value="undefined|trackingId=UA-1458255-7|clientId=443812090.1490182578">
  <br>
  costum_client_var:<br>
  <input type="text" style="width:100%" name="costum_client_var" value='{"popup_url_page":"?maskyooPhone=&maskyooCli=732112215","callback_url":"https:\/\/fnx.atomi.co.il\/callback\/msq-callback.php","utm_source":"test_source","utm_medium":"test_medium","utm_campaign":"Car_12_1_GDN_Remarketing_LP_MS","label":"Display","device":"m","utm_term":"test_term","lpurl":"https:\/\/insurance.fnx.co.il\/car\/site-bitul\/","gclid":"CPHF8teCytQCFUy37Qod6u8KzA","page_title":"\u05d4\u05e4\u05e0\u05d9\u05e7\u05e1 - \u05d1\u05d9\u05d8\u05d5\u05d7 \u05e8\u05db\u05d1 - \u05d4\u05d7\u05dc \u05de 22%","key_google_analytics_data":"undefined|trackingId=UA-1458255-7|clientId=269757403.1497879158","popup_url_client_page1":"","popup_url_client_page2":"","popup_url_client_page3":"","popup_url_client_page4":""}'>
  <br><br>
  <input type="submit" value="Submit">
</form> 

<?php endif; ?>