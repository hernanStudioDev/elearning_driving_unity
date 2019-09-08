<?php
include_once 'config.php';
include_once '../class.switchback.php';

/*
	query += "?e=" // Email;

	https://CLIENTURL/api/GetRegionRange.php?e=email
*/

if(isset($_GET['e']))
{
	$sb = new SWITCHBACK($DB_con);

	$email = $_GET['e'];

	// get user info
	$userInfo = $users->fetchUserInfoByEmail($email);

	// check if user exists, if not don't continue
	if ($userInfo["id"] == null) onErrorMessage("Invalid user.");

	// get region
	$region = $userInfo["region"];

	// get top 10 list
	$loadRegionRange = $sb->loadRegionRange($email, $region);

	if ($loadRegionRange["success"])
	{
		$response["data"] = $loadRegionRange["list"];

		if ($isDebug) highlight_string( var_export($response, true));
		else echo json_encode($response);
	}
	else // track type does not exist, create it
	{
		if ($isDebug) highlight_string( var_export($loadRegionRange, true));
		else onErrorMessage("Could not find index.");
	}
}
else
{
	onErrorMessage("Invalid entry.");
}

function onSuccessMessage($msg, $response)
{
	$response["success"] = true;
	$response["status"] = $msg;
	$serverResponse["data"] = $response;
	echo json_encode($serverResponse);
}

function onErrorMessage($msg)
{
	$response["success"] = false;
	$response["status"] = $msg;
	$serverResponse["data"] = $response;
	echo json_encode($serverResponse);
	exit;
}
?>
