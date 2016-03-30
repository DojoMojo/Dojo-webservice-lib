<html><head><title>CRUD Tutorial - Retrieve example</title></head><body>
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

// First : We always get the customer's list or a specific one
try
{
	$webService = new DojoWebservice(DOJO_SITE_PATH, DOJO_WS_AUTH_KEY, DOJO_WS_AUTH_PASS, DEBUG);
	// Here we set the option array for the Webservice : we want customers resources
	$opt['resource'] = 'customers';
	// We set an id if we want to retrieve infos from a customer
	if (isset($_GET['id']))
		$opt['id'] = (int)$_GET['id']; // cast string => int for security measures

	// Call
	$xml = $webService->get($opt);

	// Here we get the elements from children of customer markup which is children of prestashop root markup
	$resources = $xml->children()->children();
}
catch (PrestaShopWebserviceException $e)
{
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error<br />'.$e->getMessage();
}

// We set the Title
echo '<h1>Customers ';
if (isset($_GET['id']))
	echo 'Details';
else
	echo 'List';
echo '</h1>';

// We set a link to go back to list if we are in customer's details
if (isset($_GET['id']))
	echo '<a href="?">Return to the list</a>';

echo '<table border="5">';
// if $resources is set we can lists element in it otherwise do nothing cause there's an error
if (isset($resources))
{
	if (!isset($_GET['id']))
	{
		echo '<tr><th>Id</th><th>More</th></tr>';
		foreach ($resources as $resource)
		{
			// Iterates on the found IDs
			echo '<tr><td>'.$resource->attributes().'</td><td>'.
			'<a href="?id='.$resource->attributes().'">Retrieve</a>'.
			'</td></tr>';
		}
	}
	else
	{
		foreach ($resources as $key => $resource)
		{
			// Iterates on customer's properties
			echo '<tr>';
			echo '<th>'.$key.'</th><td>'.$resource.'</td>';
			echo '</tr>';
		}
	}
}
echo '</table>';
?>
</body></html>
