<?php
    require "Services/Twilio.php";
    $to = $_POST['From'];
    $AccountSid = "AC9c9ffe78169960383ddcbd1fac9bbf3d";
    $AuthToken = "dd7f067bdb806e2a9a5f9c0e35f52046";
    $client = new Services_Twilio($AccountSid, $AuthToken);
    $from = '7076745678';
    $people = array("+19253886379" => "Vansh", "+19259807321" => "Carl");
    $fulltext = $_POST['Body'];
    $fulltextarray = explode('|', $fulltext, 2);
    $start = $fulltextarray[0];
    $finish = $fulltextarray[1];
    $start = urlencode($start);
    $finish = urlencode($finish);
    $url = 'http://dev.virtualearth.net/REST/V1/Routes/Driving?o=xml&wp.0=' . $start . '&wp.1=' . $finish . '&avoid=minimizeTolls&key=AnuSkwrW2ovkGB807r9EjfSnyN7IDeMDCIbpPJiRZxoLhe4Bb5L8_Dbq7PU-VIKb';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    if ($curl_response === false)
    {
        echo "well fuck i guess it didnn't work";
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('error occured during curl exec. Additional info: ' . var_export($info));
    }
    curl_close($curl);
    $Response = new SimpleXMLElement($curl_response);
    $result = "";
    $x = 1;
    foreach($Response -> ResourceSets -> ResourceSet -> Resources -> Route -> RouteLeg -> ItineraryItem as $iten)
        $result .= $x++ . '. ' . $iten->Instruction . "\r\n";


    $array = str_split($result, 160);
    $arrlen = count($array, COUNT_NORMAL);
    foreach($array as $string)
    {
        $client->account->sms_messages->create($from, $to, $string);
        sleep(9);
    }
?>
