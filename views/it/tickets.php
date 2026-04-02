<div id="itMainView-tickets" class="it-main-view" style="display:none;">
    
    <div id="itDashboardView">
        
        <div class="summary-stats" style="margin-bottom: 1.5rem;">
            <div class="stat-card" style="border-top: 3px solid var(--primary-color);">
                <h3>Filtered Tickets</h3>
                <div class="number" id="countTotal" style="color: var(--primary-color);">0</div>
            </div>
            <div class="stat-card" style="border-top: 3px solid var(--danger-color);">
                <h3>New / Open</h3>
                <div class="number" id="countOpen" style="color: var(--danger-color);">0</div>
            </div>
            <div class="stat-card" style="border-top: 3px solid var(--warning-color);">
                <h3>Pending / Hold</h3>
                <div class="number" id="countPending" style="color: var(--warning-color);">0</div>
            </div>
            <div class="stat-card" style="border-top: 3px solid var(--success-color);">
                <h3>Resolved / Closed</h3>
                <div class="number" id="countResolved" style="color: var(--success-color);">0</div>
            </div>
        </div>

        <div style="display:flex; height: 12px; border-radius: 6px; overflow:hidden; margin-bottom: 2rem; background: var(--border-color); border: 1px solid var(--border-color);">
            <div id="metricResolved" style="background: var(--success-color); width: 0%; transition: width 0.5s;" title="Resolved / Closed"></div>
            <div id="metricInProgress" style="background: var(--danger-color); width: 0%; transition: width 0.5s;" title="New / Open"></div>
            <div id="metricPending" style="background: var(--warning-color); width: 0%; transition: width 0.5s;" title="Pending / Hold"></div>
        </div>

        <div class="controls" style="background: var(--card-bg); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
            <div style="display: flex; gap: 1rem; width: 100%; align-items: center; flex-wrap: wrap;">
                <button class="btn-submit" onclick="switchITView('create')" style="margin-top:0;">&#10133; Create Ticket</button>
                <input type="text" id="searchInput" placeholder="Search logs by keyword, ticket #, title, department..." style="flex: 2; min-width: 200px; margin:0;">
                
                <select id="filterAssigned" style="flex: 1; min-width: 180px; margin:0;">
                    <option value="Me" selected>Assigned to Me & Unassigned</option>
                    <option value="All">All Tickets</option>
                </select>
                
                <select id="filterStatus" style="flex: 1; min-width: 120px; margin:0;">
                    <option value="Open" selected>Open Tickets</option>
                    <option value="Closed">Closed Tickets</option>
                </select>

                <select id="filterUser" style="flex: 1; min-width: 120px; margin:0;">
                    <option value="All">All Users</option>
                </select>

                <select id="filterCategory" style="flex: 1; min-width: 120px; margin:0;">
                    <option value="All" selected>All Types</option>
                    <option value="Support">Support Tickets</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Update">System Updates</option>
                    <option value="Note">General Notes</option>
                </select>
                
                <button class="btn-outline" onclick="resetFilters()" title="Clear all filters" style="margin:0;">&#8634; Reset</button>
            </div>
        </div>

        <div class="log-feed" id="logFeed"></div>
    </div>

    <div id="formContainer" style="display:none;">
        <div class="log-form-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem;">
                <h3 style="margin:0; color:var(--primary-color);">Log New Support Action / Ticket</h3>
                <button class="btn-outline" onclick="switchITView('dashboard')">&#8592; Back to List</button>
            </div>
            <form id="addLogForm">
                <input type="hidden" id="editId">
                <div style="background: var(--input-bg); padding: 1rem; border-radius: 6px; border: 1px solid var(--border-color); margin-bottom: 1.2rem;">
                    <h4 style="margin-top: 0; margin-bottom: 0.8rem; font-size: 0.9rem; color: var(--muted-color);">User / Requestor Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Requestor Type *</label>
                            <select id="logRequestor" required onchange="updateDepartmentDropdown()">
                                <option value="" disabled selected>Select Type...</option>
                                <option value="Employee">&#128100; Employee</option>
                                <option value="Guest">&#128716; Guest (Room #)</option>
                                <option value="N/A">N/A (Internal IT)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department / Area *</label>
                            <select id="logDepartment" required disabled onchange="updateSpecificRequestor()">
                                <option value="" disabled selected>Waiting for Type...</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Specific Requestor Name / Room * <a href="#" id="manualToggleBtn" onclick="toggleManualRequestor(event)" style="float:right; font-size:0.75rem; color:var(--primary-color);">Manual Entry</a>
                            </label>
                            <select id="logRequestorSub" required disabled>
                                <option value="" disabled selected>Waiting for Dept...</option>
                            </select>
                            <input type="text" id="logRequestorSubManual" placeholder="Type name manually..." style="display:none;">
                        </div>
                        <div class="form-group">
                            <label>Job Title (Optional)</label>
                            <input type="text" id="logJobTitle" placeholder="e.g., Manager, Supervisor...">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Log Type *</label>
                        <select id="logType" required>
                            <option value="Support">&#128680; Support Ticket</option>
                            <option value="Maintenance">&#128295; Maintenance</option>
                            <option value="Update">&#9989; System Update</option>
                            <option value="Note">&#128221; General Note</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select id="logStatus" required>
                            <option value="New">&#128309; New</option>
                            <option value="Open">&#128993; Open</option>
                            <option value="Pending">&#128308; Pending</option>
                            <option value="Hold">&#9888; Hold</option>
                            <option value="Resolved">&#128994; Resolved</option>
                            <option value="Closed">&#9899; Closed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Concern Category *</label>
                        <select id="logConcern" required onchange="updateSubCategory()">
                            <option value="" disabled selected>Select Category...</option>
                            <option value="Hardware">&#128187; Hardware</option>
                            <option value="Software">&#128190; Software</option>
                            <option value="Network">&#127760; Network</option>
                            <option value="System Access">&#128273; System Access</option>
                            <option value="Other">&#128204; Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sub Category *</label>
                        <select id="logSubCategory" required disabled>
                            <option value="" disabled selected>Waiting for Category...</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full">
                        <label>Task / Issue Title *</label>
                        <input type="text" id="logTitle" placeholder="e.g., POS Terminal Restarted, Room 501 Wi-Fi Issue..." required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date Logged *</label>
                        <input type="date" id="logDate" required onclick="this.showPicker()">
                    </div>
                    <div class="form-group">
                        <label>Time Logged *</label>
                        <input type="time" id="logTime" required onclick="this.showPicker()">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full">
                        <label>Original Request / Details *</label>
                        <textarea id="logMessage" placeholder="Describe the action taken, cause of the issue, or notes for the team..." required></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full">
                        <label>Attach Screenshot / Document (Optional)</label>
                        <input type="file" id="clientAttachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit" id="saveLogBtn">+ Save Log Entry</button>
                    <button type="button" class="btn-cancel-edit" id="cancelEditBtn" onclick="cancelEdit()">Cancel Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>