<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation</title>
</head>

<body>
    <h1>Invitation au groupe : {{ $group->name }}</h1>
    <p>Pour y accéder, vous devez vous inscrire pour ceux qui n'ont pas encore de compte sur 
        <!-- <a href="http://localhost:5174/Registration">m' inscrire</a> et pour ceux qui disposent de compte sur <a href="http://localhost:5174">me connecter</a> -->
    </p>
    <p> Veuillez nous rejoindre pour plus de nouvelles, Voici votre code OTP : {{ $otp }}</p>

</body>

</html>