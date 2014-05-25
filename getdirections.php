<?php
    require "Services/Twilio.php";
    //$body =
    $AccountSid = "AC9c9ffe78169960383ddcbd1fac9bbf3d";
    $AuthToken = "dd7f067bdb806e2a9a5f9c0e35f52046";
    $client = new Services_Twilio($AccountSid, $AuthToken);
    $from = '7076745678';
    $people = array("+19253886379" => "Vansh", "+19259807321" => "Carl");
    $start = '7228 Moss Tree Way, Pleasanton, CA 94566';
    $finish = '773 Summit Creek Lane, Pleasanton, CA 94566';
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
        $result .= $x++ . '. ' . $iten->Instruction . "<br>";

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>

<Response>
    <Message><?php echo $result ?></Message>
</Response>