</div>
    </div>
    <footer class="dash-footer">
        &copy; <?php echo date("Y"); ?> IT | Finance Department. All rights reserved.<br>
        <span style="font-size: 0.75rem; opacity: 0.7;">System monitored and updated in real-time.</span>
    </footer>
    <script>
        const isITStaff = <?php echo $is_it_staff ? 'true' : 'false'; ?>;
        const isSuperAdmin = <?php echo $is_superadmin ? 'true' : 'false'; ?>;
        const isAdmin = <?php echo $is_admin ? 'true' : 'false'; ?>;
        const canManage = isSuperAdmin || isAdmin;
        const currentUser = "<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>";
        const currentDisplayName = "<?php echo addslashes($_SESSION['displayname'] ?? $_SESSION['username'] ?? ''); ?>";
        
        let appSettings = <?php 
            $s = @file_get_contents('log_settings.json'); 
            echo ($s && json_decode($s)) ? $s : '{}'; 
        ?>;
        let userProfiles = <?php 
            $p = @file_get_contents('profiles.json'); 
            echo ($p && json_decode($p)) ? $p : '{}'; 
        ?>;
        let activityLogs = []; 
    </script>
    <script src="assets/app.js?v=<?php echo time(); ?>"></script>
    <script>
        if (typeof fetchLogsFromDB === "function") {
            fetchLogsFromDB();
        } else {
            console.error("app.js failed to load properly. Check file paths and syntax.");
        }
    </script>
</body>
</html>