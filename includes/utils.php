<?php

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
    $is_logged_in = isset($_COOKIE['logged_in']) ? $_COOKIE['logged_in'] : false;
    if ($is_logged_in) {
        $login_logout  = render(file_get_contents("template/header.logged.html"), [
            'isAdmin' => isset($_COOKIE['role']) && $_COOKIE['role'] == 'admin' ? true : false,
            'cart_count' => isset($_COOKIE['cart'])? count(json_decode($_COOKIE['cart'], true)??[]) : 0,
        ]);
    } else {
        $login_logout = render(file_get_contents("template/header.nologin.html"),[]);
    }
    return render(file_get_contents('template/header.html'), [
        'login_logout' => $login_logout
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
