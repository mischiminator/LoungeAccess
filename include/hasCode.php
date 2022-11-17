<form class="form-signin" method="POST">
    <div class="text-center mb-4">
        <img class="mb-4" src="<?= $config['logofile'] ?>" alt="" width="72" height="72">
        <h1 class="h1 mb-3">Gigamot Charger Lounge</h1>
        <h1 class="h3 mb-3" lang="de">Hallo,<br />
            Dein Code ist noch gültig.<br /> 
            Er wurde an <strong><?=$_POST['inputPhone']?></strong> gesendet.<br />
            Schaue in deiner Nachrichten/SMS App wie er lautet.
        </h1>
        <h1 class="h3 mb-3" lang="en">Hello,<br />
            Your code is still valid.<br /> 
            It was sent to<br /> 
            <strong><?=$_POST['inputPhone']?></strong><br />
            Please check your Messages/SMS app.
        </h1>
    </div>
    <p class=" mt-5 mb-3 text-muted text-center">&copy; Gigamot GmbH <?= date("Y"); ?>
    </p>
</form>