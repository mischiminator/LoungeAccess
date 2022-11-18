<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<form class="form-signin" method="POST">
    <div class="text-center mb-4">
        <img class="mb-4" src="<?= $config['logofile'] ?>" alt="" width="72" height="72">
        <h1 class="h1 mb-3">Gigamot Charger Lounge</h1>
        <p lang="de">Hallo,<br />gib bitte deine Handynummer ein, du bekommst einen Zugangscode per SMS gesendet
        </p>
        <p lang="en">Hello,<br /> please enter your mobile number,<br />you will receive an access code via SMS</p>
    </div>

    <div class="form-label-group">
        <input type="tel" id="inputPhone" name="inputPhone" class="form-control" placeholder="Telefonnummer"
            pattern="\+*[0-9]*" required autofocus value="<?php if ($captcha_error) {
                echo $_POST['inputPhone'];
            } ?>">
        <label for="inputPhone"><p lang="de">Telefonnummer</p><p lang="en">Mobile Number</p></label>
    </div>
    <?php if ($captcha_error) { ?>
    <div class="text-center mb-4">
        <p class="mb-3" lang="de">
            Bitte reCaptcha klicken!
        </p>
        <p class="mb-3" lang"en">
            Please solve reCaptcha!
        </p>
    </div>
    <?php } ?>
    <div class="g-recaptcha" data-sitekey="<?=$config['recaptcha_sitekey']?>"></div>
    <br />
    <div class="text-center">
        <button class="btn btn-lg btn-primary btn-block" type="submit"><div lang="de">Code senden</div><div lang="en">Send Code</div></button>
    </div>
    <p class="mt-5 mb-3 text-muted text-center">&copy; Gigamot GmbH <?= date("Y"); ?>
    </p>
</form>