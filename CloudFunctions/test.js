const { Storage } = require('@google-cloud/storage');
const puppeteer  = require('puppeteer');

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
} catch(error){
    console.log(error);
}
