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
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { 
            <?php if($bg_img): ?>
                background-image: url('<?php echo $bg_img; ?>'); background-size: cover; background-position: center; background-attachment: fixed;
            <?php endif; ?>
        }
        <?php if($bg_img): ?>
        body::before { content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: var(--bg-overlay); z-index: -2; }
        <?php endif; ?>
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <header>
                <div class="header-left">
                    <img src="<?php echo $logo_img; ?>" class="header-logo" alt="Logo">
                    <div class="header-titles">
                        <?php if ($is_it_staff): ?>
                            <h1>HOR Support Operations Log Center</h1>
                        <?php else: ?>
                            <h1>My Support Requests</h1>
                            <div class="header-nav">
                                <a class="nav-link active" id="tabNewTicket" onclick="switchClientView('new')">Submit Ticket</a>
                                <a class="nav-link" id="tabMyTickets" onclick="switchClientView('tickets')">My Tickets</a>
                                <a class="nav-link" onclick="openKBModal(event)">Knowledge Base</a>
                                <a class="nav-link" onclick="openFormsModal(event)">IT Forms</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="header-right" style="flex-direction: column; align-items: flex-end; gap: 0.8rem;">
                    
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; flex-wrap: wrap; width: 100%;">
                        
                        <div style="display: flex; align-items: center; justify-content: center; text-align: right; padding-right: 0.5rem; border-right: 1px solid var(--border-color);">
                            
                            <label for="profileUpload" class="avatar-upload-label" title="Click to upload profile picture" style="margin-right: 0.8rem; flex-shrink: 0;">
                                <img id="headerAvatar" class="avatar-img" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%238b949e'><path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/></svg>">
                            </label>
                            <input type="file" id="profileUpload" style="display:none;" accept="image/*" onchange="uploadProfilePic(event)">

                            <span class="welcome-text"><?php echo htmlspecialchars($current_displayname); ?></span>
                            <?php if ($is_superadmin): ?>
                                <span class="role-badge role-superadmin" style="margin-left:8px;">Super Admin</span>
                            <?php elseif ($is_admin): ?>
                                <span class="role-badge role-admin" style="margin-left:8px;">Admin</span>
                            <?php elseif ($is_it_staff): ?>
                                <span class="role-badge" style="margin-left:8px;">IT Staff</span>
                            <?php else: ?>
                                <span class="role-badge role-client" style="margin-left:8px;">End-User</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="dropdown" style="margin-left: 0.5rem;">
                            <button class="btn-outline dropdown-toggle" onclick="toggleDropdown(event, 'userThemeDropdown')" style="border: none; background: transparent; padding: 0.5rem; font-size: 1.2rem;" title="Change Theme">&#127912;</button>
                            <div id="userThemeDropdown" class="dropdown-content" style="min-width: 140px; margin-top: 10px;">
                                <button type="button" onclick="setUserTheme('light')">&#9728;&#65039; Light Mode</button>
                                <button type="button" onclick="setUserTheme('dark')">&#127769; Dark Mode</button>
                                <button type="button" onclick="setUserTheme('colorful')">&#10024; Colorful</button>
                            </div>
                        </div>

                        <a href="?logout=true" class="btn-outline-logout">Log Out</a>
                    </div>

                    <?php if ($is_it_staff): ?>
                    <div class="header-nav" style="margin-top: 0; justify-content: flex-end; width: 100%;">
                        <a class="nav-link active" id="mainTab-dashboard" onclick="switchITMainTab('dashboard')">Dashboard</a>
                        <a class="nav-link" id="mainTab-tickets" onclick="switchITMainTab('tickets')">Tickets</a>
                        <a class="nav-link" id="mainTab-reports" onclick="switchITMainTab('reports')">Reports</a>
                        <a class="nav-link" id="mainTab-settings" onclick="switchITMainTab('settings')">Settings</a>
                    </div>
                    <?php endif; ?>

                    <div style="width: 100%; text-align: right; margin-top: -0.2rem;">
                        <span id="liveClock" style="font-size: 0.85rem; color: var(--muted-color); font-family: monospace;"></span>
                    </div>

                </div>
            </header>