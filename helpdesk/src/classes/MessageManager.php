<?php

class MessageManager {
    public function displayMessages($messages) {
        if (isset($messages)) {
            error_reporting(E_ALL ^ E_WARNING);
            foreach ($messages as $message) {
                echo '
                <div class="message">
                    <span>' . $message . '</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    <script>
                        setTimeout(function() {
                            document.querySelector(".message").style.opacity = "0";
                            document.querySelector(".message").style.transition = "all 0.5s";
                            setTimeout(function() {
                                document.querySelector(".message").remove();
                            }, 500);
                        }, 8000);
                    </script>
                </div>
                ';
            }
        }
    }
}
?>
