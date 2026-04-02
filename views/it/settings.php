<div id="itMainView-settings" class="it-main-view" style="display:none;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div class="log-form-card" style="text-align:center;">
            <h3 style="color:var(--text-color);">&#128994; Active Users</h3>
            <p style="color:var(--muted-color); font-size:0.85rem; margin-bottom:1rem;">View all end-users currently online.</p>
            <button class="btn-outline" style="width:100%; border-color:var(--primary-color);" onclick="openOnlineUsersModal(event)">View Online Users</button>
        </div>
        <?php if (isset($can_manage) && $can_manage): ?>
        <div class="log-form-card" style="text-align:center;">
            <h3 style="color:var(--text-color);">&#128194; Organization Directory</h3>
            <p style="color:var(--muted-color); font-size:0.85rem; margin-bottom:1rem;">Manage departments, employees, and rooms.</p>
            <button class="btn-outline" style="width:100%; border-color:var(--primary-color);" onclick="openSettingsModal(event)">Manage Directory</button>
        </div>
        <?php endif; ?>
        <?php if (isset($is_superadmin) && $is_superadmin): ?>
        <div class="log-form-card" style="text-align:center;">
            <h3 style="color:var(--text-color);">&#128187; Portal Customization</h3>
            <p style="color:var(--muted-color); font-size:0.85rem; margin-bottom:1rem;">Manage Knowledge Base and IT Forms.</p>
            <button class="btn-outline" style="width:100%; border-color:var(--primary-color);" onclick="openEndUserSettingsModal(event)">Edit Portal</button>
        </div>
        <div class="log-form-card" style="text-align:center;">
            <h3 style="color:var(--text-color);">&#127912; Appearance & Branding</h3>
            <p style="color:var(--muted-color); font-size:0.85rem; margin-bottom:1rem;">Change Logos, Backgrounds, and Banners.</p>
            <button class="btn-outline" style="width:100%; border-color:var(--primary-color);" onclick="openThemeModal(event)">Theme Settings</button>
        </div>
        <div class="log-form-card" style="text-align:center;">
            <h3 style="color:var(--text-color);">&#128270; Security Logs</h3>
            <p style="color:var(--muted-color); font-size:0.85rem; margin-bottom:1rem;">View all Active Directory authentication attempts.</p>
            <button class="btn-outline" style="width:100%; border-color:var(--warning-color);" onclick="openAuthLogsModal(event)">View Security Logs</button>
        </div>
        <div class="log-form-card" style="text-align:center; border-color:var(--danger-color);">
            <h3 style="color:var(--danger-color);">&#128465; Database Management</h3>
            <p style="color:var(--muted-color); font-size:0.85rem; margin-bottom:1rem;">Permanently delete all ticket history.</p>
            <button class="btn-danger-outline" style="text-align:center;" onclick="clearAllLogs(event)">Purge Database</button>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-overlay" id="onlineUsersModal">
    <div class="modal" style="max-width: 600px;">
        <div class="modal-header">
            <h2>&#128994; Online Users</h2>
            <button type="button" class="close-btn" onclick="closeOnlineUsersModal()">&times;</button>
        </div>
        <p style="margin-top:0; margin-bottom:1rem; color: var(--muted-color); font-size:0.85rem;">Showing end-users currently online.</p>
        <ul class="mng-list" id="onlineUsersList">
            <li>Loading active users...</li>
        </ul>
    </div>
</div>

<?php if (isset($can_manage) && $can_manage): ?>
<div class="modal-overlay" id="settingsModal">
    <div class="modal">
        <div class="modal-header">
            <h2>Organization Directory</h2>
            <button type="button" class="close-btn" onclick="closeSettingsModal()">&times;</button>
        </div>
        <div class="dir-split">
            <div class="dir-section" style="border-right: 1px solid var(--border-color); padding-right: 1.5rem;">
                <h3>&#10133; Add New Entry</h3>
                <form id="settingsForm">
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label>Requestor Type</label>
                        <select id="setReqType" required>
                            <option value="Employee">Employee</option>
                            <option value="Guest">Guest Room</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label>Department / Area</label>
                        <input type="text" id="setDept" placeholder="e.g., Finance, Front Office" list="existingDepts" required>
                        <datalist id="existingDepts"></datalist>
                    </div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label>Specific Name / Room #</label>
                        <input type="text" id="setSpecific" placeholder="e.g., Juan Dela Cruz, or 1040" required>
                    </div>
                    <button type="submit" class="btn-submit" style="width: 100%;">Add to Directory</button>
                </form>
            </div>
            <div class="dir-section">
                <h3>&#128736; Modify Existing</h3>
                <div class="form-group">
                    <label>Select Type</label>
                    <select id="mngType" onchange="renderMngDepts()">
                        <option value="Employee">Employee</option>
                        <option value="Guest">Guest Room</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="dept-header-actions">
                        <label style="margin:0;">Select Department</label>
                        <div id="deptActionContainer">
                            <button type="button" class="mng-btn" onclick="renameDirDept()" title="Rename Department">&#9999;</button>
                            <?php if (isset($is_superadmin) && $is_superadmin): ?>
                                <button type="button" class="mng-btn del" onclick="deleteDirDept()" title="Delete Department">&#128465;</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <select id="mngDept" onchange="renderMngItems()">
                        <option value="" disabled selected>Waiting...</option>
                    </select>
                </div>
                <label style="margin-top: 0.5rem;">Members / Rooms</label>
                <ul class="mng-list" id="mngItemList"></ul>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($is_superadmin) && $is_superadmin): ?>
