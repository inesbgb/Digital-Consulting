<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index',],
    'apropos' => ['HomeController', 'apropos',],
    'signout' => ['HomeController', 'signout',],
    'createAd' => ['AnnonceController','createAd',],
    'designAndCreation' => ['AnnonceController','designAndCreation',],
    'myAds' => ['AnnonceController','myAds',],
    'MyOrders' => ['AnnonceController','MyOrders',],
    'theAds' => ['AnnonceController','theAds', ['service']],
    'Annonce/info' => ['AnnonceController','info',],
    'Annonce/edit' => ['AnnonceController','edit',['id']],
    'Annonce/delete' => ['AnnonceController','delete',['id']],
    'Annonce/contact' => ['AnnonceController','contact',],
    'mailbox' => ['MailboxController','mailbox',],
    'createTchat' => ['MailboxController','createTchat',['id']],
    'tchatShow' => ['MailboxController','tchatShow',['id']],
    'Bill' => ['BillController','index',],
    //on recupere l'id de l'annonce
    'Bill/add' => ['BillController','add',['id']],
    'Bill/order' => ['BillController','order',],

    'createProfil' => ['UserController','createProfil',['role']],
    'login' => ['UserController','login',],
    'profil' => ['UserController','profil',],
    'signin' => ['UserController','signin',],
    'logout' => ['UserController','logout',],
];
