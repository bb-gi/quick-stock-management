# Guide Utilisateur - Quick Stock Management for WooCommerce

Ce guide vous aidera à utiliser l'interface de gestion rapide du stock de WooCommerce, vous permettant de modifier rapidement le statut de stock de vos produits.

## Accéder à la page de gestion rapide du stock

1.  Connectez-vous à votre tableau de bord d'administration WordPress.
2.  Dans le menu latéral gauche, survolez "Produits".
3.  Cliquez sur "Gestion rapide du stock".

Vous arriverez sur une page similaire à celle-ci :

![Interface de gestion rapide du stock](/home/ubuntu/qsm_interface_screenshot_placeholder.png)

## Comprendre l'interface

L'interface est conçue pour être simple et intuitive :

*   **Nom du produit** : La première colonne affiche le nom de chaque produit.
*   **Statut du stock** : La deuxième colonne contient un interrupteur à bascule et un badge visuel indiquant le statut actuel du stock.
    *   **Interrupteur vert (coché)** : Le produit est en stock (`instock`).
    *   **Interrupteur gris (non coché)** : Le produit est en rupture de stock (`outofstock`).
    *   **Badge vert "In Stock"** : Confirmation visuelle que le produit est en stock.
    *   **Badge rouge "Out of Stock"** : Confirmation visuelle que le produit est en rupture de stock.

## Gérer le stock des produits

Pour modifier le statut de stock d'un produit :

1.  **Localisez le produit** que vous souhaitez modifier dans la liste.
2.  **Cliquez sur l'interrupteur à bascule** à côté du nom du produit.
    *   Si le produit est en stock, cliquez pour le passer en rupture de stock.
    *   Si le produit est en rupture de stock, cliquez pour le passer en stock.

### Feedback visuel

*   Pendant la sauvegarde de la modification, un petit indicateur de chargement (spinner) apparaîtra à côté de l'interrupteur.
*   Une fois la mise à jour réussie, le badge de statut changera de couleur et de texte pour refléter le nouveau statut.

### Tri automatique

La liste des produits se trie automatiquement pour afficher d'abord les produits en stock, puis les produits en rupture de stock. Au sein de chaque groupe, les produits sont triés par ordre alphabétique.

## Dépannage rapide

*   **Le statut ne change pas ?** Vérifiez votre connexion internet. Si le problème persiste, rechargez la page.
*   **Message d'erreur ?** Lisez le message pour comprendre le problème. Il peut s'agir d'un problème de permissions ou d'une erreur serveur.

Si vous avez d'autres questions ou rencontrez des problèmes, veuillez consulter la documentation d'installation ou contacter votre administrateur système.

