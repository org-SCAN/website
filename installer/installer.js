#!/usr/bin/env node
/**
 * Installateur amélioré pour l'application SCAN utilisant Laravel Sail.
 * Structure :
 * - Le dépôt est cloné dans le dossier "SCAN"
 * - Le contenu de l'application se trouve dans le sous-dossier "src"
 */

const inquirer = require('inquirer');
const { exec, spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectDir = path.join(process.cwd(), 'SCAN');
const srcDir = path.join(projectDir, 'src');

// Vérifier la présence d'une commande (docker, git, etc.)
function checkCommand(command) {
    return new Promise((resolve) => {
        exec(`${command} --version`, (error) => {
            resolve(!error);
        });
    });
}

// Exécuter une commande et afficher ses logs
function runCommand(command, args, options = {}) {
    return new Promise((resolve, reject) => {
        const proc = spawn(command, args, { stdio: 'inherit', shell: true, ...options });
        proc.on('close', (code) => {
            if (code !== 0) {
                reject(new Error(`La commande "${command} ${args.join(' ')}" a retourné le code ${code}`));
            } else {
                resolve();
            }
        });
    });
}

// Fonction d'installation complète
async function installationProcess() {
    console.log("=== Processus d'installation de SCAN ===");

    // Vérification préalable : Docker et Git
    console.log("\nVérification de Docker...");
    if (!await checkCommand('docker')) {
        console.error("Docker n'est pas installé.\nTéléchargez-le ici : https://www.docker.com/products/docker-desktop");
        process.exit(1);
    }
    console.log("Docker est installé.");

    console.log("\nVérification de Git...");
    if (!await checkCommand('git')) {
        console.error("Git n'est pas installé.\nTéléchargez-le ici : https://git-scm.com/downloads");
        process.exit(1);
    }
    console.log("Git est installé.");

    // Demande des paramètres nécessaires
    const { environment, containerName, defaultEmail, defaultPassword } = await inquirer.prompt([
        {
            type: 'list',
            name: 'environment',
            message: 'Choisissez l\'environnement à installer :',
            choices: ['DEV', 'PROD'],
            default: 'DEV'
        },
        {
            type: 'input',
            name: 'containerName',
            message: 'Nom du container Laravel Sail (celui contenant le code de l\'application) :',
            default: 'laravel.test'
        },
        {
            type: 'input',
            name: 'defaultEmail',
            message: 'DEFAULT_EMAIL (ex: test@test.com) :',
            default: 'test@test.com'
        },
        {
            type: 'password',
            name: 'defaultPassword',
            message: 'DEFAULT_PASSWORD :',
            mask: '*',
            default: 'password123'
        }
    ]);

    // Définir la branche en fonction de l'environnement
    const branch = environment === 'PROD' ? 'main' : 'master/dev';
    const gitRepo = 'https://github.com/org-SCAN/website.git';

    // Clonage du dépôt s'il n'existe pas déjà
    if (!fs.existsSync(projectDir)) {
        console.log("\nClonage du repository...");
        try {
            await runCommand('git', ['clone', gitRepo, projectDir]);
            console.log("Repository cloné avec succès.");
        } catch (err) {
            console.error("Erreur lors du clonage du dépôt :", err.message);
            process.exit(1);
        }
    } else {
        console.log(`Le répertoire ${projectDir} existe déjà.`);
    }

    // Passage sur la branche choisie
    try {
        console.log(`\nBasculement sur la branche ${branch}...`);
        await runCommand('git', ['checkout', branch], { cwd: projectDir });
        console.log("Branche mise à jour.");
    } catch (err) {
        console.error("Erreur lors du changement de branche :", err.message);
        process.exit(1);
    }

    // Création et configuration du fichier .env dans le dossier src
    const envExamplePath = path.join(srcDir, '.env.example');
    const envPath = path.join(srcDir, '.env');
    if (!fs.existsSync(envPath)) {
        fs.copyFileSync(envExamplePath, envPath);
        console.log("\nLe fichier .env a été créé à partir de .env.example dans le dossier src.");
    } else {
        console.log("\nLe fichier .env existe déjà dans le dossier src.");
    }

    // Mise à jour du fichier .env pour les variables obligatoires (marquées d'un *)
    let envContent = fs.readFileSync(envPath, 'utf8');
    const updateEnv = (key, value) => {
        const regex = new RegExp(`^${key}=.*$`, 'm');
        if (regex.test(envContent)) {
            envContent = envContent.replace(regex, `${key}=${value}`);
        } else {
            envContent += `\n${key}=${value}`;
        }
    };

    updateEnv('DEFAULT_EMAIL', defaultEmail);
    updateEnv('DEFAULT_PASSWORD', defaultPassword);
    fs.writeFileSync(envPath, envContent);
    console.log(".env mis à jour avec vos paramètres.");

    // Démarrage de Laravel Sail depuis le dossier src
    console.log("\nDémarrage de Laravel Sail (Docker)...");
    try {
        await runCommand('sh', ['./vendor/bin/sail', 'up', '-d'], { cwd: srcDir });
        console.log("Les containers Docker ont démarré.");
    } catch (err) {
        console.error("Erreur lors du démarrage de Sail :", err.message);
        process.exit(1);
    }

    // Petite pause pour laisser le temps aux containers de démarrer
    await new Promise(resolve => setTimeout(resolve, 10000));

    // Exécution des commandes d'installation et de troubleshooting dans le container
    const sailExec = async (cmd) => {
        await runCommand('docker', ['exec', '-it', containerName, 'sh', '-c', cmd], { cwd: srcDir });
    };

    console.log("\nExécution des commandes dans le container Docker...");
    try {
        await sailExec('npm update');
        await sailExec('composer install');
        // Installer Laravel Sail en ignorant les exigences PHP pour pallier le problème de maatwebsite/excel
        await sailExec('composer require laravel/sail --dev --ignore-platform-reqs');
        await sailExec('php artisan cache:clear');
        await sailExec('php artisan key:generate');
        await sailExec('chmod -R 777 storage/');
        await sailExec('chmod -R 770 bootstrap/cache/');
        await sailExec('php artisan migrate:refresh --seed');
        // Démarrer le worker de queue en arrière-plan
        await sailExec('nohup php artisan queue:work > /dev/null 2>&1 &');
        console.log("\nInstallation terminée avec succès !");
    } catch (err) {
        console.error("Erreur lors de l'exécution des commandes dans le container :", err.message);
        process.exit(1);
    }

    console.log("\nVous pouvez accéder à l'application SCAN via http://localhost:80");
}

// Fonction de démarrage / arrêt de l'application
async function startStopProcess() {
    if (!fs.existsSync(projectDir)) {
        console.error("L'application n'est pas installée. Veuillez lancer l'installation d'abord.");
        process.exit(1);
    }
    const { action } = await inquirer.prompt([
        {
            type: 'list',
            name: 'action',
            message: 'Que souhaitez-vous faire ?',
            choices: ['Démarrer l\'application', 'Arrêter l\'application']
        }
    ]);

    // Commandes de Sail dans le dossier src
    if (action === 'Démarrer l\'application') {
        console.log("\nDémarrage de l'application...");
        try {
            await runCommand('sh', ['./vendor/bin/sail', 'up', '-d'], { cwd: srcDir });
            console.log("L'application a démarré.");
        } catch (err) {
            console.error("Erreur lors du démarrage :", err.message);
            process.exit(1);
        }
    } else {
        console.log("\nArrêt de l'application...");
        try {
            await runCommand('sh', ['./vendor/bin/sail', 'down'], { cwd: srcDir });
            console.log("L'application a été arrêtée.");
        } catch (err) {
            console.error("Erreur lors de l'arrêt :", err.message);
            process.exit(1);
        }
    }
}

// Fonction de mise à jour
async function updateProcess() {
    if (!fs.existsSync(projectDir)) {
        console.error("L'application n'est pas installée. Veuillez lancer l'installation d'abord.");
        process.exit(1);
    }
    console.log("\nMise à jour de l'application...");
    try {
        await runCommand('git', ['pull'], { cwd: projectDir });
        console.log("Mise à jour effectuée avec succès.");
    } catch (err) {
        console.error("Erreur lors de la mise à jour :", err.message);
        process.exit(1);
    }
}

// Menu principal
async function mainMenu() {
    // Options du menu selon que l'application est installée ou non
    const options = [];
    if (!fs.existsSync(projectDir)) {
        options.push('Installation');
    } else {
        options.push('Démarrage / Arrêt de l\'application', 'Mise à jour');
    }
    options.push('Quitter');

    const { menuChoice } = await inquirer.prompt([
        {
            type: 'list',
            name: 'menuChoice',
            message: 'Sélectionnez une option :',
            choices: options
        }
    ]);

    switch (menuChoice) {
        case 'Installation':
            await installationProcess();
            break;
        case 'Démarrage / Arrêt de l\'application':
            await startStopProcess();
            break;
        case 'Mise à jour':
            await updateProcess();
            break;
        case 'Quitter':
        default:
            console.log("Au revoir !");
            process.exit(0);
    }
}

// Lancement du menu principal
(async () => {
    await mainMenu();
})();