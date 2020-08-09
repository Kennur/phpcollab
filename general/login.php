<?php
/*
** Application name: phpCollab
** Last Edit page: 15/01/2005
** Path by root: ../general/login.php
** Authors: Ceam / Fullo
**
** =============================================================================
**
**               phpCollab - Project Managment
**
** -----------------------------------------------------------------------------
** Please refer to license, copyright, and credits in README.TXT
**
** -----------------------------------------------------------------------------
** FILE: login.php
**
** DESC: Screen: login page
**
** -----------------------------------------------------------------------------
** TO-DO:
** move to a better login system and authentication (try to db session)
**
** =============================================================================
*/


use phpCollab\Logs\Logs;
use phpCollab\Members\Members;

$checkSession = "false";
include '../includes/library.php';

$members = new Members($logger);
$logs = new Logs();

$strings = $GLOBALS["strings"];
$idSession = (isset($_SESSION["idSession"]) && !empty($_SESSION["idSession"])) ? $_SESSION["idSession"] : null;
$loginMethod = $GLOBALS["loginMethod"];

if ($logout == "true") {
    $logs->setConnectedByLogin($loginSession, false);

    $logger->info('User logged out', ['username' => $_SESSION["loginSession"]]);

    // delete the authentication cookies
    setcookie('loginCookie', '', time()-86400);
    setcookie('passwordCookie', '', time()-86400);

    session_unset();
    session_destroy();


    phpCollab\Util::headerFunction("../general/login.php?msg=logout");
}

$auth = $request->query->get("auth");
$usernameForm = $request->request->get("usernameForm");
$passwordForm = $request->request->get("passwordForm");

$match = false;
$ssl = false;

if (!empty($SSL_CLIENT_CERT) && !$logout && $auth != "test") {
    $auth = "on";
    $ssl = true;

    if (function_exists("openssl_x509_read")) {
        $x509 = openssl_x509_read($SSL_CLIENT_CERT);
        $cert_array = openssl_x509_parse($x509, true);
        $subject_array = $cert_array["subject"];
        $ssl_email = $subject_array["Email"];
        openssl_x509_free($x509);
    } else {
        $ssl_email = `echo "$SSL_CLIENT_CERT" | $pathToOpenssl x509 -noout -email`;
    }
} else {
    //test blank fields in form
    if ($auth == "test") {
        if ($usernameForm == "" && $passwordForm == "") {
            $error = $strings["login_username"] . "<br/>" . $strings["login_password"];
        } else {
            if ($usernameForm == "") {
                $error = $strings["login_username"];
            } else {
                if ($passwordForm == "") {
                    $error = $strings["login_password"];
                } else {
                    $auth = "on";

                    if (isset($rememberForm) && $rememberForm == "on") {
                        $oneyear = 22896000;
                        $storePwd = phpCollab\Util::getPassword($passwordForm);
                        setcookie("loginCookie", $usernameForm, time() + $oneyear, null, null, null, true);
                        setcookie("passwordCookie", $storePwd, time() + $oneyear, null, null, null, true);
                    } else {
                        setcookie("loginCookie", null, null, null, null, null, true);
                        setcookie("passwordCookie", null, null, null, null, null, true);
                    }
                }
            }
        }
    }

    if ($forcedLogin == "false") {
        if ($auth == "on" && !$usernameForm && !$passwordForm) {
            $auth = "off";
            $error = "Detecting variables poisoning ;-)";
        }
    }
}

