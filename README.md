# blogPhp

## Forum
Ce projet consiste en la réalisation d'un forum en symfony.

## Description
Dans ce forum, les utilisateurs peuvent avoir 3 roles (ADMIN, BLOGGER, USER).
Le USER pourra consulter et commenter les posts.
le BLOGGER lui pourra créer des posts en plus de ce que peut faire le USER.
L'ADMIN lui pourra administer les posts et commentaires à travers un dashboard dans lequel il pourra voir les posts et commentaires signalés.

## Installation
Après avoir mis en place le projet et migré la base de donnée, vous devrez vous créer un compte.
Ce compte aura le role USER par défaut, vous devrez donc aller sur l'url ".../routeTestForAdminAccount" pour attribuer à ce compte le role admin. Cette route est utilisée seulement dans la version de développement pour initialiser le premier compte admin. une fois qu'un compte admin est créer, celui ci pourra attribuer ce role à d'autre utilisateurs depuis son dashboard.
Depuis le dashboard administrateur, vous pourrez aussi créer des catégories pour les posts.

