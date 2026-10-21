<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle ?? 'POS System') ?> · IT0049 POS</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
<?php if (session()->get('user_id')): ?>
<header class="topbar">
    <a class="brand" href="<?= site_url('dashboard') ?>">IT0049 <span>POS</span></a>
    <nav>
        <a href="<?= site_url('dashboard') ?>">Dashboard</a>
        <a href="<?= site_url('products') ?>">Products</a>
        <a href="<?= site_url('customers') ?>">Customers</a>
        <a href="<?= site_url('staff') ?>">Staff</a>
        <a href="<?= site_url('sales/create') ?>">Record Sale</a>
        <a href="<?= site_url('sales/history') ?>">Sales History</a>
    </nav>
    <div class="user-menu">
        <span><?= esc(session()->get('full_name')) ?></span>
        <a class="logout" href="<?= site_url('logout') ?>">Log out</a>
    </div>
</header>
<?php endif; ?>
<main class="<?= session()->get('user_id') ? 'container' : 'auth-container' ?>">
    <?php if ($message = session()->getFlashdata('success')): ?><div class="alert success"><?= esc($message) ?></div><?php endif; ?>
    <?php if ($message = session()->getFlashdata('error')): ?><div class="alert error"><?= esc($message) ?></div><?php endif; ?>
    <?php if (isset($validation) && $validation->getErrors()): ?>
        <div class="alert error"><strong>Please fix the following:</strong><ul><?php foreach ($validation->getErrors() as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
