## Addario Clément, Pader Joris

<img align="right" height="100" src="https://brand.ynov.com/img/logos/projet_etudiant/ynov/prj_ynov.svg" alt="logo projet">

# PHP WordPress

Voici un plugin pour recevoir sur Discord et/ou Telegram, les nouveaux commentaires mis sur vos articles wordpress.

## Prérequis

- WordPress
- Discord ou telegram


## Installation

- Télécharger le dossier du plugin

  ![App Screenshot](https://i.postimg.cc/QNTvDKGs/Capture-d-cran-2022-01-27-11-44-12.png)

- Le compresser

  ![App Screenshot](https://i.postimg.cc/mkvdcwvR/Capture-d-cran-2022-01-27-11-42-38.png)

- Aller sur votre page administrateur Wordpress

  ![App Screenshot](https://i.postimg.cc/PqX4MB55/Capture-d-cran-2022-01-27-11-45-31.png)

- Cliquer sur **Ajouter** puis **Téléverser une application**

  ![App Screenshot](https://i.postimg.cc/PJjNWkd6/Capture-d-cran-2022-01-27-11-46-31.png)

- Glisser/Choisir le .zip 

- Activer le plugin

  ![App Screenshot](https://i.postimg.cc/HxSP5T0m/Capture-d-cran-2022-01-27-11-48-58.png)

- Une fois activé, vous n'avez plus qu'à rentrer le ChatID de telegram et/ou le Webhook de votre serveur Discord et le tour est joué ! (À noter que tous les settings sont sauvegardés dans la database)

  ![App Screenshot](https://i.ibb.co/SVXN1Zm/hb-Gb-TTXQEF.png)

  ![App Screenshot](https://i.postimg.cc/5NScKyKF/Capture-d-cran-2022-02-02-11-57-30.png)

  ![App Screenshot](https://i.postimg.cc/8zfBxMrg/Capture-d-cran-2022-01-27-11-54-12.png)



- Telegram :

  ![App Screenshot](https://i.postimg.cc/T32hD6f7/Capture-d-cran-2022-01-27-11-55-47.png)

- Discord :

  ![App Screenshot](https://i.postimg.cc/GtBH9JRc/Capture-d-cran-2022-01-27-11-55-33.png)


### Ou

Aller dans le **dossier racine** de WordPress > **wp-content > plugins**.

Puis mettre le fichier du plugin.

  ![App Screenshot](https://i.postimg.cc/HLq9WNRj/Capture.png)

Se rendre dans le **dashboard** > **Plugin**

  ![App Screenshot](https://i.postimg.cc/43WRrzS8/chrome-I9-Pla-T5-DYQ.png)


## Initialisation channel Telegram

Tout d'abord, créez votre channel Telegram dans lequel vous allez recevoir tout vos commentaires

  ![App Screenshot](https://i.postimg.cc/QCdkJ6Ft/Capture-d-cran-2022-02-02-11-35-05.png)

Nommez le 

  ![App Screenshot](https://i.postimg.cc/C5rCPj84/Capture-d-cran-2022-02-02-11-35-43.png)

Mettre votre channel en **public** et donnez lui un lien unique

  ![App Screenshot](https://i.postimg.cc/qMgxt5fD/Capture-d-cran-2022-02-02-11-36-49.png)

Cliquez sur ajouter un administrateur

  ![App Screenshot](https://i.postimg.cc/PfSM5k78/Capture-d-cran-2022-02-02-11-37-09.png)

Ajoutez notre bot telegram @YPHPBot (ProjetPHP)

  ![App Screenshot](https://i.postimg.cc/Dz1gv4V2/Capture-d-cran-2022-02-02-11-38-15.png)

Accordez lui tout les droits 

  ![App Screenshot](https://i.postimg.cc/zBjFWmX6/Capture-d-cran-2022-02-02-11-38-33.png)

Ajoutez également @JsonDumpsBot pour récupérer le ChatID (ce bot pourra ensuite être supprimé du channel)

  ![App Screenshot](https://i.postimg.cc/k49yBM9W/Capture-d-cran-2022-02-02-11-39-06.png)

Récupérez à la ligne 4 le Chat ID qui serra à mettre dans les paramètres du plugin sur Wordpress

  ![App Screenshot](https://i.postimg.cc/7ZF9rMWs/Capture-d-cran-2022-02-02-11-39-21.png)

## Création d'un Webhook

Sélectionner le channel où vous voulez créer votre Webhook.Puis Cliquer sur l'engrenage pour modifier le channel

  ![App Screenshot](https://i.ibb.co/88nN10g/webhook.png)

Ensuite aller voir dans les intégrations, puis "Créer un webhook"

  ![App Screenshot](https://i.ibb.co/px3x0ZB/o-Ta-F5h8-Qd-W.png)

Vous pouvez **changer son nom**, **choisir le salon spécifique** pour recevoir les notifications ou bien **récupérer** l'url du webhook

  ![App Screenshot](https://i.ibb.co/FmFRYLp/h-Moc-MDHl-Fb.png)

## Authors

- Clément Addario
- Joris Pader

  ![App Screenshot](https://media.giphy.com/media/WaLBCbU4LAovLZJijE/giphy.gif)