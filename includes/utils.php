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
    function create_card($card_data) {
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



?>