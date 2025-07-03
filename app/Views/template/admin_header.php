<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css');?>">
</head>
<body>
<div id="container">
    <header>
        <h1>Halaman Admin</h1>
    </header>
    <nav>
        <a href="<?= base_url('/admin/artikel'); ?>" class="active">Data Artikel</a>
        <a href="<?= base_url('/'); ?>">Lihat Website</a>
        <a href="#">Logout</a>
    </nav>
    <section id="wrapper">
        <section id="main">
