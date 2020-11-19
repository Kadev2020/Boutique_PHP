<?php
    if(isset($_POST['mailform'])){
        echo '<pre>'; print_r($_POST); echo '</pre>';
        $header="MIME-Version: 1.0\r\n";
        $header.='From:"Kadev"<kadev2021@gmail.com>'."\n";
        $header.='Content-Type:text/html; charset="uft-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';

        $message='
        <html>
            <body>
                <div align="center">
                    <img src="https://images.freeimages.com/images/small-previews/acc/flower-banner-1169934.jpg"/>
                    <br>
                    <p> !! Pour finaliser votre inscription veuillez cliquer sur le lien ci-dessous !! </p>
                    <br><hr>
                    <button><a>Cliquez ICI pour valider votre email</a></button>
                    <br><hr><br>
                    <img src="https://images.freeimages.com/images/small-previews/132/banner-1-1163365.jpg"/>
                </div>
            </body>
        </html>
        ';

        mail("kadev2021@gmail.com", "Kadev's Shop | Finalisez votre inscription !", $message, $header);
    }
?>
<form method="POST" action="">
	<input type="submit" value="Recevoir un mail !" name="mailform"/>
</form>