if ($auth == "on") {
    $usernameForm = strip_tags($usernameForm);
    $passwordForm = strip_tags($passwordForm);

    if (!empty($loginCookie) && !empty($passwordCookie)) {
        $usernameForm = $loginCookie;
    }

    $loginData = [];
    $loginData['login'] = $usernameForm;
    $loginData['demo'] = $demoMode;
    $loginData['ssl'] = $ssl;
    $loginData['ssl_email'] = $ssl_email;

    $member = $members->getMemberByLogin($loginData);


    //test if user exits
    if (!$member) {
        $logger->notice('Member not found', ['username' => $usernameForm]);
        $error = $strings["invalid_login"];
        setcookie("loginCookie", null, null, null, null, null, true);
        setcookie("passwordCookie", null, null, null, null, null, true);
    } else {

        //test password
        if (!empty($loginCookie) && !empty($passwordCookie)) {
            if (!$ssl && $passwordCookie != $member['mem_password']) {
                $logger->notice('Invalid password', ['username' => $usernameForm]);
                $error = $strings["invalid_login"];
            } else {
                $match = true;
            }
        } else {
            if (!$ssl && !phpCollab\Util::doesPasswordMatch($usernameForm, $passwordForm, $member['mem_password'], $loginMethod)) {
                $logger->notice('Invalid password', ['username' => $usernameForm]);
                $error = $strings["invalid_login"];
            } else {
                $match = true;
            }
        }

        if ($match === true) {

            //crypt password in session
            $r = substr($passwordForm, 0, 2);
            $passwordForm = crypt($passwordForm, $r);

            //set session variables
            $browserSession = $request->server->get("HTTP_USER_AGENT");
            $idSession = $member['mem_id'];
            $timezoneSession = $member['mem_timezone'];
            $languageSession = $request->request->get('languageForm');
            $loginSession = $usernameForm;
            $passwordSession = $passwordForm;
            $nameSession = $member['mem_name'];
            $profilSession = $member['mem_profil'];
            $ipSession = $request->server->get("REMOTE_ADDR");
            $dateunixSession = date("U");
            $dateSession = date("d-m-Y H:i:s");
            $logouttimeSession = $member['mem_logout_time'];


            $_SESSION["browserSession"] = $browserSession;
            $_SESSION["idSession"] = $idSession;
            $_SESSION["timezoneSession"] = $timezoneSession;
            $_SESSION["languageSession"] = $languageSession;
            $_SESSION["loginSession"] = $loginSession;
            $_SESSION["passwordSession"] = $passwordSession;
            $_SESSION["nameSession"] = $nameSession;
            $_SESSION["profilSession"] = $profilSession;
            $_SESSION["ipSession"] = $ipSession;
            $_SESSION["dateunixSession"] = $dateunixSession;
            $_SESSION["dateSession"] = $dateSession;
            $_SESSION["logouttimeSession"] = $logouttimeSession;

            //register demosession = true in session if user = demo
            if ($usernameForm == "demo") {
                $demoSession = "true";

                $_SESSION['demoSession'] = $demoSession;
            }

            //insert into or update log
            $ip = $request->server->get("REMOTE_ADDR");

            $logEntry = $logs->getLogByLogin($usernameForm);

            $session = session_id();
            error_log("set session to " . $session, 0);
            /**
             * Validate form data
             */

            $filteredData =  [];
            $filteredData['login'] = filter_var((string) $request->request->get('usernameForm'), FILTER_SANITIZE_STRING);
            $filteredData['ip'] = filter_var($ip, FILTER_SANITIZE_STRING);
            $filteredData['session'] = $session;
            $filteredData['last_visite'] = $dateheure;

            if (!$logEntry) {
                $filteredData['compt'] = 1;
                $logs->insertLogEntry($filteredData);
            } else {
                $lastvisiteSession = $logEntry['last_visite'];

                $_SESSION['lastvisiteSession'] = $lastvisiteSession;

                $filteredData['compt'] = $logEntry['compt'] + 1;

                $logs->updateLogEntry($filteredData);
            }

            // we must avoid to redirect to some special pages
            // otherwise, the user can't access to phpCollab
            $member['mem_last_page'] = str_replace(
                'accessfile.php?mode=view&',
                'viewfile.php?',
                $member['mem_last_page']
            );
            $member['mem_last_page'] = str_replace(
                'accessfile.php?mode=download&',
                'viewfile.php?',
                $member['mem_last_page']
            );

            $logger->info('User logged in', ['username' => $usernameForm]);

            //redirect for external link to internal page
            if ($request->request->get('url') != "") {
                if ($member['mem_profil'] == "3") {
                    phpCollab\Util::headerFunction("../{$request->request->get('url')}&updateProject=true");
                } else {
                    phpCollab\Util::headerFunction("../{$request->request->get('url')}");
                }
            } //redirect to last page required (with auto log out feature)
            else {
                if ($member['mem_last_page'] != "" && $member['mem_profil'] != "3" && $lastvisitedpage) {
                    $members->setLastPageVisitedByLogin($usernameForm, '');
                    phpCollab\Util::headerFunction("../" . $member['mem_last_page']);
                } else {
                    if ($member['mem_last_page'] != "" && (!empty($loginCookie) && !empty($passwordCookie)) && $member['mem_profil'] != "3" && $lastvisitedpage) {
                        $members->setLastPageVisitedByLogin($usernameForm, '');
                        phpCollab\Util::headerFunction("../" . $member['mem_last_page']);
                    } //redirect to home or admin page (if user is administrator)
                    else {
                        if ($member['mem_profil'] == "3") {
                            phpCollab\Util::headerFunction("../projects_site/home.php");
                        } else {
                            if ($member['mem_profil'] == "0") {
                                if ($adminathome == '1') {
                                    phpCollab\Util::headerFunction("../general/home.php");
                                } else {
                                    phpCollab\Util::headerFunction("../administration/admin.php");
                                }
                            } else {
                                phpCollab\Util::headerFunction("../general/home.php");
                            }
                        }
                    }
                }
            }
        }
    }
}
error_log("session = " . $session, 0);
if ($session == "false" && empty($url)) {
    $logger->notice('Invalid session for user', ['username' => $usernameForm]);
    $error = $strings["session_false"];
    session_regenerate_id();
}

