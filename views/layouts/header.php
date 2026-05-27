<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>public/css/print.css" media="print">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?php echo $title ?? APP_NAME; ?></h1>
                </div>
                <div class="top-bar-right">
                    <span class="user-badge">
                        <i class="fas fa-user-md"></i> Laboratorista
                    </span>
                </div>
            </header>

            <!-- Contenido de la página -->
            <div class="page-content">
                <!-- Las notificaciones del servidor se transformarán en toasts -->
                <?php if (isset($_SESSION['mensaje']) || isset($_SESSION['error']) || isset($_SESSION['errores'])): ?>
                    <script>
                        window.toastMessages = window.toastMessages || [];
                        <?php if (isset($_SESSION['mensaje'])): ?>
                            window.toastMessages.push({
                                type: 'success',
                                text: <?php echo json_encode($_SESSION['mensaje']); ?>
                            });
                            <?php unset($_SESSION['mensaje']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                            window.toastMessages.push({
                                type: 'error',
                                text: <?php echo json_encode($_SESSION['error']); ?>
                            });
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['errores'])): ?>
                            <?php foreach ($_SESSION['errores'] as $err): ?>
                                window.toastMessages.push({
                                    type: 'error',
                                    text: <?php echo json_encode($err); ?>
                                });
                            <?php endforeach; ?>
                            <?php unset($_SESSION['errores']); ?>
                        <?php endif; ?>
                    </script>
                <?php endif; ?>