<?php


$CURRENT_PAGE = basename($_SERVER['PHP_SELF'], ".php");
$PAGELINKS = [
    "home" => [
        "name"=> "Home",
        "url" => "index.php"
    ],
    "shop" => [
        "name"=> "Shop",
        "url" => "shop.php"
    ],
    "about" => [
        "name"=> "About",
        "url" => "about.php"
    ],
];

$BREADCRUMB = [
    "index" => [],
    "shop" => ["home","shop"],
    "about" => ["home","about"],
];

/**
 * Creates a card
 *
 * @param array $card_data
 * [
 *  'card_image_url' => 'https://picsum.photos/200/300?a=szdf0',
 *  'card_image_desk' => 'A descriptive text about the content of the image 1',
 *  'card_title' => 'Card Title 1',
 *  'card_subtitle' => 'Card Subtitle 1',
 *  'card_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
 * ]
 * @return string html 
 */
function create_card($card_data)
{
    $card_template = file_get_contents('template/card.html');
    return render($card_template, $card_data);
    return $card_template;
}


/**
 * Get the language from the get or session
 */
function get_language()
{
    $lang = 'it';
    $lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : $lang;
    $lang = isset($_GET['lang']) ? $_GET['lang'] : $lang;
    // check language it zh en
    if ($lang != 'it' && $lang != 'zh' && $lang != 'en')
        $lang = 'it';
    return $lang;
}



/**
 * Render a template with data
 *
 * @param string $html
 * @param array $data
 * @return string html
 */

function render($html, $data)
{
    foreach ($data as $key => $value) {
        $html = str_replace("{{ $key }}", $value, $html);
    }
    return $html;
}

/**
 * Create the page header
 *
 * @return string html
 */
function create_page_header()
{
    global $CURRENT_PAGE, $PAGELINKS, $BREADCRUMB;

    $userRoleClass = 'd-none';
    if (isset($_COOKIE['user'])) {
        $userData = json_decode($_COOKIE['user'], true);
        if ($userData && isset($userData['role']) && trim($userData['role']) === 'admin') {
            $userRoleClass = ''; // Admin users don't get the 'd-none' class
        } else {
            $userRoleClass = 'd-none'; // Non-admin users get the 'd-none' class
        }
    } else {
        $userRoleClass = 'd-none'; // If there's no user cookie, default to 'd-none'
    }

    $is_logged_in = isset($_COOKIE['logged_in']) ? $_COOKIE['logged_in'] : false;
    if ($is_logged_in) {
        $login_logout  = render(file_get_contents("template/header.logged.html"), [
            'isAdmin' => $userRoleClass,
            'cart_count' => isset($_COOKIE['cart'])? count(json_decode($_COOKIE['cart'], true)??[]) : 0,
        ]);
    } else {
        $login_logout = render(file_get_contents("template/header.nologin.html"),[]);
    }
    
    $breadcrumb_html = "";
    if($BREADCRUMB[$CURRENT_PAGE]??false){
        $size = count($BREADCRUMB[$CURRENT_PAGE]);
        for($i=0; $i<$size; $i++){
            if($i == $size-1){
                $breadcrumb_html .= "<li aria-current='page'>". $PAGELINKS[$BREADCRUMB[$CURRENT_PAGE][$i]]['name'] ."</li>";
            }else{
                $breadcrumb_html .= render(file_get_contents('template/breadcrumb.html'), [
                    'label' => $PAGELINKS[$BREADCRUMB[$CURRENT_PAGE][$i]]['name'],
                    'url' => $PAGELINKS[$BREADCRUMB[$CURRENT_PAGE][$i]]['url'],
                    'label_attr' => "aria-label='Tornare a pagina " . $PAGELINKS[$BREADCRUMB[$CURRENT_PAGE][$i]]['name'] . "'",
                ]);
            }
        }
    }
    return render(file_get_contents('template/header.html'), [
        'login_logout' => $login_logout,
        "aria_label_attr" => $BREADCRUMB[$CURRENT_PAGE]??false?"aria-label='percorso di navigazione'":'',
        'breadcrumbs' => $breadcrumb_html,
    ]);
}

/**
 * Create the page menu
 *
 * @return string html
 */
function create_page_menu()
{
    $menu = file_get_contents('template/menu.html');
    return render($menu, []);
}

/**
 * Create the page footer
 *
 * @return string html
 */
function create_page_footer()
{
    $footer = file_get_contents('template/footer.html');
    return render($footer, []);
}

/**
 * Create the page
 *
 * @param string $template
 * @param array $data
 * @return string html
 */
function create_page($template, $data)
{
    $page = file_get_contents($template);
    $page = render($page, $data);
    return $page;
}
