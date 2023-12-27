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
    })
    console.log(allFdjCircles);
    await browser.close(); //On ferme le browser 
}

main();