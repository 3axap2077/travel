<?php
function renderFeaturesSection() {
    $features = [
        [
            'img' => 'img/1-1.jpg',
            'description' => 'text1.',
            'buttonText' => 'button green',
            'buttonClass' => 'green-btn',
        ],
        [
            'img' => 'img/1-2.jpg',
            'description' => 'text2',
            'buttonText' => 'See Details',
            'buttonClass' => 'blue-btn',
        ],
        [
            'img' => 'img/1-3.jpg',
            'description' => 'text3',
            'buttonText' => 'Button Red',
            'buttonClass' => 'red-btn',
        ],
    ];

    echo '<section id="feature-area" class="about-section">
            <div class="container">
                <div class="row text-center inner">';

    foreach ($features as $feature) {
        echo '<div class="col-sm-4">
                <div class="feature-content">
                    <img src="' . htmlspecialchars($feature['img']) . '" alt="Image">
                    <p class="feature-content-description">' . $feature['description'] . '</p> 
                    <a href="' . '" class="feature-content-link ' . htmlspecialchars($feature['buttonClass']) . '">' . $feature['buttonText'] . '</a>
                </div>
              </div>';
    }

    echo '      </div>
            </div>
        </section>';
}

?>
