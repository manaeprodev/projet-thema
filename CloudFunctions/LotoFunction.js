import puppeteer from 'puppeteer';
const { Storage } = require('@google-cloud/storage');
const { default: puppeteer } = require('puppeteer');


const resultatLotoUrl = "https://www.fdj.fr/jeux-de-tirage/resultats";

exports.myCloudFunction = async (req, res) => {
    try {
        
        //Acces a la page avec puppeteer
        const browser = await puppeteer.launch(); //Setup un nouveau browser
        const page = await browser.newPage(); //Creer une nouvelle page internet
        await page.goto(resultatLotoUrl); //On va sur la page que l'on a créé
        await browser.close(); //On ferme le browser 

        // Store the response in a GCP bucket
        const storage = new Storage({
            keyFilename: process.env.GOOGLE_APPLICATION_CREDENTIALS
        });
        const bucket = storage.bucket('velam_bucket');

        //Nom du fichier
        //Changer le nom du fichier --> N°jour_N°mois_N°annee.csv
        const file = bucket.file(`${Date.now()}_response.json`);
        await file.save(JSON.stringify(response.data));

    } catch (error) {
        console.error(error);
        res.status(500).send('An error occurred');
    }
}