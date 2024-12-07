<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OK Clothing - Accueil</title>
    <!-- Favicon -->
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <!-- Font Awesome (pour les icônes) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #a4d3a9, #f1b7b7);
            color: #333;
            overflow-x: hidden;
            position: relative;
            height: 100vh;
        }

        /* Effet de neige */
        .snowfall {
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 9999;
            width: 100%;
            height: 100%;
            background: transparent;
            overflow: hidden;
        }

        .snowfall .snow {
            position: absolute;
            background-color: white;
            border-radius: 50%;
            opacity: 0.8;
            animation: snowfall 10s linear infinite;
        }

        @keyframes snowfall {
            0% { transform: translateY(-10%); opacity: 0.9; }
            100% { transform: translateY(100%); opacity: 0.3; }
        }

        /* Section de l'en-tête */
        .header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 20px 40px;
            position: absolute;
            width: 100%;
            z-index: 10;
        }

        .header img {
            height: 40px;
            margin-right: 30px;
            transition: transform 0.3s ease;
        }

        .header img:hover {
            transform: scale(1.1);
        }

        .header nav {
            display: flex;
            align-items: center;
        }

        .header nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #d4af37;
            font-size: 1.5em;
            transition: color 0.3s, transform 0.3s;
        }

        .header nav a:hover {
            color: #fff;
            transform: scale(1.1);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        /* Section du diaporama */
        .company-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 150px;
            animation: fadeInUp 2s ease-out;
            z-index: 5;
        }

        @keyframes fadeInUp {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .slideshow-container {
            position: relative;
            width: 50%;
            max-width: 600px;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        }

        .slideshow-container img {
            width: 100%;
            display: none;
            transition: opacity 1s ease;
            border-radius: 20px;
        }

        .shop-section {
            flex: 1;
            padding: 30px;
            text-align: left;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .shop-section h1 {
            font-size: 3.5em;
            font-weight: 900;
            color: #e60f1c;
            text-transform: uppercase;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.4);
            margin-bottom: 15px;
            animation: neonGlow 1.5s ease-in-out infinite alternate;
        }

        @keyframes neonGlow {
            0% { text-shadow: 0 0 10px #ff0000, 0 0 20px #ff0000, 0 0 30px #ff0000; }
            100% { text-shadow: 0 0 10px #ff8c00, 0 0 20px #ff8c00, 0 0 30px #ff8c00; }
        }

        .shop-now {
            display: inline-block;
            padding: 18px 35px;
            background-color: #ff8e39;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 40px;
            box-shadow: 0 4px 20px rgba(255, 142, 57, 0.6);
            transition: transform 0.3s, background-color 0.3s, box-shadow 0.3s;
        }

        .shop-now:hover {
            background-color: #ffb41f;
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(255, 142, 57, 0.9);
        }

        .shop-now:active {
            transform: scale(0.95);
        }

        .shop-section p {
            font-size: 1.3em;
            line-height: 1.8;
            color: white;
            text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            transition: opacity 0.3s ease-in-out;
        }

        /* Conception réactive */
        @media (max-width: 768px) {
            .company-section {
                flex-direction: column;
                margin-top: 40px;
            }

            .slideshow-container, .shop-section {
                width: 100%;
            }

            .header nav a {
                font-size: 1em;
            }
        }
    </style>
    <script>
        let slideIndex = 0;

        // Fonction pour afficher les diapositives du diaporama
        function showSlides() {
            const slides = document.querySelectorAll(".slideshow-container img");
            slides.forEach((slide) => (slide.style.display = "none"));
            slideIndex = (slideIndex + 1) % slides.length;
            slides[slideIndex].style.display = "block";
            slides[slideIndex].style.opacity = "0";
            setTimeout(() => slides[slideIndex].style.opacity = "1", 100);
            setTimeout(showSlides, 3000); 
        }

        // Lancement du diaporama au chargement de la page
        window.onload = showSlides;
    </script>
</head>
<body>

<!-- Effet de neige -->
<div class="snowfall">
    <div class="snow" style="width: 10px; height: 10px; animation-duration: 8s; left: 10%;"></div>
    <div class="snow" style="width: 12px; height: 12px; animation-duration: 10s; left: 30%;"></div>
    <div class="snow" style="width: 15px; height: 15px; animation-duration: 12s; left: 50%;"></div>
</div>

<!-- En-tête -->
<div class="header">
    <img src="images/logo.png" alt="Logo">
    <nav>
        <a href="#home"><i class="fas fa-home"></i></a>
        <a href="#shop"><i class="fas fa-tshirt"></i></a>
        <a href="#login"><i class="fas fa-user"></i></a>
    </nav>
</div>

<!-- Section de l'entreprise avec diaporama et boutique -->
<div class="company-section">
    
    <div class="shop-section">
        <h1>Découvrez les Dernières Tendances!</h1>
        <p>Magasinez les dernières tendances de mode pour les fêtes et au-delà. Découvrez notre collection exclusive de vêtements !</p>
        <a href="login.php" class="shop-now">Magasiner Maintenant</a>
    </div>
</div>

</body>
</html>