<div class="modal-overlay" id="endUserSettingsModal">
    <div class="modal" style="max-width: 1000px; border-color: var(--success-color);">
        <div class="modal-header">
            <h2>&#128187; Portal Customization</h2>
            <button type="button" class="close-btn" onclick="closeEndUserSettingsModal()">&times;</button>
        </div>
        <div class="dir-split">
            <div class="dir-section" style="border-right: 1px solid var(--border-color); padding-right: 1.5rem;">
                <h3 style="color: var(--primary-color);">&#128218; Manage Knowledge Base</h3>
                <input type="hidden" id="euKbEditId">
                <input type="text" id="euKbTitle" placeholder="Article Title" style="width: 100%; margin-bottom: 0.5rem; background: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 4px;">
                <select id="euKbCategory" style="width: 100%; margin-bottom: 0.5rem; background: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 4px;">
                    <option value="Applications">Applications</option>
                    <option value="Network and Internet">Network and Internet</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Rooms Entertainment">Rooms Entertainment</option>
                    <option value="System Access">System Access</option>
                </select>
                <textarea id="euKbContent" placeholder="Article Content (HTML allowed)..." style="width: 100%; margin-bottom: 0.5rem; min-height: 100px; background: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 4px;"></textarea>
                <label style="font-size:0.8rem; color:var(--muted-color);">Attach File (PDF/Doc) Optional:</label>
                <input type="file" id="euKbFile" accept=".pdf,.doc,.docx,.txt" style="width: 100%; margin-bottom: 0.5rem;">
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" class="btn-outline" onclick="saveEUKB()" id="euKbSaveBtn">&#10133; Add Article</button>
                </div>
                <ul class="mng-list" id="euKbList" style="margin-top: 1rem;"></ul>
            </div>
            <div class="dir-section">
                <h3 style="color: var(--primary-color);">&#128221; Manage IT Forms</h3>
                <input type="hidden" id="euFormEditId">
                <input type="text" id="euFormTitle" placeholder="Form Display Title" style="width: 100%; margin-bottom: 0.5rem; background: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 4px;">
                <input type="file" id="euFormFile" accept=".pdf,.doc,.docx" style="width: 100%; margin-bottom: 0.5rem;">
                <small style="color: var(--muted-color); display:block; margin-bottom:0.5rem;" id="euFormFileHelp">Select a file to upload. Leave blank when editing to keep the existing file.</small>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" class="btn-outline" onclick="saveEUForm()" id="euFormSaveBtn">&#10133; Add Form</button>
                </div>
                <ul class="mng-list" id="euFormList" style="margin-top: 1rem;"></ul>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="themeModal">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h2>&#127912; Appearance & Branding</h2>
            <button type="button" class="close-btn" onclick="closeThemeModal()">&times;</button>
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed var(--border-color);">
            <label>Login Portal Banner Text</label>
            <textarea id="themeLoginBanner" style="width:100%; min-height:60px; background: var(--input-bg); color: var(--text-color); padding: 0.5rem; border: 1px solid var(--border-color); border-radius:4px;" placeholder="Sign in with your standard network credentials..."></textarea>
            <button type="button" class="btn-outline" style="margin-top:0.5rem;" onclick="saveThemeBanner()">Update Banner</button>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label>Update Custom Logo (PNG/JPG)</label>
            <input type="file" id="uploadLogoBtn" accept="image/*" onchange="uploadThemeImage(event, 'logoImage', 300)" style="background: transparent; border: none; padding: 0;">
            <p style="font-size: 0.8rem; color: var(--muted-color); margin-top: 0.2rem;">Will appear on the login screen, dashboard header, and tab bar.</p>
        </div>
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label>Update Background Image (JPG)</label>
            <input type="file" id="uploadBgBtn" accept="image/*" onchange="uploadThemeImage(event, 'bgImage', 1920)" style="background: transparent; border: none; padding: 0;">
            <p style="font-size: 0.8rem; color: var(--muted-color); margin-top: 0.2rem;">A theme-aware overlay will automatically be applied so cards remain readable.</p>
        </div>
        <button type="button" class="btn-danger-outline" onclick="resetTheme()" style="width: 100%; margin-top: 1rem;">Reset Theme to Defaults</button>
    </div>
</div>

<div class="modal-overlay" id="authLogsModal">
    <div class="modal" style="max-width: 800px;">
        <div class="modal-header">
            <h2>Security Logs</h2>
            <button type="button" class="close-btn" onclick="closeAuthLogsModal()">&times;</button>
        </div>
        <div class="terminal" id="authTerminalOutput">
            Loading logs...
        </div>
    </div>
</div>
<?php endif; ?>