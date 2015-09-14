<?php

// Home page
$app->match('/',"WebLinks\\Controller\\HomeController::indexAction")->bind('home');

// Login 
$app->get('/login',"WebLinks\\Controller\\HomeController::loginAction")->bind('login');

//New link form
$app->match('/link',"WebLinks\\Controller\\HomeController::linkAction")->bind('addlink');

// Admin space
$app->match('/admin',"WebLinks\\Controller\\AdminController::indexAction")->bind('admin');

// Edit link page
$app->match('/admin/link/edit/{id}',"WebLinks\\Controller\\AdminController::editLinkAction")->bind('admin_edit_link');

// Delete a link
$app->get('/admin/link/delete/{id}',"WebLinks\\Controller\\AdminController::deleteLinkAction")->bind('admin_delete_link');

// Edit an existing user
$app->match('/admin/user/edit/{id}', "WebLinks\\Controller\\AdminController::editUserAction")->bind('admin_edit_user');

// Remove a user
$app->get('/admin/user/delete/{id}', "WebLinks\\Controller\\AdminController::deleteUserAction")->bind('admin_delete_user');

// API: Get all links
$app->get('/api/links', "WebLinks\\Controller\\APIController::linksAction");

// API: Watch link details
$app->get('/api/link/{id}',"WebLinks\\Controller\APIController::linkAction");