if ($logout == "true") {
    $msg = "logout";
}

if ($demoMode == "true") {
    $usernameForm = "demo";
    $passwordForm = "demo";
}


$notLogged = "true";

$setTitle .= " : Login";
$bodyCommand = "onLoad='document.loginForm.usernameForm.focus();'";


include APP_ROOT . '/themes/' . THEME . '/header.php';

$blockPage = new phpCollab\Block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs("&nbsp;");
$blockPage->closeBreadcrumbs();

if ($msg != "") {
    include_once('../includes/messages.php');
    $blockPage->messageBox($msgLabel);
}


$block1 = new phpCollab\Block();

$block1->form = "login";
$block1->openForm("../general/login.php?auth=test");

if (!empty($request->query->get('url'))) {
    echo "<input value='{$request->query->get('url')}' type='hidden' name='url'>";
}

if ($error != "") {
    $block1->headingError($strings["errors"]);
    $block1->contentError($error);
}

$block1->heading($setTitle . " : " . $strings["login"]);

$block1->openContent();
$block1->contentTitle($strings["please_login"]);

$selectLanguage = "<select name='languageForm'>";
$selectLanguage .= "<option value='$langDefault'>Default (" . $langValue["$langDefault"] . ")</option>";
$selectLanguage .= "
	<option value='ar'>Arabic</option>
	<option value='az'>Azerbaijani</option>
	<option value='pt-br'>Brazilian Portuguese</option>
	<option value='bg'>Bulgarian</option>
	<option value='ca'>Catalan</option>
	<option value='zh'>Chinese simplified</option>
	<option value='zh-tw'>Chinese traditional</option>
	<option value='cs-iso'>Czech (iso)</option>
	<option value='cs-win1250'>Czech (win1250)</option>
	<option value='da'>Danish</option>
	<option value='nl'>Dutch</option>
	<option value='en'>English</option>
	<option value='et'>Estonian</option>
	<option value='fr'>French</option>
	<option value='de'>German</option>
	<option value='hu'>Hungarian</option>
	<option value='is'>Icelandic</option>
	<option value='in'>Indonesian</option>
	<option value='it'>Italian</option>
	<option value='ko'>Korean</option>
	<option value='lv'>Latvian</option>
	<option value='ja'>Japanese</option>
	<option value='no'>Norwegian</option>
	<option value='pl'>Polish</option>
	<option value='pt'>Portuguese</option>
	<option value='ro'>Romanian</option>
	<option value='ru'>Russian</option>
	<option value='sk-win1250'>Slovak (win1250)</option>
	<option value='es'>Spanish</option>
	<option value='tr'>Turkish</option>
	<option value='uk'>Ukrainian</option>
";

$selectLanguage .= "</select>";

$block1->contentRow($strings["language"], $selectLanguage);

$block1->contentRow("* " . $strings["user_name"], "<input value='$usernameForm' type='text' name='usernameForm'>");
$block1->contentRow("* " . $strings["password"], "<input value='$passwordForm' type='password' name='passwordForm' autocomplete='off'>");

$block1->contentRow(
    "",
    "<input type='submit' name='save' value='" . $strings["login"] . "'><br/><br/><br/>" . $blockPage->buildLink(
        "../general/sendpassword.php?",
        $strings["forgot_pwd"],
        'in'
    )
);

$block1->closeContent();
$block1->closeForm();

include APP_ROOT . '/themes/' . THEME . '/footer.php';
