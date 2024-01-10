import puppeteer from 'puppeteer';
const { Storage } = require('@google-cloud/storage');
const { default: puppeteer } = require('puppeteer');


function getDateFormatted() {
    const today = new Date();

    // Récupération du jour, du mois et de l'année
    let day = today.getDate();
    let month = today.getMonth() + 1; // Les mois commencent à 0 (janvier est 0)
    let year = today.getFullYear();

    // Formatage pour avoir deux chiffres pour le jour et le mois si nécessaire (ajout du zéro)
    if (day < 10) {
        day = `0${day}`;
    }
    if (month < 10) {
        month = `0${month}`;
    }

    // Formatage final dd/mm/yyyy
    const formattedDate = `${day}/${month}/${year}`;
    return formattedDate;
}


exports.myCloudFunction = async (req, res) => {
    try {
        
        const resultatLotoUrl = 'https://www.fdj.fr/jeux-de-tirage/loto/resultats';
        console.log('Connexion au site loto :');
        console.log(resultatLotoUrl);
    
        //Acces a la page avec puppeteer
        const browser = await puppeteer.launch(); //Setup un nouveau browser
        const page = await browser.newPage(); //Creer une nouvelle page internet
        await page.goto(resultatLotoUrl); //On va sur la page que l'on a créé
        
        const allFdjBalls = await page.evaluate(() => {
            const circles = document.querySelectorAll('.game-ball'); //Recupere les élements dans la balise HTML associé a la classe "game-ball"
            const ballsArray = []; // Tableau pour stocker les valeurs
    
            circles.forEach(circle => {
                ballsArray.push(circle.textContent.trim()); // Ajoute le texte des éléments à la liste
            });
            ballsArray.splice(-5);
            return ballsArray;
        });
        console.log(allFdjBalls);
        await browser.close(); //On ferme le browser 


        // Store the response in a GCP bucket
        const storage = new Storage({
            keyFilename: process.env.GOOGLE_APPLICATION_CREDENTIALS
        });
        const bucket = storage.bucket('predeect_bucket');

        //Nom du fichier
        //Changer le nom du fichier --> N°jour_N°mois_N°annee.csv
        const file = bucket.file(`${Date.now()}_response.json`);
        await file.save(JSON.stringify(response.data));

    } catch (error) {
        console.error(error);
        res.status(500).send('An error occurred');
    }
}