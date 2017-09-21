<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../services/editservice.php

$checkSession = "true";
include_once '../includes/library.php';

if ($profilSession != "0") {
    phpCollab\Util::headerFunction('../general/permissiondenied.php');
}

//case update user
$id = $_GET['id'];

$action = $_GET['action'];

if ($id != "") {

//case update user
    if ($action == "update") {
        //replace quotes by html code in name and address
        $n = phpCollab\Util::convertData($_POST['n']);
        $np = phpCollab\Util::convertData($_POST['np']);
        $tmpquery = "UPDATE {$tableCollab["services"]} SET name=:name,name_print=:name_print,hourly_rate=:hourly_rate WHERE id = :service_id";
        $dbParams = [];
        $dbParams["name"] = $n;
        $dbParams["name_print"] = $np;
        $dbParams["hourly_rate"] = $_POST['hr'];
        $dbParams["service_id"] = $id;
        phpCollab\Util::newConnectSql($tmpquery, $dbParams);
        unset($dbParams);
        phpCollab\Util::headerFunction("../services/listservices.php?msg=update");
    }
    $tmpquery = "WHERE serv.id = '$id'";
    $detailService = new phpCollab\Request();
    $detailService->openServices($tmpquery);
    $comptDetailService = count($detailService->serv_id);

    //set values in form
    $n = $detailService->serv_name[0];
    $np = $detailService->serv_name_print[0];
    $hr = $detailService->serv_hourly_rate[0];
}

//case add user
if ($id == "") {
    if ($action == "add") {
        //replace quotes by html code in name and address
        $n = phpCollab\Util::convertData($_POST['n']);
        $np = phpCollab\Util::convertData($_POST['np']);

        $tmpquery1 = "INSERT INTO {$tableCollab["services"]} (name,name_print,hourly_rate) VALUES (:name,:name_print,:hourly_rate)";

        $dbParams = [];
        $dbParams["name"] = $n;
        $dbParams["name_print"] = $np;
        $dbParams["hourly_rate"] = $_POST['hr'];
        phpCollab\Util::newConnectSql($tmpquery1, $dbParams);
        unset($dbParams);
        phpCollab\Util::headerFunction("../services/listservices.php?msg=add");
    }
}

/* Titles */
if ($id == '') {
    $setTitle .= " : Add Service";
} else {
    $setTitle .= " : Edit Service (" . $detailService->serv_name[0] . ")";
}

$bodyCommand = "onLoad=\"document.serv_editForm.n.focus();\"";
include '../themes/' . THEME . '/header.php';

$blockPage = new phpCollab\Block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?", $strings["administration"], "in"));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../services/listservices.php?", $strings["service_management"], "in"));

if ($id == "") {
    $blockPage->itemBreadcrumbs($strings["add_service"]);
}
if ($id != "") {
    $blockPage->itemBreadcrumbs($blockPage->buildLink("../services/viewservice.php?id=$id", $detailService->serv_name[0], "in"));
    $blockPage->itemBreadcrumbs($strings["edit_service"]);
}
$blockPage->closeBreadcrumbs();

if ($msg != "") {
    include '../includes/messages.php';
    $blockPage->messageBox($msgLabel);
}

$block1 = new phpCollab\Block();

if ($id == "") {
    $block1->form = "serv_edit";
    $block1->openForm("../services/editservice.php?id=$id&action=add&#" . $block1->form . "Anchor");
}
if ($id != "") {
    $block1->form = "serv_edit";
    $block1->openForm("../services/editservice.php?id=$id&action=update&#" . $block1->form . "Anchor");
}

if ($error != "") {
    $block1->headingError($strings["errors"]);
    $block1->contentError($error);
}

if ($id == "") {
    $block1->heading($strings["add_service"]);
}
if ($id != "") {
    $block1->heading($strings["edit_service"] . " : " . $detailService->serv_name[0]);
}

$block1->openContent();

if ($id == "") {
    $block1->contentTitle($strings["details"]);
}
if ($id != "") {
    $block1->contentTitle($strings["details"]);
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">" . $strings["name"] . " :</td><td><input size=\"24\" style=\"width: 250px;\"type=\"text\" name=\"n\" value=\"$n\"></td>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">" . $strings["name_print"] . " :</td><td><input size=\"24\" style=\"width: 250px;\" type=\"text\" name=\"np\" value=\"$np\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">" . $strings["hourly_rate"] . " :</td><td><input size=\"24\" style=\"width: 250px;\" type=\"text\" name=\"hr\" value=\"$hr\"></td></tr>";

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"Save\" value=\"" . $strings["save"] . "\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

include '../themes/' . THEME . '/footer.php';
