
<html>
<head>
<title>Demo Create and Consume Simple REST API in PHP - AllPHPTricks.com</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div style="width:90%; margin:0 auto;">

<h3>Demo Consume Simple REST API in PHP</h3>   
<form action="" method="POST">
    <label>Enter Course ID:</label><br />
    <input type="text" name="course_id" placeholder="Enter Course ID" required/>
    <br />
    <button type="submit" name="submit">Submit</button>
</form>  








<?php

$myurl      = 'https://moodleapi.maiaxia.com/webservice/rest/server.php'    ;
$mytoken    = 'fb69b656d7be85b04c27fa4268e488ce'                            ;
$myfunction = 'core_course_get_courses'                                     ;
$myformat   = 'json';
// $myformat   = 'xml';


print_r("<br>*****1****************<br>"); 


    try {  
        $myrestapi  = new MoodleRest($myurl, $mytoken , 'json' )   ;
        $myresponse = $myrestapi->request($myfunction )         ;
        print_r("<br>MYRESPONSE1: $myresponse<br>"); 
    } 
    catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }
    

    
if ( !empty($_POST['course_id']) ) {
	$course_id = $_POST['course_id'];
	$myurl2 = "$myurl?wstoken=$mytoken&wsfunction=$myfunction&moodlewsrestformat=$myformat&options[ids][0]=".$_POST['course_id'];
    
    print_r("<br>URL: $myurl2<br>");
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept:application/json, Content-Type:application/json']);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, ‘GET’);
    
    try {
        
        //  Initiate curl
        $ch = curl_init();
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$myurl2);
        // Execute
        $result=curl_exec($ch);
        print_r("<br>RESULT: $result<br>");
        curl_close($ch);

        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $myurl2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        print_r("<br>DATA: $data<br>");
        curl_close($curl);
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $myurl2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        $result = json_decode($response);
        
        print_r("<br>RESPONSE: $response<br>");
        print_r("<br>RESULT: ".serialize($result)."<br>");
        print_r("<br>RESULT: ".serialize($result[0])."<br>");
        print_r("<br>RESULT: ".serialize($result[0]->shortname)."<br>");

        echo "<table>";
        // echo "<tr><td>Course ID </td> <td>($result[0])->id       </td> </tr>";
        echo "<tr><td>Short Name</td> <td>".$result[0]->shortname."</td> </tr>";
        // echo "<tr><td>Full Name </td> <td>$result[0]->fullname </td> </tr>";
        echo "</table>";        
    
        curl_close($curl);
    } 
    catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }
    

}
  

  ?>

<br />

</div>
</body>
</html>

<?php

    $token = 'fb69b656d7be85b04c27fa4268e488ce';
    $domainname = 'https://moodleapi.maiaxia.com';
    $functionname = 'core_course_get_courses';
    $restformat = 'json';

    $cursos = '{}';

    try {
        $cursos = file_get_contents($myurl2);
    } catch (Exception $e) {
        echo $e;
    }

    $rows = json_decode($cursos);

    echo "<pre>";
    print_r("<br>ROWS: ".serialize($cursos));
    print_r("<br>ROWS: ".serialize($rows));
    echo "</pre>";
?>


<?php
/**
 * MoodleRest
 *
 * MoodleRest is a class to query Moodle REST webservices
 *
 * @package    MoodleRest
 * @version    2.4.0
 * @author     Lawrence Lagerlof <llagerlof@gmail.com>
 * @copyright  2021 Lawrence Lagerlof
 * @link       http://github.com/llagerlof/MoodleRest
 * @license    https://opensource.org/licenses/MIT MIT
 */
class MoodleRest
{
    /**
     * The constant that defines the JSON return format
     * @access public
     */
    const RETURN_JSON = 'json';

    /**
     * The constant that defines the XML return format
     * @access public
     */
    const RETURN_XML = 'xml';

    /**
     * The constant that defines the ARRAY return format
     * @access public
     */
    const RETURN_ARRAY = 'array';

    /**
     * The constant that defines the request method using GET
     * @access public
     */
    const METHOD_GET = 'get';

    /**
     * The constant that defines the request method using POST
     * @access public
     */
    const METHOD_POST = 'post';

    /**
     * The full server address to Moodle REST webservices.
     * @access private
     */
    private $server_address;

    /**
     * The Moodle webservice token
     * @access private
     */
    private $token;

    /**
     * The return format (json, xml, array)
     * @access private
     */
    private $return_format = 'json'; // or xml

    /**
     * The RAW return data (as returned by the request. could be json or xml)
     * @access private
     */
    private $request_return;

    /**
     * The PARSED return data (could be json, xml or array)
     * @access private
     */
    private $parsed_return;

    /**
     * The full encoded URL used to access the webservice
     * @access private
     */
    private $url;

    /**
     * The full URL decoded
     * @access private
     */
    private $url_decoded;

