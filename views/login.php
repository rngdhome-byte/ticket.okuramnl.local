<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Okura IT Support Portal</title>
    <link rel="icon" href="<?php echo $logo_img; ?>" type="image/svg+xml">
    <link rel="stylesheet" href="assets/style.css">
    <script>
        const savedTheme = localStorage.getItem('userTheme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <style>
        body { 
            display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; margin: 0; 
            <?php if($bg_img): ?>
                background-image: url('<?php echo $bg_img; ?>');
                background-size: cover; background-position: center; background-attachment: fixed;
            <?php else: ?>
                background-image: radial-gradient(var(--border-color) 1px, transparent 1px); background-size: 30px 30px; 
            <?php endif; ?>
        }
        <?php if($bg_img): ?>
        body::before { content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: var(--bg-overlay); z-index: -2; }
        <?php endif; ?>
        
        .login-wrapper { position: relative; z-index: 1; }
        .login-wrapper::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 300px; background: radial-gradient(circle, rgba(137, 87, 229, 0.15) 0%, transparent 70%); z-index: -1; }
        .login-box { background: var(--card-bg); backdrop-filter: blur(12px); padding: 3rem 2.5rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 15px 35px rgba(0,0,0,0.5); text-align: center; width: 100%; max-width: 420px; }
        .login-box input[type="text"], .login-box input[type="password"] { width: 100%; padding: 0.85rem; margin-bottom: 1.2rem; background-color: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color); border-radius: 6px; outline: none; transition: all 0.2s; font-size: 1rem; box-sizing: border-box;}
        .login-box input[type="text"]:focus, .login-box input[type="password"]:focus { border-color: var(--primary-color); }
        .login-box button { background-color: var(--info-color); color: white; border: none; padding: 0.85rem 1.5rem; border-radius: 6px; font-weight: bold; font-size: 1rem; cursor: pointer; width: 100%; margin-top: 0.5rem; transition: 0.2s; }
        .login-box button:hover { opacity: 0.9; }
        .error { background: rgba(248, 81, 73, 0.1); color: var(--danger-color); border-left: 3px solid var(--danger-color); padding: 0.75rem; margin-bottom: 1.5rem; font-size: 0.9rem; border-radius: 4px; text-align: left; }
        .auth-footer { margin-top: 3rem; text-align: center; color: var(--muted-color); font-size: 0.85rem; z-index: 1; }
        .login-logo { max-width: 80px; max-height: 80px; margin-bottom: 1rem; border-radius: 8px;}
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-box">
            <img src="<?php echo $logo_img; ?>" class="login-logo" alt="Logo">
            <h2 style="color: var(--info-color); margin-top: 0; margin-bottom: 0.5rem; font-weight: 700; font-size: 1.4rem;">Okura IT Support</h2>
            <p style="margin-bottom: 2rem; font-size: 0.85rem; color: var(--muted-color); line-height: 1.5;">
                <?php echo htmlspecialchars($login_banner); ?>
            </p>
            <?php if(isset($error) && $error !== "") echo "<div class='error'>$error</div>"; ?>
            <form method="POST" action="index.php">
                <input type="text" name="username" placeholder="AD Username" required autofocus>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Authenticate</button>
            </form>
        </div>
    </div>
    <div class="auth-footer">&copy; <?php echo date("Y"); ?> IT | Finance Department.</div>
</body>
</html>