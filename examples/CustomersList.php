<html><head><title>CRUD Tutorial - Customer's list</title></head><body>
<?php
/*
* 2015 Dojo
*
*  @author Dojo BV <info@dojo.nl>
*  @copyright  2015 Dojo BV
*  International Registered Trademark & Property of Dojo
*
* Dojo Webservice Library
* @package DojoWebservice
*/

// Here we define constants /!\ You need to replace this parameters
define('DEBUG', true);
define('DOJO_SITE_PATH', 'http://dojo.business/');
define('DOJO_WS_AUTH_KEY', 'BATTLEPLANFORYOURBUSINESS');
define('DOJO_WS_AUTH_PASS', 'DOJOMOJO');
require_once('./DojoWebService.php');

// Here we use the WebService to get the schema of "customers" resource
try
{
	$webService = new DojoWebservice(DOJO_SITE_PATH, DOJO_WS_AUTH_KEY, DOJO_WS_AUTH_PASS, DEBUG);
	
	// Here we set the option array for the Webservice : we want customers resources
	$opt['resource'] = 'customers';
	
	// Call
	$xml = $webService->get($opt);

	// Here we get the elements from children of customers markup "customer"
	$resources = $xml->customers->children();
}
catch (DojoWebserviceException $e)
{
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error';
}

// We set the Title
echo "<h1>Customer's List</h1>";

echo '<table border="5">';
// if $resources is set we can lists element in it otherwise do nothing cause there's an error
if (isset($resources))
{
		echo '<tr><th>Id</th></tr>';
		foreach ($resources as $resource)
		{
			// Iterates on the found IDs
			echo '<tr><td>'.$resource->attributes().'</td></tr>';
		}
}
echo '</table>';
?>
</body></html>
