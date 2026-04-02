<div id="newTicketView">
    <div class="log-form-card" id="formContainer" style="border-color: var(--success-color);">
        <div style="background: rgba(46, 160, 67, 0.1); border: 1px solid rgba(46, 160, 67, 0.3); padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 150px;">
                <p style="margin: 0; font-size: 0.85rem; color: var(--muted-color); text-transform: uppercase;">Requestor</p>
                <strong style="color: var(--text-color); font-size: 1.1rem;"><?php echo htmlspecialchars($_SESSION['displayname'] ?? ''); ?></strong>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <p style="margin: 0; font-size: 0.85rem; color: var(--muted-color); text-transform: uppercase;">Job Title</p>
                <strong style="color: var(--text-color); font-size: 1.1rem;"><?php echo htmlspecialchars($_SESSION['job_title'] ?? ''); ?></strong>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <p style="margin: 0; font-size: 0.85rem; color: var(--muted-color); text-transform: uppercase;">Department</p>
                <strong style="color: var(--text-color); font-size: 1.1rem;"><?php echo htmlspecialchars($_SESSION['department'] ?? ''); ?></strong>
            </div>
        </div>

        <h3 style="margin-top:0; color:var(--success-color);">Submit a New Ticket</h3>
        <form id="addLogForm">
            <input type="hidden" id="editId">
            <input type="hidden" id="logDate">
            <input type="hidden" id="logTime">
            <input type="hidden" id="logRequestor" value="Employee">
            <input type="hidden" id="logDepartment" value="<?php echo htmlspecialchars($_SESSION['department'] ?? ''); ?>">
            <input type="hidden" id="logRequestorSub" value="<?php echo htmlspecialchars($_SESSION['displayname'] ?? ''); ?>">
            <input type="hidden" id="logJobTitle" value="<?php echo htmlspecialchars($_SESSION['job_title'] ?? ''); ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>What do you need help with? *</label>
                    <select id="logConcern" required onchange="updateSubCategory()">
                        <option value="" disabled selected>Select Category...</option>
                        <option value="Hardware">&#128187; Hardware / Equipment</option>
                        <option value="Software">&#128190; Software / Application</option>
                        <option value="Network">&#127760; Network / Internet</option>
                        <option value="System Access">&#128273; Passwords / Access</option>
                        <option value="Other">&#128204; Other Inquiry</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Specific Issue *</label>
                    <select id="logSubCategory" required disabled>
                        <option value="" disabled selected>Waiting for Category...</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full">
                    <label>Brief Title *</label>
                    <input type="text" id="logTitle" placeholder="e.g., Cannot connect to Wi-Fi, Need password reset..." required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group full">
                    <label>Detailed Description *</label>
                    <textarea id="logMessage" placeholder="Please describe your issue in detail so IT can assist you..." required></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full">
                    <label>Attach Screenshot / Document (Optional)</label>
                    <input type="file" id="clientAttachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit" id="saveLogBtn" style="background-color: var(--success-color);">Send to IT Support</button>
            </div>
        </form>
    </div>
</div>

<div id="myTicketsView" style="display:none;">
    <div class="controls">
        <h3 style="margin: 0; color: var(--success-color);">My Active & Past Tickets</h3>
        <div class="filter-group">
            <select id="filterStatus">
                <option value="Active" selected>Active Tickets</option>
                <option value="All">All (Incl. Closed)</option>
                <option value="New">New</option>
                <option value="Open">Open</option>
                <option value="Pending">Pending</option>
                <option value="Hold">Hold</option>
                <option value="Resolved">Resolved</option>
                <option value="Closed">Closed</option>
            </select>
            <button class="btn-outline" onclick="fetchLogsFromDB()" title="Refresh">&#8635; Refresh</button>
        </div>
    </div>
    <div class="log-feed" id="logFeed"></div>
</div>

<?php require_once 'views/modals/respond_modal.php'; ?>
<?php require_once 'views/modals/kb_modal.php'; ?>
<?php require_once 'views/modals/forms_modal.php'; ?>