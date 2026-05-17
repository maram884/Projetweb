<?php
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="./images/favicon.png">
    <title>uberge</title>
</head>

<body>
    <header>
        <a href="#" class="logo"><span>A</span>UBERGE</a>
        <div class="menuToggle" onclick="toggleMenu();"></div>
        <ul class="navbar">
            <li><a href="#banniere" onclick="toggleMenu();">Accueil</a></li>
            <li><a href="#apropos" onclick="toggleMenu();">A propos</a></li>
            <li><a href="#menu" onclick="toggleMenu();">Menu</a></li>
            <li><a href="#expert" onclick="toggleMenu();">Expert</a></li>
            <li><a href="#temoignage" onclick="toggleMenu();">Temoignage</a></li>
            <li><a href="#contact" onclick="toggleMenu();">Contact</a></li>
            <li><a href="reservation.php" class="btn-reserve">Reservation</a></li>
        </ul>
    </header>
    <section class="banniere" id="banniere">
    <div class="contenu">
        <h2>Que Des Plats Délicieux</h2>
        <p>Ici, vous pouvez déguster de la bonne nourriture délicieuse en famille et entre amis..</p>
        <a href="#menu" class="btn1">Notre Menu</a>
        <a href="reservation.php" class="btn2">Reservation</a>
    </div>
</section>
<section class="apropos" id="apropos">
    <div class="row">
        <div class="col50">
            <h2 class="titre-texte"><span>A</span> Propos De Nous</h2>
            <p>A panoramic restaurant, housed in a 6-metre-high glass cube with a golden ceiling, in one of the Duo Towers, takes your breath away. To discover it, you have to climb to the 25th floor of this 27-storey building, with the TacTac Skybar on the rooftop.</p>
        </div>
        <div class="col50">
            <div class="img">
                <img src="./images/plat3.jpg" alt="image">
            </div>
        </div>
    </div>
</section>
<section class="menu" id="menu">
    <div class="titre">
        <h2 class="titre-texte"><span>M</span>enu</h2>
        <p>Our delicious menu</p>
    </div>
    <div class="contenu">

<?php

$sql = "SELECT * FROM produits ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {

?>

    <div class="box">

        <div class="imbox">
            <img src="<?php echo $row['image_path']; ?>" alt="">
        </div>

        <div class="text">
            <h3><?php echo $row['nom']; ?></h3>
        </div>

    </div>

<?php
}
?>

</div>
 <div class="titre">
    <a href="#" class="btn1">Voir Plus</a>
 </div>
</section>
<section class="expert" id="expert">
    <div class="titre">
        <h2 class="titre-texte">Nos <span>E</span>xperts</h2>
    </div>
    <div class="contenu">
        <div class="box">
            <div class="imbox">
                <img src="./images/Aissam.jpg" alt="">
            </div>
            <div class="text">
                <h3>Aissam Ait Ouakrim</h3>
            </div>
        </div>
        <div class="box">
            <div class="imbox">
                <img src="./images/Philippe.jpg" alt="">
            </div>
            <div class="text">
                <h3>Philippe Etchebest</h3>
            </div>
        </div>
        <div class="box">
            <div class="imbox">
                <img src="./images/paul.jpg" alt="">
            </div>
            <div class="text">
                <h3>Paul Bocuse</h3>
            </div>
        </div>
        <div class="box">
            <div class="imbox">
                <img src="./images/Jérôme.jpg" alt="">
            </div>
            <div class="text">
                <h3>Jérôme Bocuse</h3>
            </div>
        </div>
    </div>
 </div>
</section>
 <section class="temoignage" id="temoignage">
    <div class="titre blanc">
        <h2 class="titre-texte">Que Disent Nos <span>C</span>lients</h2>
        
    </div>
    <div class="contenu">
        <div class="box">
            <div class="imbox">
                <img src="./images/t1.jpeg" alt="">
            </div>
            <div class="text">
                <p> La qualité des ingrédients est indéniable, et le service était impeccable. Un régal pour les papilles!.</p>
                <h3>Franck leroi</h3>
            </div>
        </div>
        <div class="box">
            <div class="imbox">
                <img src="./images/t2.jpg" alt="">
            </div>
            <div class="text">
                Délicieux repas! J'ai récemment découvert ce restaurant et la cuisine est tout simplement incroyable. Chaque bouchée était une explosion de saveurs délicates.<h3>Jack Mary</h3>
            </div>
        </div>
        <div class="box">
            <div class="imbox">
                <img src="./images/t3.jpg" alt="">
            </div>
            <div class="text">
                <p>La présentation était élégante, et chaque élément sur l'assiette était parfaitement équilibré. C'est le genre de repas qui vous laisse souriant longtemps après avoir quitté le restaurant.</p>
                <h3>Robert Berathion</h3>
            </div>
        </div>
    </div>
 </section>

    <section class="contact" id="contact">
        <div class="titre noir">
            <h2 class="titre-text"><span>C</span>ontact</h2>
            
        </div>
        <div class="contactform">
            <h3>Envoyer un message</h3>
            <form method="post">
                <div class="inputboite">
                    <input name="nom" type="text" placeholder="Nom" required>
                </div>
                <div class="inputboite">
                    <input name="prenom" type="text" placeholder="Prénom" required>
                </div>
                <div class="inputboite">
                    <input name="email" type="email" placeholder="Email" required>
                </div>
                <div class="inputboite">
                    <textarea name="msg" placeholder="Message" required></textarea>
                </div>
                <div class="inputboite">
                    <input name="envoyer" type="submit" value="Envoyer">
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        function toggleMenu() {
            const menuToggle = document.querySelector('.menuToggle');
            const navbar = document.querySelector('.navbar');
            navbar.classList.toggle('active');
            menuToggle.classList.toggle('active');
        }

        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            header.classList.toggle("sticky", window.scrollY > 0);
        });
    </script>
</body>

</html>