    /**
     * The header string to be used in header() output
     * @access private
     */
    private $output_header;

    /**
     * The header string to be used in header() output
     * @access private
     */
    private $print_on_request = false;

    /**
     * The method to be used on request
     * @access private
     */
    private $method = 'get'; // or post

    /**
     * Print debug information to standard output?
     * @access private
     */
    private $debug = false;

    /**
     * Constructor
     *
     * @param string $server_address The full URL of Moodle rest server script. Eg: http://127.0.0.1/moodle/webservice/rest/server.php
     */
    public function __construct($server_address = null, $token = null, $return_format = self::RETURN_ARRAY)
    {
        $this->server_address = $server_address;
        $this->token = $token;
        if (!is_null($return_format) && $return_format <> 'json' && $return_format <> 'xml' && $return_format <> 'array') {
            throw new Exception("MoodleRest: Invalid return format: '$return_format'.");
        }
        $this->return_format = $return_format;
    }

    /**
     * Set the full server address to Moodle REST webservices
     *
     * @param string $server_address The server address. eg:
     *
     * @return MoodleRest
     */
    public function setServerAddress($server_address)
    {
        $this->server_address = $server_address;
        return $this;
    }

    /**
     * Get the server address to Moodle REST webservices
     *
     * @return string The server address
     */
    public function getServerAddress()
    {
        return $this->server_address;
    }

    /**
     * Set the Moodle token to access Moodle REST webservices
     *
     * @param string $token The oken generated by Moodle admin
     *
     * @return MoodleRest
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the Moodle token
     *
     * @return string The Moodle token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the return format (json, xml or array)
     *
     * @param string $return_format The return format (json, xml or array)
     *
     * @return MoodleRest
     */
    public function setReturnFormat($return_format) // json, xml, array
    {
        if ($return_format <> 'json' && $return_format <> 'xml' && $return_format <> 'array') {
            throw new Exception("MoodleRest: Invalid return format: '$return_format'.");
        }
        $this->return_format = $return_format;

        return $this;
    }

    /**
     * Get the return format
     *
     * @return string The return format (array, json or xml)
     */
    public function getReturnFormat()
    {
        return $this->return_format;
    }

    /**
     * Store the return data
     *
     * @param string $request_return The returned data made by request() method
     */
    private function setRawData($request_return)
    {
        $this->request_return = $request_return;
    }

    /**
     * Get the returned data previously made by request() method
     *
     * @return mixed
     */
    public function getRawData()
    {
        return $this->request_return;
    }

    /**
     * Store the parsed return data
     *
     * @param string $parsed_return The parsed returned data made by request() method
     */
    private function setData($parsed_return)
    {
        $this->parsed_return = $parsed_return;
    }

    /**
     * Get the parsed returned data previously made by request() method
     *
     * @return mixed The returned data in his final form
     */
    public function getData()
    {
        return $this->parsed_return;
    }

    /**
     * Store the full URL when querying the server
     *
     * @param string $url The parsed returned data made by request() method
     */
    private function setUrl($url)
    {
        $this->url = $url;
        $this->url_decoded = urldecode($url);
    }

    /**
     * Get the full URL stored when the query was made
     *
     * @return string The requested URL
     */
    public function getUrl($decoded = true)
    {
        return $decoded ? $this->url_decoded : $this->url;
    }

    /**
     * Store the output header
     *
     * @param string $output_header The header
     */
    private function setHeader($output_header)
    {
        $this->output_header = $output_header;
    }

    /**
     * Get the output header string
     *
     * @return string Get the output header
     */
    public function getHeader()
    {
        return $this->output_header;
    }

    /**
     * Set the request method
     *
     * @param string $method The method to be used on request: MoodleRest::METHOD_GET or METHOD_POST
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get the method
     *
     * @return string Get the request method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Enable debugging information
     *
     * @param bool $enabled Enable or disable debugging information
     */
    public function setDebug($enabled = true)
    {
        $this->debug = $enabled;
    }

    /**
     * Print debug information
     *
     * @param string $url The full url used to request the Moodle REST server
     * @param string $webservice_function The name of the Moodle function
     * @param string $method The method used to request data (get or post)
     * @param string $returned_data The data returned by Moodle webservice
     */
    private function debug($url, $webservice_function, $method, $returned_data)
    {
        if ($this->debug) {
            $line_break = php_sapi_name() == 'cli' ? "\n" : '<br />';
            $open_html_pre = php_sapi_name() == 'cli' ? '' : '<pre>';
            $close_html_pre = php_sapi_name() == 'cli' ? '' : '</pre>';
            echo $open_html_pre;
            echo $line_break;
            echo '[debug][' . strtoupper($method) . '] ' . get_class($this) . "::request( $webservice_function )$line_break";
            echo "$url $line_break";
            if (is_array($returned_data) || is_object($returned_data)) {
                print_r($returned_data);
            } else {
                if ((strlen(trim($returned_data)) > 0) && in_array($returned_data[0], array('[', '{'))) {
                    print_r(json_decode(trim($returned_data), true));
                } else {
                    echo gettype($returned_data) . " '$returned_data'$line_break";
                }
            }
            echo $line_break;
            echo $close_html_pre;
        }
    }

