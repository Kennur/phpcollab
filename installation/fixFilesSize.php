<?php
#Application name: PhpCollab
#Status page: 0
#_fixFilesSize.php

$checkSession = "false";
include_once '../includes/library.php';

echo "Script to remove file size labels in database: <a href='$PHP_SELF?action=update'>launch</a><br/><br/>";

if ($action == "update") {
    $tmpquery = "";
    $listFiles = new phpCollab\Request();
    $listFiles->openFiles($tmpquery);
    $comptListFiles = count($listFiles->fil_id);

    if ($comptListFiles != "0") {
        for ($i = 0; $i < $comptListFiles; $i++) {
            $sizeNew = $listFiles->fil_size[$i];
            $sizeNew = str_replace('o', '', $sizeNew);
            $sizeNew = str_replace('O', '', $sizeNew);
            $sizeNew = str_replace('b', '', $sizeNew);
            $sizeNew = str_replace('B', '', $sizeNew);
            $sizeNew = str_replace('K', '', $sizeNew);
            $sizeNew = str_replace('M', '', $sizeNew);
            $sizeNew = str_replace('G', '', $sizeNew);

            $tmpquery = "UPDATE {$tableCollab["files"]} SET size=:size_new'$sizeNew' WHERE id=:file_id";
            phpCollab\Util::newConnectSql($tmpquery, ["size_new"=>$sizeNew, "file_id"=>$listFiles->fil_id[$i]]);
        }
        echo "$comptListFiles fixed :o)";
    } else {
        echo "no results";
    }
}
