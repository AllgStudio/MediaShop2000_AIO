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
    "productdetail" => [
        "name"=> "Detaglio Prodotto",
        "url" => "productdetail.php"
    ],
    "userprofile" => [
        "name"=> "Profilo Utente",
        "url" => "userprofile.php"
    ],
    "userordermanage" => [
        "name"=> "Gestione Ordini",
        "url" => "userordermanage.php"
    ],
    "orderdetails"=>[
        "name"=> "Dettagli Ordine",
        "url" => "orderdetails.php"
    ],
    "search" => [
        "name"=> "Search",
        "url" => "search.php"
    ],
    "register" => [
        "name"=> "Register",
        "url" => "register.php"
    ],
    "login" => [
        "name"=> "Login",
        "url" => "login.php"
    ],

];

$BREADCRUMB = [
    "index" => [],
    "shop" => ["home","shop"],
    "about" => ["home","about"],
    "productdetail" => ["home","shop","productdetail"],
    "userprofile" => ["home","userprofile"],
    "userordermanage" => ["home","userprofile","userordermanage"],
    "orderdetails" => ["home","userprofile","userordermanage","orderdetails"],
    "search" => ["home","search"],
    "register" => ["home","login", "register"],
    "login" => ["home","login"],
];



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
           
        ]);
    } else {

        $login_logout = render(file_get_contents("template/header.nologin.html"),[
           
        ]);
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
        $count = 0;
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true);
            foreach($cart as $item) {
                $count += $item['quantity']??0;
            }
        }

    return render(file_get_contents('template/header.html'), [
        'cart_count' => $count,
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

function create_error_page($error)
{
    echo create_page('template/index.html',[
        'header_title' =>'500 Internal error | MediaShop2000',
        'header_description' => 'La pagina di errore 500',
        'header_keywords' => "errore, about, media, games",
        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/500.html'), [
            "error" => $error
        ]),
        'page_footer' => create_page_footer(),
    ]);
    exit();
}