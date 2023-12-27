const { Storage } = require('@google-cloud/storage');
const puppeteer  = require('puppeteer');


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

const main = async (req, res) => {
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
}

try{

    main();
    const formattedDate = getDateFormatted();
    console.log(formattedDate); // Affiche la date du jour au format dd/mm/yyyy

} catch(error){
    console.log(error);
}
