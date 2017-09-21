<?php
#Application name: PhpCollab
#Status page: 0

$checkSession = "true";
include '../includes/library.php';

$bouton[5] = "over";
$titlePage = $strings["bulletin_board"];
include 'include_header.php';

$tmpquery = "WHERE topic.project = '$projectSession' AND topic.published = '0' ORDER BY topic.last_post DESC";
$listTopics = new phpCollab\Request();
$listTopics->openTopics($tmpquery);
$comptListTopics = count($listTopics->top_id);

$block1 = new phpCollab\Block();

$block1->heading($strings["bulletin_board"]);

if ($comptListTopics != "0") {
    echo "<table cellspacing=\"0\" width=\"90%\" border=\"0\" cellpadding=\"3\" cols=\"4\" class=\"listing\">
<tr><th>" . $strings["topic"] . "</th><th>" . $strings["posts"] . "</th><th>" . $strings["owner"] . "</th><th class=\"active\">" . $strings["last_post"] . "</th></tr>";

    for ($i = 0; $i < $comptListTopics; $i++) {
        if (!($i % 2)) {
            $class = "odd";
            $highlightOff = $block1->getOddColor();
        } else {
            $class = "even";
            $highlightOff = $block1->getEvenColor();
        }
        echo "<tr class=\"$class\" onmouseover=\"this.style.backgroundColor='" . $block1->getHighlightOn() . "'\" onmouseout=\"this.style.backgroundColor='" . $highlightOff . "'\"><td><a href=\"showallthreads.php?id=" . $listTopics->top_id[$i] . "\">" . $listTopics->top_subject[$i] . "</a></td><td>" . $listTopics->top_posts[$i] . "</td><td>" . $listTopics->top_mem_name[$i] . "</td><td>" . phpCollab\Util::createDate($listTopics->top_last_post[$i], $timezoneSession) . "</td></tr>";
    }
    echo '</table><hr />\n';
} else {
    echo '<table cellspacing="0" border="0" cellpadding="2"><tr><td colspan="4">' . $strings["no_items"] . '</td></tr></table><hr>';
}

echo "<br/><br/>
<a href=\"createthread.php?\" class=\"FooterCell\">" . $strings["create_topic"] . "</a>";

include("include_footer.php");