    /**
     * Set the option to print the requested data to standard output
     *
     * @param bool $print_on_request Set to TRUE if you want to output the result
     */
    public function setPrintOnRequest($print_on_request = true)
    {
        $this->print_on_request = $print_on_request;
    }

    /**
     * Check if the object is configured to print the result to standard output
     *
     * @return bool Print the returned data to the standard output?
     */
    public function getPrintOnRequest()
    {
        return $this->print_on_request;
    }

    /**
     * Output the result if the requested data format is json or xml, or print_r if is an array
     */
    public function printRequest()
    {
        if (($this->getReturnFormat() == 'json') || ($this->getReturnFormat() == 'xml')) {
            if (empty($this->output_header)) {
                if ($this->getReturnFormat() == 'json') {
                    header('Content-Type: application/json');
                } elseif ($this->getReturnFormat() == 'xml') {
                    header('Content-Type: application/xml');
                }
            }
            echo $this->getData();
        } else {
            print_r($this->getData());
        }
    }

    /**
     * Make the request
     *
     * @param string $function A Moodle function
     * @param array $parameters The parameters to be passed to the Moodle function. eg: array('groupids' => array(1,2)) | This translates as "groupids[0]=1&groupids[1]=2" in URL
     *
     * @return mixed The final requested data
     */
    public function request($function, $parameters = null, $method = self::METHOD_GET)
    {
        if (empty($this->server_address)) {
            throw new Exception('MoodleRest: Empty server address. Use setServerAddress() or put the address on constructor.');
        }
        if (empty($this->token)) {
            throw new Exception('MoodleRest: Empty token. Use setToken() or put the token on constructor.');
        }
        if (empty($this->return_format)) {
            throw new Exception('MoodleRest: Empty return format. Use setReturnFormat().');
        }
        if (empty($function)) {
            throw new Exception('MoodleRest: Empty function. Fill the first parameter of request().');
        }
        if (!is_null($parameters)) {
            if (!is_array($parameters)) {
                throw new Exception('MoodleRest: The second parameter of request() should be an array.');
            }
        }

        if ($this->getReturnFormat() == 'array' || $this->getReturnFormat() == 'json') {
            $return_format = 'json';
        } else {
            $return_format = 'xml';
        }

        $this->setMethod($method);

        $query_string = is_array($parameters) ? http_build_query($parameters) : '';

        $this->setUrl(
            $this->getServerAddress() .
            '?wstoken=' . $this->getToken() .
            '&moodlewsrestformat=' . $return_format .
            '&wsfunction=' . $function .
            '&' . $query_string
        );

        $post_url =
            $this->getServerAddress() .
            '?wstoken=' . $this->getToken() .
            '&moodlewsrestformat=' . $return_format .
            '&wsfunction=' . $function;
            
            
            print_r("<br>POST_URL1: $post_url<br>");

        if ($this->getMethod() != self::METHOD_POST) {
            
            print_r("POST_URL2: ".$this->getUrl(true)."<br>");
            print_r("POST_URL3: ".$this->getUrl(false)."<br>");
            // GET
            // $moodle_request = file_get_contents($this->getUrl(false));
            // $moodle_request = file_get_contents($post_url);
            
            $options = array('http' =>
                array(
                    'method'  => 'GET',
                    'header'  => 'Content-type: application/json',
                    'content' => $query_string
                )
            );
            $context = stream_context_create($options);
            // $moodle_request = file_get_contents($post_url, false, $context);
            $moodle_request = file_get_contents($post_url);

            if ($moodle_request === false) {
                throw new Exception('MoodleRest: Error trying to connect to Moodle server on GET request. Check PHP warning messages.');
            }

            $this->debug($this->getUrl(), $function, self::METHOD_GET, $moodle_request);
        } else {
            // POST
            $options = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $query_string
                )
            );
            $context = stream_context_create($options);
            $moodle_request = file_get_contents($post_url, false, $context);

            if ($moodle_request === false) {
                throw new Exception('MoodleRest: Error trying to connect to Moodle server on POST request. Check PHP warning messages.');
            }

            $this->debug($this->getUrl(), $function, self::METHOD_POST, $moodle_request);
        }

        $this->setRawData($moodle_request);

        if ($this->getReturnFormat() == 'array') {
            $this->setData(json_decode($moodle_request, true));
        } else {
            $this->setData($moodle_request);
        }

        if ($this->getPrintOnRequest()) {
            $this->printRequest();
        }

        return $this->getData();
    }
} 
?> 