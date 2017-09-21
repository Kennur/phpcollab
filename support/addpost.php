<?php
#Application name: PhpCollab
#Status page: 0

$checkSession = "true";
include_once '../includes/library.php';

$id = $_GET["id"];
$action = $_GET["action"];
$idSession = $_SESSION["idSession"];

if ($enableHelpSupport != "true") {
    phpCollab\Util::headerFunction('../general/permissiondenied.php');
}

if ($supportType == "admin") {
    if ($profilSession != "0") {
        phpCollab\Util::headerFunction('../general/permissiondenied.php');
    }
}

$tmpquery = "WHERE sr.id = '$id'";
$requestDetail = new phpCollab\Request();
$requestDetail->openSupportRequests($tmpquery);

if ($action == "edit") {
    $dbParams = [];
    $dbParams["status"] = $_POST["sta"];
    $dbParams["date_close"] = ($_POST["sta"] == 2) ? $dateheure : '--' ;
    $dbParams["support_request_id"] = $id;
    phpCollab\Util::newConnectSql("UPDATE {$tableCollab["support_requests"]} SET status=:status,date_close=:date_close WHERE id = :support_request_id", $dbParams);
    unset($dbParams);

    if ($notifications == "true") {
        if ($requestDetail->sr_status[0] != $_POST["sta"]) {
            $num = $id;
            include '../support/noti_statusrequestchange.php';
        }
    }

    phpCollab\Util::headerFunction("../support/viewrequest.php?id=$id");
}

if ($action == "add") {
    $num = phpCollab\Util::newConnectSql(
        "INSERT INTO {$tableCollab["support_posts"]} (request_id,message,date,owner,project) VALUES(:request_id, :message, :date, :owner, :project)",
        ["request_id" => $id, "message" => phpCollab\Util::convertData($mes), "date" => $dateheure, "owner" => $idSession, "project" => $requestDetail->sr_project[0]]
    );

    if ($notifications == "true") {
        if ($mes != "") {
            include '../support/noti_newpost.php';
        }
    }

    phpCollab\Util::headerFunction("../support/viewrequest.php?id=$id");
}


include '../themes/' . THEME . '/header.php';

$blockPage = new phpCollab\Block();
$blockPage->openBreadcrumbs();

if ($supportType == "team") {
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?", $strings["projects"], "in"));
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=" . $requestDetail->sr_project[0], $requestDetail->sr_pro_name[0], "in"));
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../support/listrequests.php?id=" . $requestDetail->sr_project[0], $strings["support_requests"], "in"));
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../support/viewrequest.php?id=" . $requestDetail->sr_id[0], $requestDetail->sr_subject[0], "in"));
    if ($action == "status") {
        $blockPage->itemBreadcrumbs($strings["edit_status"]);
    } else {
        $blockPage->itemBreadcrumbs($strings["add_support_response"]);
    }
} elseif ($supportType == "admin") {
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?", $strings["administration"], "in"));
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/support.php?", $strings["support_management"], "in"));
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../support/listrequests.php?id=" . $requestDetail->sr_project[0], $strings["support_requests"], "in"));
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../support/viewrequest.php?id=" . $requestDetail->sr_id[0], $requestDetail->sr_subject[0], "in"));
    if ($action == "status") {
        $blockPage->itemBreadcrumbs($strings["edit_status"]);
    } else {
        $blockPage->itemBreadcrumbs($strings["add_support_response"]);
    }
}
$blockPage->closeBreadcrumbs();

if ($msg != "") {
    include '../includes/messages.php';
    $blockPage->messageBox($msgLabel);
}


$block2 = new phpCollab\Block();

$block2->form = "sr";
if ($action == "status") {
    $block2->openForm("../support/addpost.php?action=edit&id=$id&#" . $block2->form . "Anchor");
} else {
    $block2->openForm("../support/addpost.php?action=add&id=$id&#" . $block2->form . "Anchor");
}
if ($error != "") {
    $block2->headingError($strings["errors"]);
    $block2->contentError($error);
}

$block2->heading($strings["add_support_respose"]);

$block2->openContent();
$block2->contentTitle($strings["details"]);
if ($action == "status") {
    echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">" . $strings["status"] . " :</td><td><select name=\"sta\">";

    $comptSta = count($requestStatus);
    for ($i = 0; $i < $comptSta; $i++) {
        if ($requestDetail->sr_status[0] == $i) {
            echo "<option value=\"$i\" selected>$requestStatus[$i]</option>";
        } else {
            echo "<option value=\"$i\">$requestStatus[$i]</option>";
        }
    }
    echo "</select></td></tr>";
} else {
    echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">" . $strings["message"] . "</td><td><textarea rows=\"3\" style=\"width: 400px; height: 200px;\" name=\"mes\" cols=\"43\">$mes</textarea></td></tr>
<input type=\"hidden\" name=\"user\" value=\"$idSession\">";
}
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"" . $strings["submit"] . "\"></td></tr>";

$block2->closeContent();

include '../themes/' . THEME . '/footer.php';
