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
    $card_template = '
            <div class="card" tabindex="0">
                <div class="card-img">
                    <img src="' . $card_data['card_image_url'] . '" alt="' . $card_data['card_image_desk'] . '">
                </div>
                <div class="card-title">' . $card_data['card_title'] . '</div>
                <div class="card-subtitle">' . $card_data['card_subtitle'] . '</div>
                <div class="card-text">' . $card_data['card_text'] . '</div>
            </div>';

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
    $header = file_get_contents('template/header.html');

    $is_logged_in = isset($_COOKIE['logged_in']) ? $_COOKIE['logged_in'] : false;
    $lang = get_language();
    $i18n = include('includes/i18n/lang.php');

    if ($is_logged_in) {
        $login_logout  = '<li><a href="profile.php" aria-label="{{ account }}" title="{{ account }}"><span class="mdi mdi-account" aria-hidden="true"><span id="username">{{ username }}</span></span></a></li>';
        $login_logout .= '<li><a href="cart.php" aria-label="{{ cart }}"    title="{{ cart }}"><span class="mdi mdi-cart" aria-hidden="true"><span id="cart_counter">{{ cart_counter }}</span></span></a></li>';
        if (json_decode($_COOKIE['user'], true)['role'] == 'admin') {
            $login_logout .= '<li><a href="categorymanage.php" aria-label="{{ manage }}"    title="{{ manage }}"><span class="mdi mdi-cog" aria-hidden="true"></span></a></li>';
        }
        $login_logout .= '<li><a href="logout.php"  aria-label="{{ logout }}"  title="{{ logout }}"><span class="mdi mdi-logout"   aria-hidden="true"></span></a></li>';
    } else {
        $login_logout = '<li><a href="login.php"    aria-label="{{ login }}"   title="{{ login }}"><span class="mdi mdi-login"     aria-hidden="true"></span></a></li>';
    }

    return render($header, [
        'title' => $i18n['title'][$lang],
        'login_logout' => render($login_logout, [
            'login' => $i18n['login'][$lang],
            'cart' => $i18n['cart'][$lang],
            'account' => $i18n['account'][$lang],
            'logout' => $i18n['logout'][$lang],
            'manage' => $i18n['manage'][$lang],

            'username' => isset($_COOKIE['username']) ? $_COOKIE['username'] : '',
            'cart_counter' => isset($_COOKIE['cart_counter']) ? $_COOKIE['cart_counter'] : 0,
        ]),
        'home' => $i18n['home'][$lang],
        'about' => $i18n['about'][$lang],
        'shop' => $i18n['shop'][$lang],

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
