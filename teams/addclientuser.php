<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../teams/addclientuser.php

$checkSession = "true";
include_once '../includes/library.php';

$members = new \phpCollab\Members\Members();
$projects = new \phpCollab\Projects\Projects();

$project = $_GET["project"];

$projectDetail = $projects->getProjectById($project);

if (!$projectDetail) {
    phpCollab\Util::headerFunction("../projects/listprojects.php?msg=blank");
}

if ($_GET["action"] == "add") {
    if ($id != "") {
        $pieces = explode("**", $id);
        $id = str_replace("**", ",", $id);

        if ($htaccessAuth == "true") {
            $Htpasswd = new Htpasswd;
            $Htpasswd->initialize("../files/" . $projectDetail["pro_id"] . "/.htpasswd");

            $tmpquery = "WHERE mem.id IN($id)";
            $listMembers = new phpCollab\Request();
            $listMembers->openMembers($tmpquery);
            $comptListMembers = count($listMembers->mem_id);

            for ($i = 0; $i < $comptListMembers; $i++) {
                $Htpasswd->addUser($listMembers->mem_login[$i], $listMembers->mem_password[$i]);
            }
        }
//if mantis bug tracker enabled	
        if ($enableMantis == "true") {
            //  include mantis library
            include '../mantis/core_API.php';
        }
        $comptTeam = count($pieces);
        for ($i = 0; $i < $comptTeam; $i++) {
            phpCollab\Util::newConnectSql(
                "INSERT INTO {$tableCollab["teams"]} (project, member, published, authorized) VALUES (:project, :member,'1','0')",
                ["project" => $projectDetail["pro_id"], "member" => $pieces[$i]]
            );
//if mantis bug tracker enabled		
            if ($enableMantis == "true") {
                // Assign user to this project in mantis
                $f_access_level = $client_user_level; // Reporter access
                $f_project_id = $projectDetail["pro_id"];
                $f_user_id = $pieces[$i];
                include '../mantis/user_proj_add.php';
            }

        }

        if ($notifications == "true") {
            $organization = "";
            include '../teams/noti_addprojectteam.php';
        }
        phpCollab\Util::headerFunction("../projects/viewprojectsite.php?id=" . $projectDetail["pro_id"] . "&msg=addClientToSite");
    }
}

include '../themes/' . THEME . '/header.php';

$blockPage = new phpCollab\Block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?", $strings["projects"], "in"));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=" . $projectDetail["pro_id"], $projectDetail["pro_name"], "in"));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewprojectsite.php?id=" . $projectDetail["pro_id"], $strings["project_site"], "in"));
$blockPage->itemBreadcrumbs($strings["grant_client"]);
$blockPage->closeBreadcrumbs();

$block1 = new phpCollab\Block();

$block1->form = "atpt";
$block1->openForm("../teams/addclientuser.php?project=$project#" . $block1->form . "Anchor");

$block1->heading($strings["add_team"]);

$block1->openPaletteIcon();
$block1->paletteIcon(0, "add", $strings["add"]);
$block1->paletteIcon(1, "info", $strings["view"]);
$block1->paletteIcon(2, "edit", $strings["edit"]);
$block1->closePaletteIcon();

$block1->sorting("team", $sortingUser->sor_users[0], "mem.name ASC", $sortingFields = array(0 => "mem.name", 1 => "mem.title", 2 => "mem.login", 3 => "mem.phone_work", 4 => "log.connected"));

$tmpquery = "WHERE tea.project = '$project' AND mem.profil = '3'";
$concatMembers = new phpCollab\Request();
$concatMembers->openTeams($tmpquery);
$comptConcatMembers = count($concatMembers->tea_id);
$membersTeam = null;
if ($comptConcatMembers != "0") {
    for ($i = 0; $i < $comptConcatMembers; $i++) {
        $membersTeam .= $concatMembers->tea_mem_id[$i];
        if ($i < $comptConcatMembers - 1) {
            $membersTeam .= ",";

        }
    }
}

$comptListMembers = count($listMembers["mem_id"]);
$listMembers = $members->getClientMembersByOrgIdAndNotInTeam($projectDetail["pro_organization"], $membersTeam, $block1->sortingValue);

if ($listMembers) {
    $block1->openResults();

    $block1->labels($labels = array(0 => $strings["full_name"], 1 => $strings["title"], 2 => $strings["user_name"], 3 => $strings["work_phone"], 4 => $strings["connected"]), "false");

//    for ($i = 0; $i < $comptListMembers; $i++) {
    foreach ($listMembers as $member) {
        if ($member["mem_phone_work"] == "") {
            $member["mem_phone_work"] = $strings["none"];
        }
        $block1->openRow();
        $block1->checkboxRow($member["mem_id"]);
        $block1->cellRow($blockPage->buildLink("../users/viewuser.php?id=" . $member["mem_id"], $member["mem_name"], "in"));
        $block1->cellRow($member["mem_title"]);
        $block1->cellRow($blockPage->buildLink($member["mem_email_work"], $member["mem_login"], "mail"));
        $block1->cellRow($member["mem_phone_work"]);
        if ($member["mem_profil"] == "3") {
            $z = "(Client on project site)";
        } else {
            $z = "";
        }
        if ($member["mem_log_connected"] > $dateunix - 5 * 60) {
            $block1->cellRow($strings["yes"] . " " . $z);
        } else {
            $block1->cellRow($strings["no"]);
        }
        $block1->closeRow();
    }

    $block1->closeResults();
} else {
    $block1->noresults();
}
$block1->closeFormResults();

$block1->openPaletteScript();
$block1->paletteScript(0, "add", "../teams/addclientuser.php?project=$project&action=add", "false,true,true", $strings["add"]);
$block1->paletteScript(1, "info", "../users/viewuser.php?", "false,true,false", $strings["view"]);
$block1->paletteScript(2, "edit", "../users/updateclientuser.php?organization=" . $projectDetail["pro_organization"] . "", "false,true,false", $strings["edit"]);
$block1->closePaletteScript($comptListMembers, $listMembers["mem_id"]);

include APP_ROOT . '/themes/' . THEME . '/footer.php';
