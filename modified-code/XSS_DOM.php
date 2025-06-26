<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: DOM Based Cross Site Scripting (XSS)' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'xss_d';
$page[ 'help_button' ]   = 'xss_d';
$page[ 'source_button' ] = 'xss_d';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
        case 'low':
                $vulnerabilityFile = 'low.php';
                break;
        case 'medium':
                $vulnerabilityFile = 'medium.php';
                break;
        case 'high':
                $vulnerabilityFile = 'high.php';
                break;
        default:
                $vulnerabilityFile = 'impossible.php';
                break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/xss_d/source/{$vulnerabilityFile}";

# For the impossible level, don't decode the querystring
$decodeURI = "decodeURI";
if ($vulnerabilityFile == 'impossible.php') {
        $decodeURI = "";
}

$page[ 'body' ] = <<<EOF
<div class="body_padded">
        <h1>Vulnerability: DOM Based Cross Site Scripting (XSS)</h1>

        <div class="vulnerable_code_area">
 
                <p>Please choose a language:</p>

                <form name="XSS" method="GET">
                        <select name="default">
                                <script>
                                        if (document.location.href.indexOf("default=") >= 0) {
                                                var lang = document.location.href.substring(document.location.href.indexOf("default=")+8);
                                                
                                                //💡XSS 방어 코드
                                                lang = lang
                                                    .replace(/&/g, "&amp;") //💡&를 &amp로
                                                    .replace(/</g, "&lt;")  //💡<를 &lt;로
                                                    .replace(/>/g, "&gt;") //💡>를 &gt;로
                                                    .replace(/"/g, "&quot;") //💡"를 &quot;로
                                                    .replace(/'/g, "&#x27;") //💡'를 &#x27;로
                                                    .replace(/\//g, "&#x2F;"); ////💡<를 &#x2F;로

                                                document.write("<option value='" + lang + "'>" + $decodeURI(lang) + "</option>");
                                                document.write("<option value='' disabled='disabled'>----</option>");
                                        }
                                            
                                        document.write("<option value='English'>English</option>");
                                        document.write("<option value='French'>French</option>");
                                        document.write("<option value='Spanish'>Spanish</option>");
                                        document.write("<option value='German'>German</option>");
                                </script>
                        </select>
                        <input type="submit" value="Select" />
                </form>
        </div>
EOF;

$page[ 'body' ] .= "
        <h2>More Information</h2>
        <ul>
                <li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/xss/' ) . "</li>
                <li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/DOM_Based_XSS' ) . "</li>
                <li>" . dvwaExternalLinkUrlGet( 'https://www.acunetix.com/blog/articles/dom-xss-explained/' ) . "</li>
        </ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
