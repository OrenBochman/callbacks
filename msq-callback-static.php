<?php /*Template Name: Static CallBack Page*/?>
<?php

    $debug=false;
    $log=true;
    //init vars
    $MobilePhone=$device= $lpurl=$gclid=$utm_term=$uid=$cid=$utm_content=$title=$utm_source=$utm_medium=$utm_campaign=$label="";
    settype ($MobilePhone, 'string' );
    $isDynamic=$isStatic=false;
    $missingData=empty($_GET);

    date_default_timezone_set('Asia/Jerusalem');
    ini_set("log_errors", 1);
    $logFileName = './'.basename(__FILE__, '.php').'.log';  
    ini_set("error_log", $logFileName);

if ($debug) {
    error_log("---------------------------- Get DATA ---------------------");   
    error_log(print_r($_GET, true));        
    error_log("---------------------------- End DATA ---------------------");       
}
    
if (!empty($_GET) ) {
    $isStatic = true;
}
        
/**
 * Post data using curl.
 * 
 * @param string $Url        url to post to.
 * @param string $strRequest request to make.
 * 
 * @return - the reponse.
 */
function httpsPost($Url, $strRequest)
{
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strRequest);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $Response = curl_exec($ch);
        curl_close($ch);

        return $Response;
}

if ($missingData) {
    if ($debug) { 
        error_log("no data");
    }
} else if ($isStatic) {

    if ($debug) {
        error_log("processing - static number");
    }

    if (isset($_GET['utm_campaign']) ) {
        $utm_campaign =$_GET['utm_campaign'];
    }
    if (isset($_GET['label']) ) {
        $label =$_GET['label'];
    }
    if (isset($_GET['gclid']) ) {
        $gclid =$_GET['gclid'];
    }
    if (isset($_GET['utm_medium']) ) {
        $utm_medium =$_GET['utm_medium'];
    }
    if (isset($_GET['utm_source']) ) {
        $utm_source =$_GET['utm_source'];
    }
    if (isset($_GET['utm_term']) ) {
        $utm_term =$_GET['utm_term'];
    }
    if (isset($_GET['utm_content']) ) {
        $utm_content =$_GET['utm_content'];
    }
    if (isset($_GET['gclid']) ) {
        $gclid =$_GET['gclid'];
    }
    if (isset($_GET['lpurl']) ) {
        $lpurl =$_GET['lpurl'];
    }

}

if (!$missingData) {
 
    if ($debug) {
        error_log("generating response");
    }

    $LeadWizeUri = urlencode('http://salesonlineinternalsite:4041/Car/Public/MokdanLandingPage/?guid=Ld_From_Atomi&ia=false&utm_source='. $utm_source.'&utm_medium='. $utm_medium.'&utm_campaign='. $utm_campaign.'&utm_content='.  $utm_content.'&utm_term='. $utm_term.'&lpurl='. $lpurl.'&label='.  $label.'&gclid='. $gclid.'&uid='. $uid.'&cid='. $cid.'&device='. $device.'&title='. $title);
    
    $url = 'https://leadswize.com/api/leadswize/PostCarInsuranceLead_He';
    
    $LeadTypeID = '37';
    $CampaignID = '418';
    $ProviderLeadPK = '0';
    $FirstName = 'שיחת';
    $LastName = 'טלפון';
    if (isset($_GET['CLI']) ) {
         $MobilePhone = $_GET['CLI'];
    } else {
        if ($debug) {
             error_log("CLI -  not set");
        }
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
      
    if ($log) {
        error_log('lw-response:'.$Response.'   lw-request:'.$strRequest);
    }
          
}

    $google_analytics_result = file_get_contents(
        'https://www.google-analytics.com/collect?v=1&tid=UA-1458255-7&'.
        'cid={$cid}&'.
        't=event&'.
        'ec=monitor&'.
        'ea='.'car-static'. //urlencode($LeadWizeUri) .
        '&el='. urlencode($Response) 
    );

 
?>
<head>
</head><body><BR>
<?php  echo  ($missingData ? "missing lead data" : "res: ".
  $Response.
  "<br> lw uri: ".
  $LeadWizeUri); ?>
<?php if(isset($_GET['debug']) ) : ?>
<br><h1>Simulate static call:</h1><br>
<form  method="get" action="/callback/msq-callback-static.php">
  CLI:<br>
  <input type="text" style="width:100%" name="CLI" value="0544320015">
  <input type="text" style="width:100%" name="gclid" value="gclid">
  <input type="text" style="width:100%" name="utm_source" value="test_medium">
  <input type="text" style="width:100%" name="utm_medium" value="test_medium">
  <input type="text" style="width:100%" name="utm_campaign" value="test_campaign">
  <input type="text" style="width:100%" name="utm_term" value="test_term">
  <input type="text" style="width:100%" name="utm_content" value="test_content">
  <input type="text" style="width:100%" name="lpurl" value="lpurl">
  <input type="text" style="width:100%" name="useragent" value="useragent">
  <input type="text" style="width:100%" name="label" value="label_test">
  <input type="submit" value="Submit">
</form>
<?php endif; ?>