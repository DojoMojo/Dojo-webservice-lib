<html><head><title>CRUD Tutorial - Create example</title></head><body>
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
	$opt = array('resource' => 'customers');
	if (isset($_GET['Create']))
		$xml = $webService->get(array('url' => DOJO_SITE_PATH.'/api/customers?schema=blank'));
	else
		$xml = $webService->get($opt);
	$resources = $xml->children()->children();
}
catch (DojoWebserviceException $e)
{
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error<br />'.$e->getMessage();
}

if (count($_POST) > 0)
{
// Here we have XML before update, lets update XML
	foreach ($resources as $nodeKey => $node)
	{
		$resources->$nodeKey = $_POST[$nodeKey];
	}
	try
	{
		$opt = array('resource' => 'customers');
		if ($_GET['Create'] == 'Creating')
		{
			$opt['postXml'] = $xml->asXML();
			$xml = $webService->add($opt);
			echo "Successfully added.";
		}
	}
	catch (DojoWebserviceException $ex)
	{
		// Here we are dealing with errors
		$trace = $ex->getTrace();
		if ($trace[0]['args'][0] == 404) echo 'Bad ID';
		else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
		else echo 'Other error<br />'.$ex->getMessage();
	}
}

// We set the Title
echo '<h1>Customer\'s ';
if (isset($_GET['Create'])) echo 'Creation';
else echo 'List';
echo '</h1>';

// We set a link to go back to list if we are in creation
if (isset($_GET['Create']))
	echo '<a href="?">Return to the list</a>';

if (!isset($_GET['Create']))
	echo '<input type="button" onClick="document.location.href=\'?Create\'" value="Create">';
else
	echo '<form method="POST" action="?Create=Creating">';

echo '<table border="5">';
if (isset($resources))
{

echo '<tr>';
if (count($_GET) == 0)
{
	echo '<th>Id</th></tr>';

	foreach ($resources as $resource)
	{
		echo '<tr><td>'.$resource->attributes().'</td></tr>';
	}
}
else
{
	echo '</tr>';
	foreach ($resources as $key => $resource)
	{
		echo '<tr><th>'.$key.'</th><td>';
		if (isset($_GET['Create']))
			echo '<input type="text" name="'.$key.'" value=""/>';
		echo '</td></tr>';
	}
}

}
echo '</table><br/>';

if (isset($_GET['Create']))
	echo '<input type="submit" value="Create"></form>';


?>
</body></html>
