// ==========================================
// DYNAMIC MODAL INJECTOR (Bypasses Caches and Native Alerts)
// ==========================================
function injectModernModals() {
    if(document.getElementById('modernAppModalOverlay')) return;
    const modalHTML = `
    <div id="modernAppModalOverlay" class="custom-overlay-bg" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);display:none;justify-content:center;align-items:center;z-index:9999;opacity:0;transition:opacity 0.3s ease;">
        <div class="custom-alert-box" style="background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;padding:2.5rem;max-width:400px;width:90%;text-align:center;box-shadow:0 15px 40px rgba(0,0,0,0.5);transform:scale(0.7);opacity:0;transition:all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);">
            <div id="modernAppModalIcon" style="font-size:3.5rem;margin-bottom:1rem;color:var(--success-color);line-height:1;">&#10004;</div>
            <h3 id="modernAppModalMessage" style="color:var(--text-color);margin-top:0;margin-bottom:1.5rem;font-size:1.1rem;line-height:1.5;font-weight:500;">Message</h3>
            <button class="custom-alert-btn" onclick="closeModernAppModal()" style="background:var(--primary-color);color:white;border:none;padding:0.8rem 2rem;border-radius:6px;font-weight:bold;cursor:pointer;font-size:1rem;transition:0.2s;box-shadow:0 4px 10px rgba(0,0,0,0.2);width:100%;">OK</button>
        </div>
    </div>
    
    <div id="modernAppConfirmOverlay" class="custom-overlay-bg" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);display:none;justify-content:center;align-items:center;z-index:9999;opacity:0;transition:opacity 0.3s ease;">
        <div class="custom-alert-box" style="background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;padding:2.5rem;max-width:400px;width:90%;text-align:center;box-shadow:0 15px 40px rgba(0,0,0,0.5);transform:scale(0.7);opacity:0;transition:all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);">
            <div style="font-size:3.5rem;margin-bottom:1rem;color:var(--warning-color);line-height:1;">&#9888;</div>
            <h3 id="modernAppConfirmMessage" style="color:var(--text-color);margin-top:0;margin-bottom:1.5rem;font-size:1.1rem;line-height:1.5;font-weight:500;">Are you sure?</h3>
            <div style="display:flex;gap:1rem;justify-content:center;margin-top:1.5rem;">
                <button class="custom-alert-btn" id="modernAppConfirmNo" style="background:var(--input-bg);color:var(--text-color);border:1px solid var(--border-color);padding:0.8rem 2rem;border-radius:6px;font-weight:bold;cursor:pointer;font-size:1rem;transition:0.2s;flex:1;">Cancel</button>
                <button class="custom-alert-btn" id="modernAppConfirmYes" style="background:var(--danger-color);color:white;border:none;padding:0.8rem 2rem;border-radius:6px;font-weight:bold;cursor:pointer;font-size:1rem;transition:0.2s;box-shadow:0 4px 10px rgba(0,0,0,0.2);flex:1;">Proceed</button>
            </div>
        </div>
    </div>
    
    <div id="modernAppPromptOverlay" class="custom-overlay-bg" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);display:none;justify-content:center;align-items:center;z-index:9999;opacity:0;transition:opacity 0.3s ease;">
        <div class="custom-alert-box" style="background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;padding:2.5rem;max-width:400px;width:90%;text-align:center;box-shadow:0 15px 40px rgba(0,0,0,0.5);transform:scale(0.7);opacity:0;transition:all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);">
            <div style="font-size:3.5rem;margin-bottom:1rem;color:var(--primary-color);line-height:1;">&#9999;</div>
            <h3 id="modernAppPromptMessage" style="color:var(--text-color);margin-top:0;margin-bottom:1.5rem;font-size:1.1rem;line-height:1.5;font-weight:500;">Enter value:</h3>
            <input type="text" id="modernAppPromptInput" style="width:100%;text-align:center;font-size:1.1rem;margin-bottom:1.5rem;background:var(--input-bg);color:var(--text-color);padding:0.75rem;border:1px solid var(--border-color);border-radius:6px;outline:none;">
            <div style="display:flex;gap:1rem;justify-content:center;">
                <button class="custom-alert-btn" id="modernAppPromptNo" style="background:var(--input-bg);color:var(--text-color);border:1px solid var(--border-color);padding:0.8rem 2rem;border-radius:6px;font-weight:bold;cursor:pointer;font-size:1rem;transition:0.2s;flex:1;">Cancel</button>
                <button class="custom-alert-btn" id="modernAppPromptYes" style="background:var(--primary-color);color:white;border:none;padding:0.8rem 2rem;border-radius:6px;font-weight:bold;cursor:pointer;font-size:1rem;transition:0.2s;box-shadow:0 4px 10px rgba(0,0,0,0.2);flex:1;">Save</button>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}
document.addEventListener("DOMContentLoaded", injectModernModals);

window.showAppModal = function(message, isSuccess = true) {
    injectModernModals();
    document.getElementById('modernAppModalMessage').innerText = message;
    document.getElementById('modernAppModalIcon').innerHTML = isSuccess ? '&#10004;' : '&#9888;';
    document.getElementById('modernAppModalIcon').style.color = isSuccess ? 'var(--success-color)' : 'var(--danger-color)';
    
    const overlay = document.getElementById('modernAppModalOverlay');
    overlay.style.display = 'flex';
    setTimeout(() => {
        overlay.classList.add('show');
        overlay.querySelector('.custom-alert-box').style.transform = 'scale(1)';
        overlay.querySelector('.custom-alert-box').style.opacity = '1';
    }, 10);
};

window.closeModernAppModal = function() {
    const overlay = document.getElementById('modernAppModalOverlay');
    overlay.classList.remove('show');
    overlay.querySelector('.custom-alert-box').style.transform = 'scale(0.7)';
    overlay.querySelector('.custom-alert-box').style.opacity = '0';
    setTimeout(() => overlay.style.display = 'none', 300); 
};

window.showAppConfirm = function(message) {
    injectModernModals();
    return new Promise((resolve) => {
        document.getElementById('modernAppConfirmMessage').innerText = message;
        const overlay = document.getElementById('modernAppConfirmOverlay');
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.classList.add('show');
            overlay.querySelector('.custom-alert-box').style.transform = 'scale(1)';
            overlay.querySelector('.custom-alert-box').style.opacity = '1';
        }, 10);

        const btnYes = document.getElementById('modernAppConfirmYes');
        const btnNo = document.getElementById('modernAppConfirmNo');

        const cleanup = () => {
            btnYes.replaceWith(btnYes.cloneNode(true));
            btnNo.replaceWith(btnNo.cloneNode(true));
            overlay.classList.remove('show');
            overlay.querySelector('.custom-alert-box').style.transform = 'scale(0.7)';
            overlay.querySelector('.custom-alert-box').style.opacity = '0';
            setTimeout(() => overlay.style.display = 'none', 300);
        };

        document.getElementById('modernAppConfirmYes').addEventListener('click', () => { cleanup(); resolve(true); });
        document.getElementById('modernAppConfirmNo').addEventListener('click', () => { cleanup(); resolve(false); });
    });
};

window.showAppPrompt = function(message, defaultValue = "") {
    injectModernModals();
    return new Promise((resolve) => {
        document.getElementById('modernAppPromptMessage').innerText = message;
        const input = document.getElementById('modernAppPromptInput');
        input.value = defaultValue;
        
        const overlay = document.getElementById('modernAppPromptOverlay');
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.classList.add('show');
            overlay.querySelector('.custom-alert-box').style.transform = 'scale(1)';
            overlay.querySelector('.custom-alert-box').style.opacity = '1';
            input.focus();
        }, 10);

        const btnYes = document.getElementById('modernAppPromptYes');
        const btnNo = document.getElementById('modernAppPromptNo');

        const cleanup = () => {
            btnYes.replaceWith(btnYes.cloneNode(true));
            btnNo.replaceWith(btnNo.cloneNode(true));
            overlay.classList.remove('show');
            overlay.querySelector('.custom-alert-box').style.transform = 'scale(0.7)';
            overlay.querySelector('.custom-alert-box').style.opacity = '0';
            setTimeout(() => overlay.style.display = 'none', 300);
        };

        document.getElementById('modernAppPromptYes').addEventListener('click', () => { cleanup(); resolve(input.value.trim()); });
        document.getElementById('modernAppPromptNo').addEventListener('click', () => { cleanup(); resolve(null); });
        
        input.onkeydown = function(e) { if(e.key === 'Enter') { e.preventDefault(); cleanup(); resolve(input.value.trim()); } };
    });
};


let idleTime = 0;
setInterval(() => {
    idleTime++;
    if (idleTime >= 30) { window.location.href = "index.php?logout=true&timeout=1"; } 
    else { fetch('api.php?action=ping'); }
}, 60000); 
['mousemove', 'keydown', 'scroll', 'click'].forEach(evt => document.addEventListener(evt, () => idleTime = 0));

function updateClock() {
    const now = new Date();
    const clockEl = document.getElementById('liveClock');
    if (clockEl) {
        clockEl.innerText = now.toLocaleString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
    }
}
setInterval(updateClock, 1000);
updateClock();

let isManualRequestor = false;

const defaultAvatar = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%238b949e'><path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/></svg>";
if(userProfiles[currentUser]) document.getElementById('headerAvatar').src = userProfiles[currentUser];

// ==========================================
// ??? DATA NORMALIZER (Fixes the "Missing Data" / Undefined Bug)
// ==========================================
function normalizeLogData(rawLogs) {
    return rawLogs.map(log => {
        let st = log.status || "New";
        if (st === "In Progress") st = "Open";

        return {
            id: log.id,
            ticketNumber: log.ticketNumber || log.ticket_number || log.ticketnumber || "N/A",
            type: log.type || log.log_type || "Support",
            status: st,
            concern: log.concern || log.concern_category || "Other",
            subCategory: log.subCategory || log.sub_category || "",
            requestorType: log.requestorType || log.requestor_type || "Employee",
            department: log.department || "",
            jobTitle: log.jobTitle || log.job_title || "",
            requestorSpecific: log.requestorSpecific || log.requestorspecific || "Unknown",
            title: log.title || "No Title",
            user: log.user || log.logged_by || "Unknown",
            assignedTo: log.assignedTo || log.assigned_to || null,
            message: log.message || "",
            clientAttachment: log.clientAttachment || log.client_attachment || null,
            adminResponse: log.adminResponse || log.admin_response || null,
            adminResponseBy: log.adminResponseBy || log.admin_response_by || null,
            adminResponseAt: log.adminResponseAt || log.admin_response_at || null,
            adminAttachment: log.adminAttachment || log.admin_attachment || null,
            replies: log.replies || "[]",
            date: log.date || "N/A",
            time: log.time || "N/A",
            timestamp: parseInt(log.timestamp || log.timestamp_val || log.timestampval) || Date.now()
        };
    });
}

// ==========================================
// ?? WIDGET DRAG, DROP & RESIZE SYSTEM
// ==========================================
let isEditingLayout = false;
let draggedWidget = null;
const defaultWidgetOrder = ['widget-status', 'widget-inbox', 'widget-categories', 'widget-trend', 'widget-overdue', 'widget-leaderboard'];

window.initDashboardWidgets = function() {
    const container = document.getElementById('dashboardWidgetContainer');
    if(!container) return;

    let layout = appSettings.widgetLayout || { order: defaultWidgetOrder, styles: {} };
    
    // Apply saved order
    layout.order.forEach(id => {
        const el = document.getElementById(id);
        if(el) container.appendChild(el);
    });

    // Apply saved sizes
    Object.entries(layout.styles).forEach(([id, style]) => {
        const el = document.getElementById(id);
        if (el) {
            el.style.width = style.width || '';
            el.style.height = style.height || '';
            el.style.gridColumn = style.gridColumn || '';
        }
    });

    const widgets = document.querySelectorAll('.widget');
    widgets.forEach(widget => {
        widget.addEventListener('dragstart', (e) => {
            if(!isEditingLayout) { e.preventDefault(); return; }
            draggedWidget = widget;
            widget.classList.add('widget-ghost');
            e.dataTransfer.effectAllowed = 'move';
        });

        widget.addEventListener('dragend', () => {
            widget.classList.remove('widget-ghost');
            draggedWidget = null;
        });

        widget.addEventListener('dragover', (e) => {
            e.preventDefault();
            if(!isEditingLayout || !draggedWidget) return;
            const afterElement = getDragAfterElement(container, e.clientX, e.clientY);
            if (afterElement == null) {
                container.appendChild(draggedWidget);
            } else {
                container.insertBefore(draggedWidget, afterElement);
            }
        });
    });

    const ro = new ResizeObserver(() => {
        if(chartStatusObj) chartStatusObj.resize();
        if(chartUsersObj) chartUsersObj.resize();
        if(chartCategoriesObj) chartCategoriesObj.resize();
        if(chartTrendObj) chartTrendObj.resize();
    });
    widgets.forEach(w => ro.observe(w));
}

function getDragAfterElement(container, x, y) {
    const draggableElements = [...container.querySelectorAll('.widget:not(.widget-ghost)')];
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offsetY = y - box.top - box.height / 2;
        const offsetX = x - box.left - box.width / 2;
        
        if (offsetY < 0 && offsetX < 0 && offsetY > closest.offsetY) {
            return { offsetY: offsetY, element: child };
        } else {
            return closest;
        }
    }, { offsetY: Number.NEGATIVE_INFINITY }).element;
}

window.toggleEditLayout = function() {
    const container = document.getElementById('dashboardWidgetContainer');
    const widgets = document.querySelectorAll('.widget');
    const btn = document.getElementById('editLayoutBtn');
    isEditingLayout = !isEditingLayout;

    if (isEditingLayout) {
        container.classList.add('editing-layout');
        widgets.forEach(w => w.setAttribute('draggable', 'true'));
        btn.innerHTML = '&#128190; Save Layout';
        btn.style.backgroundColor = 'var(--success-color)';
        btn.style.color = 'white';
        btn.style.borderColor = 'var(--success-color)';
        showAppModal("Edit Mode Active! Drag widgets to reorder them, or drag the bottom right corner to resize.", true);
    } else {
        container.classList.remove('editing-layout');
        widgets.forEach(w => w.setAttribute('draggable', 'false'));
        btn.innerHTML = '&#9881; Edit Layout';
        btn.style.backgroundColor = '';
        btn.style.color = 'var(--primary-color)';
        btn.style.borderColor = 'var(--primary-color)';
        saveCurrentLayoutState();
    }
};

window.saveCurrentLayoutState = async function() {
    if(!isITStaff) return;
    const widgets = document.querySelectorAll('.widget');
    const layout = { order: [], styles: {} };
    
    widgets.forEach(w => {
        layout.order.push(w.id);
        layout.styles[w.id] = {
            width: w.style.width,
            height: w.style.height,
            gridColumn: w.style.gridColumn
        };
    });
    
    appSettings.widgetLayout = layout;
    await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'save_settings', settings: appSettings }) });
    showAppModal("Dashboard layout saved!", true);
};


// ==========================================
// IMAGE COMPRESSOR
// ==========================================
function getBase64FromFile(file, maxWidth = 800) {
    return new Promise((resolve, reject) => {
        if(!file) resolve(null);
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            if (!file.type.startsWith('image/')) {
                resolve(event.target.result);
                return;
            }
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                if (width > maxWidth) {
                    height = Math.round(height * maxWidth / width);
                    width = maxWidth;
                }
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                resolve(canvas.toDataURL(file.type, 0.85)); 
            };
        };
        reader.onerror = error => reject(error);
    });
}

window.uploadProfilePic = async function(event) {
    const file = event.target.files[0];
    if(!file) return;
    
    const base64 = await getBase64FromFile(file, 200);
    try {
        const res = await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'save_profile', image: base64 }) });
        const data = await res.json();
        if(data.status === 'success') {
            document.getElementById('headerAvatar').src = base64;
            userProfiles[currentUser] = base64;
            renderLogs();
            showAppModal("Profile picture updated successfully!", true);
        }
    } catch(err) { 
        console.error("Profile upload failed", err); 
        showAppModal("Failed to update profile picture. The file might be corrupted.", false);
    }
}

// ==========================================

window.setUserTheme = function(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('userTheme', theme);
    if(typeof renderDashboardCharts === 'function') renderDashboardCharts();
};

const categoryMap = {
    "Hardware": ["Mouse", "Keyboard", "Monitor", "Laptop", "Server", "Telephone", "Access Point", "Desktop", "POS", "Cashdrawers", "Printer", "Scanner", "TV"],
    "Software": ["Operating System", "Microsoft 365 Office", "MS Teams", "Zoom", "Sun System", "Miwa", "EDR"],
    "System Access": ["Password Reset", "Access Creation", "Access Deactivation", "Access Rights"],
    "Network": ["Slow Connection", "Wi-Fi Concerns", "Downtime", "No Internet"],
    "Other": ["General Configuration", "Security Incident", "Miscellaneous"]
};

const logForm = document.getElementById('addLogForm');
const respondForm = document.getElementById('respondForm');
const searchInput = document.getElementById('searchInput');
const filterCategory = document.getElementById('filterCategory');
const filterUser = document.getElementById('filterUser');
const filterDate = document.getElementById('filterDate');
const filterStatus = document.getElementById('filterStatus');
const fAssigned = document.getElementById('filterAssigned');

let chartStatusObj = null;
let chartUsersObj = null;
let chartCategoriesObj = null;
let chartTrendObj = null;


window.switchITMainTab = function(tabName) {
    document.querySelectorAll('.it-main-view').forEach(el => el.style.display = 'none');
    document.querySelectorAll('#mainTab-dashboard, #mainTab-tickets, #mainTab-reports, #mainTab-settings').forEach(el => el.classList.remove('active'));
    
    const targetView = document.getElementById(`itMainView-${tabName}`);
    targetView.style.animation = 'none';
    targetView.offsetHeight; 
    targetView.style.animation = null; 
    targetView.style.display = 'block';

    document.getElementById(`mainTab-${tabName}`).classList.add('active');

    if(tabName === 'dashboard') renderDashboardCharts();
}

window.switchITView = function(view) {
    if (view === 'create') {
        document.getElementById('itDashboardView').style.display = 'none';
        const formView = document.getElementById('formContainer');
        formView.style.animation = 'none';
        formView.offsetHeight; 
        formView.style.animation = null;
        formView.style.display = 'block';
        resetForm();
    } else {
        document.getElementById('formContainer').style.display = 'none';
        const dashView = document.getElementById('itDashboardView');
        dashView.style.animation = 'none';
        dashView.offsetHeight; 
        dashView.style.animation = null;
        dashView.style.display = 'block';
    }
}

window.switchClientView = function(view) {
    if (view === 'tickets') {
        document.getElementById('newTicketView').style.display = 'none';
        const ticketView = document.getElementById('myTicketsView');
        ticketView.style.animation = 'none';
        ticketView.offsetHeight; 
        ticketView.style.animation = null;
        ticketView.style.display = 'block';
        
        document.getElementById('tabNewTicket').classList.remove('active');
        document.getElementById('tabMyTickets').classList.add('active');
    } else {
        const newView = document.getElementById('newTicketView');
        newView.style.animation = 'none';
        newView.offsetHeight; 
        newView.style.animation = null;
        newView.style.display = 'block';
        document.getElementById('myTicketsView').style.display = 'none';
        
        document.getElementById('tabNewTicket').classList.add('active');
        document.getElementById('tabMyTickets').classList.remove('active');
    }
}

// ??? FIXES THE "0 METRICS" BUG BY USING GLOBAL LOGS
window.updateGlobalMetrics = function() {
    if (!isITStaff) return;
    
    let total = 0, pendingCount = 0, inProgressCount = 0, resolvedCount = 0;
    
    // Calculates based on ALL logs, not the filtered table logs
    activityLogs.forEach(log => {
        total++;
        let s = log.status;
        if (["Pending", "Hold"].includes(s)) pendingCount++;
        else if (["New", "Open", "In Progress"].includes(s)) inProgressCount++;
        else if (["Resolved", "Closed"].includes(s)) resolvedCount++;
    });

    if(document.getElementById('countTotal')) document.getElementById('countTotal').innerText = total;
    if(document.getElementById('countOpen')) document.getElementById('countOpen').innerText = inProgressCount;
    if(document.getElementById('countPending')) document.getElementById('countPending').innerText = pendingCount;
    if(document.getElementById('countResolved')) document.getElementById('countResolved').innerText = resolvedCount;

    if(document.getElementById('dashTotal')) document.getElementById('dashTotal').innerText = total;
    if(document.getElementById('dashOpen')) document.getElementById('dashOpen').innerText = inProgressCount;
    if(document.getElementById('dashPending')) document.getElementById('dashPending').innerText = pendingCount;
    if(document.getElementById('dashResolved')) document.getElementById('dashResolved').innerText = resolvedCount;

    if(document.getElementById('metricResolved')) {
        if (total > 0) {
            document.getElementById('metricResolved').style.width = (resolvedCount / total * 100) + '%';
            document.getElementById('metricInProgress').style.width = (inProgressCount / total * 100) + '%';
            document.getElementById('metricPending').style.width = (pendingCount / total * 100) + '%';
        } else {
            document.getElementById('metricResolved').style.width = '0%';
            document.getElementById('metricInProgress').style.width = '0%';
            document.getElementById('metricPending').style.width = '0%';
        }
    }
}

window.renderDashboardCharts = function() {
    if(!isITStaff || activityLogs.length === 0) return;

    initDashboardWidgets();
    updateGlobalMetrics();

    const days = document.getElementById('chartTimeFilter') ? document.getElementById('chartTimeFilter').value : '7';
    const now = new Date().getTime();
    
    let filteredForCharts = activityLogs;
    if (days !== 'all') {
        const cutoff = now - (parseInt(days) * 24 * 60 * 60 * 1000);
        filteredForCharts = activityLogs.filter(log => log.timestamp > cutoff);
    }

    let statusCounts = { 'New': 0, 'Open': 0, 'Pending': 0, 'Hold': 0, 'Resolved': 0, 'Closed': 0 };
    let userCounts = {};
    let catCounts = {};
    let dateCounts = {};
    let itLeaderboard = {};
    
    const threeDaysAgo = now - (3 * 24 * 60 * 60 * 1000);
    const overdueList = document.getElementById('overdueTicketsList');
    if(overdueList) overdueList.innerHTML = '';
    let overdueCount = 0;

    filteredForCharts.forEach(log => {
        let st = log.status;
        
        if(statusCounts[st] !== undefined) statusCounts[st]++;

        userCounts[log.user] = (userCounts[log.user] || 0) + 1;
        catCounts[log.concern] = (catCounts[log.concern] || 0) + 1;

        if (log.date !== 'Unknown' && log.date !== 'N/A') {
            dateCounts[log.date] = (dateCounts[log.date] || 0) + 1;
        }

        if (log.assignedTo) {
            if (!itLeaderboard[log.assignedTo]) itLeaderboard[log.assignedTo] = { total: 0, resolved: 0 };
            itLeaderboard[log.assignedTo].total++;
            if (['Resolved', 'Closed'].includes(st)) itLeaderboard[log.assignedTo].resolved++;
        }

        if (!['Resolved', 'Closed'].includes(st) && log.timestamp < threeDaysAgo) {
            overdueCount++;
            if(overdueList) {
                overdueList.innerHTML += `
                    <li style="padding: 0.5rem 1rem; border-left: 3px solid var(--danger-color);">
                        <strong>[${log.ticketNumber}]</strong> ${log.title} 
                        <br><span style="font-size:0.8rem; color:var(--muted-color);">Req: ${log.requestorSpecific} | Open since: ${log.date}</span>
                    </li>
                `;
            }
        }
    });

    if(overdueCount === 0 && overdueList) {
        overdueList.innerHTML = '<li style="color:var(--success-color); padding: 0.5rem;">&#10004; No overdue tickets found!</li>';
    }

    const leaderList = document.getElementById('leaderboardList');
    if (leaderList) {
        leaderList.innerHTML = '';
        const sortedLeaders = Object.entries(itLeaderboard).sort((a, b) => b[1].total - a[1].total);
        
        if (sortedLeaders.length === 0) {
            leaderList.innerHTML = '<li style="color:var(--muted-color); padding: 0.5rem;">No tickets assigned yet.</li>';
        } else {
            sortedLeaders.forEach((leader, index) => {
                let rankIcon = index === 0 ? '&#129351;' : index === 1 ? '&#129352;' : index === 2 ? '&#129353;' : `<span style="display:inline-block; width:24px; text-align:center; color:var(--muted-color); font-weight:bold; font-size:1rem;">${index + 1}</span>`;
                leaderList.innerHTML += `
                    <li style="padding: 0.8rem 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span style="font-size: 1.8rem; line-height: 1;">${rankIcon}</span>
                            <strong style="color: var(--text-color); font-size:1.05rem;">${leader[0]}</strong>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: var(--primary-color); font-weight: bold; font-size: 1.2rem; line-height:1;">${leader[1].total} <span style="font-size: 0.7rem; color: var(--muted-color); font-weight: normal;">TOTAL</span></div>
                            <div style="font-size: 0.75rem; color: var(--success-color); margin-top:0.2rem;">${leader[1].resolved} Resolved/Closed</div>
                        </div>
                    </li>
                `;
            });
        }
    }


    const style = getComputedStyle(document.body);
    const cText = style.getPropertyValue('--text-color').trim();
    const cPrimary = style.getPropertyValue('--primary-color').trim();
    
    Chart.defaults.color = cText;

    const statusColors = ['#00e5ff', '#2979ff', '#ffea00', '#ff1744', '#00e676', '#8b949e']; 
    const statusLabels = ['New', 'Open', 'Pending', 'Hold', 'Resolved', 'Closed'];
    const statusData = [statusCounts['New'], statusCounts['Open'], statusCounts['Pending'], statusCounts['Hold'], statusCounts['Resolved'], statusCounts['Closed']];

    if(chartStatusObj) chartStatusObj.destroy();
    const ctxStatus = document.getElementById('chartStatus');
    if(ctxStatus) {
        document.getElementById('chartStatusCenter').innerHTML = `<span style="font-size:1rem;">Total</span><br><strong style="font-size:2.2rem;">${filteredForCharts.length}</strong>`;
        
        chartStatusObj = new Chart(ctxStatus, {
            type: 'doughnut',
            data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: statusColors, borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: { enabled: true } } }
        });

        let legendHtml = '';
        statusLabels.forEach((label, i) => {
            legendHtml += `<div style="display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="width:12px; height:12px; border-radius:3px; background:${statusColors[i]};"></span>
                    <span style="color:var(--text-color);">${label}</span>
                </div>
                <strong style="color:var(--text-color);">${statusData[i]}</strong>
            </div>`;
        });
        document.getElementById('chartStatusLegend').innerHTML = legendHtml;
    }

    const userColors = ['#1f77b4', '#00bcd4', '#ff9800', '#ff5722', '#4caf50'];
    const topUsers = Object.entries(userCounts).sort((a,b) => b[1] - a[1]).slice(0, 5);
    
    if(chartUsersObj) chartUsersObj.destroy();
    const ctxUsers = document.getElementById('chartUsers');
    if(ctxUsers) {
        chartUsersObj = new Chart(ctxUsers, {
            type: 'pie',
            data: { labels: topUsers.map(u => u[0]), datasets: [{ data: topUsers.map(u => u[1]), backgroundColor: userColors, borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        let userLegHtml = '';
        topUsers.forEach((u, i) => {
            userLegHtml += `<div style="display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="width:12px; height:12px; border-radius:3px; background:${userColors[i]};"></span>
                    <span style="color:var(--text-color);">${u[0]}</span>
                </div>
                <strong style="color:var(--text-color);">${u[1]}</strong>
            </div>`;
        });
        document.getElementById('chartUsersLegend').innerHTML = userLegHtml;
    }

    const topCats = Object.entries(catCounts).sort((a,b) => b[1] - a[1]).slice(0, 5);
    if(chartCategoriesObj) chartCategoriesObj.destroy();
    const ctxCats = document.getElementById('chartCategories');
    if(ctxCats) {
        chartCategoriesObj = new Chart(ctxCats, {
            type: 'bar',
            data: {
                labels: topCats.map(c => c[0]),
                datasets: [{
                    label: 'Tickets',
                    data: topCats.map(c => c[1]),
                    backgroundColor: cPrimary,
                    borderRadius: 4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { y: { beginAtZero: true, ticks: { precision:0 } } } 
            }
        });
    }

    const sortedDates = Object.keys(dateCounts).sort((a, b) => new Date(a) - new Date(b));
    const trendData = sortedDates.map(date => dateCounts[date]);

    if(chartTrendObj) chartTrendObj.destroy();
    const ctxTrend = document.getElementById('chartTrend');
    if(ctxTrend) {
        chartTrendObj = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: sortedDates,
                datasets: [{
                    label: 'Tickets Created',
                    data: trendData,
                    borderColor: cPrimary,
                    backgroundColor: cPrimary + '33', 
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointBackgroundColor: cPrimary
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision:0 } } }
            }
        });
    }
}

window.toggleDropdown = function(e, id) {
    if(e) e.preventDefault();
    document.getElementById(id).classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropdown-toggle')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

window.assignToMe = async function(e, id) {
    if(e) e.preventDefault();
    const btn = e.currentTarget;
    const ogHtml = btn.innerHTML;
    btn.innerHTML = "&#8987; Assigning...";
    btn.disabled = true;
    try {
        const res = await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'assign_ticket', id: id }) });
        const data = await res.json();
        if(data.status === 'success') {
            await fetchLogsFromDB();
        } else {
            showAppModal("Failed to assign ticket.", false);
            btn.innerHTML = ogHtml;
            btn.disabled = false;
        }
    } catch (err) { 
        console.error("Error assigning ticket", err); 
        btn.innerHTML = ogHtml;
        btn.disabled = false;
    }
}

window.openOnlineUsersModal = async function(e) {
    if(e) e.preventDefault();
    document.getElementById('onlineUsersModal').style.display = 'flex';
    const list = document.getElementById('onlineUsersList');
    list.innerHTML = '<li>Loading active users...</li>';
    try {
        const res = await fetch('api.php?action=get_online_users');
        const users = await res.json();
        list.innerHTML = '';
        if(users.length === 0) { list.innerHTML = '<li>No end-users currently online.</li>'; return; }
        users.forEach(u => {
            list.innerHTML += `
                <li style="padding: 0.8rem 1rem;">
                    <div style="display:flex; flex-direction:column;">
                        <strong style="color:var(--success-color);">&#128994; ${u.displayname}</strong>
                        <span style="font-size:0.8rem; color:var(--muted-color);">${u.job_title} | ${u.department}</span>
                    </div>
                </li>
            `;
        });
    } catch(e) { list.innerHTML = '<li>Error loading users.</li>'; }
}
window.closeOnlineUsersModal = function() { document.getElementById('onlineUsersModal').style.display = 'none'; }


window.openKBModal = function(e) { 
    if(e) e.preventDefault();
    renderKB();
    document.getElementById('kbModal').style.display = 'flex'; 
}
window.closeKBModal = function() { document.getElementById('kbModal').style.display = 'none'; }

window.openFormsModal = function(e) { 
    if(e) e.preventDefault();
    renderForms();
    document.getElementById('formsModal').style.display = 'flex'; 
}
window.closeFormsModal = function() { document.getElementById('formsModal').style.display = 'none'; }

window.renderKB = function() {
    const kbArea = document.getElementById('kbContentArea');
    if(!kbArea) return;
    kbArea.innerHTML = '';
    if(!appSettings.knowledgeBase || appSettings.knowledgeBase.length === 0) {
        kbArea.innerHTML = '<p>No knowledge base articles found.</p>';
        return;
    }
    appSettings.knowledgeBase.forEach(item => {
        kbArea.innerHTML += `<div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed var(--border-color);">
            <span style="font-size:0.8rem; color:var(--primary-color); text-transform:uppercase;">${item.category}</span>
            <h3 style="margin-top:0.2rem; margin-bottom:0.5rem; color: var(--text-color);">${item.title}</h3>
            <div>${item.content}</div>
            ${item.url ? `<a href="${item.url}" target="_blank" class="attach-link" style="margin-top:0.8rem;">&#128206; View Attached PDF/Document</a>` : ''}
        </div>`;
    });
}

window.renderForms = function() {
    const formArea = document.getElementById('formsContentArea');
    if(!formArea) return;
    formArea.innerHTML = '';
    if(!appSettings.itForms || appSettings.itForms.length === 0) {
        formArea.innerHTML = '<p>No IT forms available at the moment.</p>';
        return;
    }
    appSettings.itForms.forEach(item => {
        formArea.innerHTML += `<div style="display:flex; justify-content:space-between; align-items:center; background:var(--input-bg); padding: 1rem; border-radius:6px; border: 1px solid var(--border-color);">
            <div style="font-weight:bold; color:var(--text-color);">${item.title}</div>
            <a href="${item.url}" download="${item.filename}" class="btn-outline" style="border-color:var(--primary-color); color:var(--primary-color);">Download Form</a>
        </div>`;
    });
}

window.openRespondModal = function(id) {
    const log = activityLogs.find(l => l.id == id);
    if(!log) return;
    document.getElementById('respondTicketId').value = log.id;
    document.getElementById('respondModalTitle').innerText = `Reply to ${log.ticketNumber}`;
    document.getElementById('respondIssueTitle').innerText = log.title;
    document.getElementById('respondIssueMessage').innerText = log.message;
    if(isITStaff) {
        document.getElementById('respondStatus').value = log.status;
    }
    document.getElementById('respondMessage').value = '';
    if(document.getElementById('threadAttachment')) document.getElementById('threadAttachment').value = '';
    document.getElementById('respondModal').style.display = "flex";
}
window.closeRespondModal = function() {
    document.getElementById('respondModal').style.display = "none";
}

window.updateSubCategory = function(selectedValue) {
    if (typeof selectedValue !== 'string') selectedValue = "";
    const concernSelect = document.getElementById('logConcern');
    const subSelect = document.getElementById('logSubCategory');
    if (!concernSelect || !subSelect) return;
    subSelect.innerHTML = '<option value="" disabled selected>Select Specific Issue...</option>';
    const val = concernSelect.value;
    if (categoryMap[val]) {
        subSelect.disabled = false;
        categoryMap[val].sort().forEach(sub => {
            const option = document.createElement('option'); 
            option.value = sub; 
            option.textContent = sub;
            subSelect.appendChild(option);
        });
        if(selectedValue) subSelect.value = selectedValue;
    } else { 
        subSelect.disabled = true; 
    }
}

window.updateDepartmentDropdown = function(selectedDept) {
    if (typeof selectedDept !== 'string') selectedDept = "";
    const type = document.getElementById('logRequestor');
    if(!type) return;
    const dept = document.getElementById('logDepartment');
    const reqSub = document.getElementById('logRequestorSub');
    dept.innerHTML = '<option value="" disabled selected>Select Department...</option>';
    if(reqSub) { reqSub.innerHTML = '<option value="" disabled selected>Waiting for Dept...</option>'; reqSub.disabled = true; }
    let sourceMap = type.value === "Employee" ? appSettings.employeeDepartments : (type.value === "Guest" ? appSettings.guestDepartments : null);
    if (sourceMap) {
        dept.disabled = false;
        Object.keys(sourceMap).sort().forEach(d => { dept.innerHTML += `<option value="${d}">${d}</option>`; });
        if(selectedDept) dept.value = selectedDept;
    } else if (type.value === "N/A") {
        dept.disabled = false; dept.innerHTML += `<option value="IT | Finance Department">IT | Finance Department</option>`; dept.value = "IT | Finance Department";
        if(reqSub) { reqSub.disabled = false; reqSub.innerHTML = '<option value="Internal IT">Internal IT</option>'; reqSub.value = "Internal IT"; }
    } else { dept.disabled = true; }
}

window.updateSpecificRequestor = function(selectedSub) {
    if (typeof selectedSub !== 'string') selectedSub = "";
    const type = document.getElementById('logRequestor');
    if(!type) return;
    const dept = document.getElementById('logDepartment').value;
    const reqSub = document.getElementById('logRequestorSub');
    if(!reqSub) return;
    reqSub.innerHTML = '<option value="" disabled selected>Select Name / Room...</option>';
    let sourceMap = type.value === "Employee" ? appSettings.employeeDepartments : (type.value === "Guest" ? appSettings.guestDepartments : null);
    if (sourceMap && sourceMap[dept]) {
        reqSub.disabled = false;
        let sortedSubs = type.value === "Guest" ? sourceMap[dept].sort((a,b) => parseInt(a) - parseInt(b)) : sourceMap[dept].sort();
        sortedSubs.forEach(sub => {
            const option = document.createElement('option'); 
            option.value = type.value === "Guest" ? `Room ${sub}` : sub;
            option.textContent = type.value === "Guest" ? `Room ${sub}` : sub; 
            reqSub.appendChild(option);
        });
        if(selectedSub) reqSub.value = selectedSub;
    } else { reqSub.disabled = true; }
}

window.setFormDateTimeToNow = function() {
    const now = new Date();
    const d = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
    const t = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    const dateInput = document.getElementById('logDate');
    const timeInput = document.getElementById('logTime');
    if (dateInput) dateInput.value = d;
    if (timeInput) timeInput.value = t;
}
setFormDateTimeToNow();

window.generateTicketNumber = function() {
    let maxNum = 0;
    for (let log of activityLogs) {
        if (log.ticketNumber && log.ticketNumber.startsWith('HORTS-')) {
            let numPart = parseInt(log.ticketNumber.replace('HORTS-', ''), 10);
            if (!isNaN(numPart) && numPart > maxNum) maxNum = numPart;
        }
    }
    return 'HORTS-' + String(maxNum + 1).padStart(6, '0');
}

window.fetchLogsFromDB = async function() {
    try {
        const res = await fetch('api.php?action=get_logs');
        const raw = await res.json();
        
        // ??? DATA CLEANING FIX (Solves missing JSON bug)
        activityLogs = normalizeLogData(raw);

        populateUserFilter();
        renderLogs();
        if(isITStaff) {
            updateGlobalMetrics();
            if(document.getElementById('itMainView-dashboard').style.display !== 'none') {
                renderDashboardCharts();
            }
        }
    } catch (e) {
        console.error("Failed to fetch logs", e);
    }
}

window.saveSettingsToServer = async function() {
    try { await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'save_settings', settings: appSettings }) }); } 
    catch (e) { console.error(e); }
}

if (logForm) {
    logForm.addEventListener('submit', async function(e) {
        e.preventDefault(); 
        const editId = document.getElementById('editId').value;
        const dateInput = document.getElementById('logDate').value;
        const timeInput = document.getElementById('logTime').value;
        const customDateObj = new Date(`${dateInput}T${timeInput}`);

        const formattedDate = customDateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const formattedTime = customDateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

        let tktNum = generateTicketNumber();
        if (editId) {
            let existingLog = activityLogs.find(l => l.id == editId);
            tktNum = existingLog ? existingLog.ticketNumber : tktNum;
        }

        let reqName = "";
        if (isITStaff) {
            reqName = isManualRequestor ? document.getElementById('logRequestorSubManual').value : document.getElementById('logRequestorSub').value;
        } else {
            reqName = document.getElementById('logRequestorSub').value;
        }

        const clientFile = document.getElementById('clientAttachment') ? document.getElementById('clientAttachment').files[0] : null;
        const clientBase64 = clientFile ? await getBase64FromFile(clientFile) : null;

        const logData = {
            editId: editId, 
            ticketNumber: tktNum,
            type: isITStaff ? document.getElementById('logType').value : 'Support',
            status: isITStaff ? document.getElementById('logStatus').value : 'New',
            concern: document.getElementById('logConcern').value,
            subCategory: document.getElementById('logSubCategory').value,
            requestorType: document.getElementById('logRequestor').value,
            department: document.getElementById('logDepartment').value,
            jobTitle: document.getElementById('logJobTitle') ? document.getElementById('logJobTitle').value : '',
            requestorSpecific: reqName,
            title: document.getElementById('logTitle').value,
            message: document.getElementById('logMessage').value,
            clientAttachmentBase64: clientBase64,
            date: formattedDate,
            time: formattedTime,
            timestamp: customDateObj.getTime()
        };

        const btn = document.getElementById('saveLogBtn');
        const ogText = btn.textContent;
        btn.textContent = "Uploading & Sending...";
        btn.disabled = true;

        try {
            await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'save_log', log: logData }) });
            await fetchLogsFromDB();
            resetForm();
            if (isITStaff) {
                switchITMainTab('tickets');
                showAppModal("Ticket saved successfully!", true);
            }
            if (!isITStaff) {
                showAppModal("Ticket submitted successfully! IT has been notified.", true);
                switchClientView('tickets');
            }
        } catch (e) { console.error("Error saving to DB", e); }
        
        btn.textContent = ogText;
        btn.disabled = false;
    });
}

if (respondForm) {
    respondForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('respondTicketId').value;
        const log = activityLogs.find(l => l.id == id);
        if(!log) return;
        
        const attachFile = document.getElementById('threadAttachment') ? document.getElementById('threadAttachment').files[0] : null;
        const attachBase64 = attachFile ? await getBase64FromFile(attachFile) : null;

        const responseData = {
            ticketId: log.id,
            message: document.getElementById('respondMessage').value,
            attachmentBase64: attachBase64,
            status: isITStaff ? document.getElementById('respondStatus').value : null
        };
        
        const btn = document.querySelector('#respondForm .btn-submit');
        btn.textContent = "Sending Reply...";
        btn.disabled = true;

        try {
            await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'save_reply', ...responseData }) });
            await fetchLogsFromDB();
            closeRespondModal();
            showAppModal("Response submitted successfully!", true);
        } catch (e) { console.error("Error submitting reply", e); }
        
        btn.textContent = "Submit Reply";
        btn.disabled = false;
    });
}

window.resetForm = function() {
    if(document.getElementById('editId')) document.getElementById('editId').value = ''; 
    if(document.getElementById('logTitle')) document.getElementById('logTitle').value = '';
    if(document.getElementById('logMessage')) document.getElementById('logMessage').value = ''; 
    if(document.getElementById('clientAttachment')) document.getElementById('clientAttachment').value = '';
    
    if (isITStaff) {
        if(document.getElementById('logType')) document.getElementById('logType').value = 'Support';
        if(document.getElementById('logStatus')) document.getElementById('logStatus').value = 'New';
        if(document.getElementById('logRequestor')) document.getElementById('logRequestor').value = '';
        if(document.getElementById('logJobTitle')) document.getElementById('logJobTitle').value = '';
        
        isManualRequestor = false;
        const sel = document.getElementById('logRequestorSub');
        const man = document.getElementById('logRequestorSubManual');
        const toggleBtn = document.getElementById('manualToggleBtn');
        if(sel && man && toggleBtn) {
            sel.style.display = 'block'; sel.required = true;
            man.style.display = 'none'; man.required = false; man.value = '';
            toggleBtn.innerHTML = 'Manual Entry';
        }
        updateDepartmentDropdown();
    }
    
    if(document.getElementById('logConcern')) document.getElementById('logConcern').value = ''; 
    updateSubCategory(); 
    setFormDateTimeToNow(); 
}

if (canManage) {
    window.editLog = function(id) {
        const log = activityLogs.find(l => l.id == id);
        if(!log) return;
        
        switchITMainTab('tickets');
        switchITView('create'); 
        document.getElementById('formContainer').classList.add('edit-mode');
        document.getElementById('saveLogBtn').textContent = "Update Log Entry";
        document.getElementById('cancelEditBtn').style.display = "inline-block";
        
        document.getElementById('editId').value = log.id; 
        document.getElementById('logType').value = log.type;
        document.getElementById('logStatus').value = log.status;
        document.getElementById('logConcern').value = log.concern; 
        updateSubCategory(log.subCategory);
        document.getElementById('logRequestor').value = log.requestorType;
        
        updateDepartmentDropdown(log.department); 
        
        const sel = document.getElementById('logRequestorSub');
        let foundInDropdown = false;
        if(sel) {
            for (let i = 0; i < sel.options.length; i++) {
                if (sel.options[i].value === log.requestorSpecific) { foundInDropdown = true; break; }
            }
        }

        if (!foundInDropdown && log.requestorSpecific) {
            if (!isManualRequestor) document.getElementById('manualToggleBtn').click();
            document.getElementById('logRequestorSubManual').value = log.requestorSpecific;
        } else {
            if (isManualRequestor) document.getElementById('manualToggleBtn').click();
            if(sel) sel.value = log.requestorSpecific;
        }
        
        document.getElementById('logJobTitle').value = log.jobTitle;
        document.getElementById('logTitle').value = log.title; 
        document.getElementById('logMessage').value = log.message;

        const logD = new Date(log.timestamp);
        document.getElementById('logDate').value = logD.getFullYear() + '-' + String(logD.getMonth()+1).padStart(2,'0') + '-' + String(logD.getDate()).padStart(2,'0');
        document.getElementById('logTime').value = String(logD.getHours()).padStart(2,'0') + ':' + String(logD.getMinutes()).padStart(2,'0');

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.cancelEdit = function() {
        document.getElementById('formContainer').classList.remove('edit-mode');
        document.getElementById('saveLogBtn').textContent = "+ Save Log Entry";
        document.getElementById('cancelEditBtn').style.display = "none"; resetForm();
        switchITView('dashboard');
    }

    window.openSettingsModal = function(e) {
        if(e) e.preventDefault();
        const dl = document.getElementById('existingDepts'); dl.innerHTML = '';
        const allDepts = [...new Set([...Object.keys(appSettings.employeeDepartments), ...Object.keys(appSettings.guestDepartments)])].sort();
        allDepts.forEach(d => dl.innerHTML += `<option value="${d}">`);
        renderMngDepts();
        document.getElementById('settingsModal').style.display = "flex";
    }
    window.closeSettingsModal = function() { document.getElementById('settingsModal').style.display = "none"; }
    
    if(document.getElementById('settingsForm')) {
        document.getElementById('settingsForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const type = document.getElementById('setReqType').value;
            const dept = document.getElementById('setDept').value.trim();
            const spec = document.getElementById('setSpecific').value.trim();
            let targetMap = type === "Employee" ? appSettings.employeeDepartments : appSettings.guestDepartments;
            
            if (!targetMap[dept]) targetMap[dept] = []; 
            if (!targetMap[dept].includes(spec)) {
                targetMap[dept].push(spec); 
                await saveSettingsToServer(); 
                showAppModal(`Added ${spec} to ${dept}!`, true);
            } else { 
                showAppModal(`${spec} already exists in ${dept}.`, false); 
            }

            document.getElementById('setSpecific').value = '';
            renderMngDepts(); 
            if(document.getElementById('logRequestor').value === type) {
                updateDepartmentDropdown(document.getElementById('logDepartment').value);
                updateSpecificRequestor(document.getElementById('logRequestorSub').value);
            }
        });
    }

    window.renderMngDepts = function() {
        const type = document.getElementById('mngType').value;
        const deptSelect = document.getElementById('mngDept');
        deptSelect.innerHTML = '<option value="" disabled selected>Select Department to Edit...</option>';
        const map = type === 'Employee' ? appSettings.employeeDepartments : appSettings.guestDepartments;
        Object.keys(map).sort().forEach(d => { deptSelect.innerHTML += `<option value="${d}">${d}</option>`; });
        document.getElementById('mngItemList').innerHTML = '<li>Select a department to view members.</li>';
    }

    window.renderMngItems = function() {
        const type = document.getElementById('mngType').value;
        const dept = document.getElementById('mngDept').value;
        const map = type === 'Employee' ? appSettings.employeeDepartments : appSettings.guestDepartments;
        const list = document.getElementById('mngItemList');
        list.innerHTML = '';
        if(!map[dept] || map[dept].length === 0) { list.innerHTML = '<li>No members found.</li>'; return; }
        
        let sortedSubs = type === "Guest" ? map[dept].sort((a,b) => parseInt(a) - parseInt(b)) : map[dept].sort();
        sortedSubs.forEach(item => {
            let delBtn = isSuperAdmin ? `<button type="button" class="mng-btn del" onclick="deleteDirItem('${type}', '${dept}', '${item}')" title="Delete">&#128465;</button>` : '';
            list.innerHTML += `
                <li>
                    <span>${type === 'Guest' ? 'Room ' : ''}${item}</span>
                    <div class="mng-actions">
                        <button type="button" class="mng-btn" onclick="renameDirItem('${type}', '${dept}', '${item}')" title="Rename">&#9999;</button>
                        ${delBtn}
                    </div>
                </li>
            `;
        });
    }

    window.deleteDirItem = async function(type, dept, item) {
        if(!await showAppConfirm(`Are you sure you want to remove ${item} from ${dept}?`)) return;
        const map = type === 'Employee' ? appSettings.employeeDepartments : appSettings.guestDepartments;
        const idx = map[dept].indexOf(item);
        if(idx > -1) {
            map[dept].splice(idx, 1);
            await saveSettingsToServer(); 
            renderMngItems();
        }
    }

    window.deleteDirDept = async function() {
        const type = document.getElementById('mngType').value;
        const dept = document.getElementById('mngDept').value;
        if(!dept) return showAppModal("Select a department first.", false);
        if(!await showAppConfirm(`Are you sure you want to delete the entire '${dept}' department?`)) return;
        
        const map = type === 'Employee' ? appSettings.employeeDepartments : appSettings.guestDepartments;
        delete map[dept];
        await saveSettingsToServer();
        renderMngDepts();
    }

    window.renameDirDept = async function() {
        const type = document.getElementById('mngType').value;
        const oldDept = document.getElementById('mngDept').value;
        if(!oldDept) return showAppModal("Select a department first.", false);
        
        const newDept = await showAppPrompt("Enter new department name:", oldDept);
        if(!newDept || newDept === oldDept) return;
        
        const map = type === 'Employee' ? appSettings.employeeDepartments : appSettings.guestDepartments;
        if(map[newDept]) return showAppModal("Department name already exists!", false);
        
        map[newDept] = map[oldDept]; delete map[oldDept];
        await saveSettingsToServer(); 
        renderMngDepts();
        if(document.getElementById('logRequestor').value === type) updateDepartmentDropdown(document.getElementById('logDepartment').value);
    }

    window.renameDirItem = async function(type, dept, oldName) {
        const newName = await showAppPrompt(`Rename '${oldName}' to:`, oldName);
        if(!newName || newName === oldName) return;
        const map = type === 'Employee' ? appSettings.employeeDepartments : appSettings.guestDepartments;
        const idx = map[dept].indexOf(oldName);
        if(idx > -1) {
            map[dept][idx] = newName;
            await saveSettingsToServer(); 
            renderMngItems();
            if(document.getElementById('logRequestor').value === type) updateSpecificRequestor(document.getElementById('logRequestorSub').value);
        }
    }
}

window.deleteLog = async function(id) {
    if(!await showAppConfirm("Are you sure you want to delete this ticket permanently?")) return;
    try {
        const res = await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'delete_log', id: id }) });
        const data = await res.json();
        if(data.status === 'success') {
            await fetchLogsFromDB();
            showAppModal("Ticket deleted successfully.", true);
        }
    } catch (e) { console.error("Error deleting log", e); }
}

window.populateUserFilter = function() {
    if (!filterUser) return;
    const currentSelection = filterUser.value;
    filterUser.innerHTML = '<option value="All">All Users</option>';
    
    const uniqueUsers = [...new Set(activityLogs.map(log => log.user))].filter(Boolean).sort();
    uniqueUsers.forEach(user => { filterUser.innerHTML += `<option value="${user}">${user}</option>`; });
    if (uniqueUsers.includes(currentSelection)) filterUser.value = currentSelection;
}

window.resetFilters = function() {
    if(searchInput) searchInput.value = ''; 
    if(filterCategory) filterCategory.value = 'All'; 
    if(filterUser) filterUser.value = 'All'; 
    if(filterDate) filterDate.value = ''; 
    if(filterStatus) filterStatus.value = 'Open'; 
    if(fAssigned) fAssigned.value = 'All';
    renderLogs();
}

window.getFilteredLogs = function() {
    const searchTerm = (searchInput ? searchInput.value : "").toLowerCase();
    const filterCat = filterCategory ? filterCategory.value : "All";
    const filterUsr = filterUser ? filterUser.value : "All"; 
    const filterDateVal = filterDate ? filterDate.value : ""; 
    const filterStatVal = filterStatus ? filterStatus.value : "Open"; 
    const filterAssignedVal = fAssigned ? fAssigned.value : "All";

    return activityLogs.filter(log => {
        const searchString = `${log.ticketNumber} ${log.title} ${log.message} ${log.user} ${log.concern} ${log.subCategory} ${log.requestorType} ${log.requestorSpecific} ${log.department}`.toLowerCase();
        
        const matchesSearch = searchString.includes(searchTerm);
        const matchesCategory = filterCat === "All" || log.type === filterCat;
        const matchesUser = filterUsr === "All" || log.user === filterUsr;
        
        let matchesStatus = true;
        if (filterStatVal === "Open") {
            matchesStatus = ["New", "Open", "In Progress"].includes(log.status);
        } else if (filterStatVal === "Closed") {
            matchesStatus = ["Closed", "Resolved"].includes(log.status);
        } else if (filterStatVal !== "All") {
            matchesStatus = log.status === filterStatVal;
        }
        
        let matchesDate = true;
        if (filterDateVal) {
            const d = new Date(log.timestamp);
            const localISO = d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, '0') + "-" + String(d.getDate()).padStart(2, '0');
            matchesDate = (localISO === filterDateVal);
        }

        let matchesAssignment = true;
        if (isITStaff && filterAssignedVal === "Me") {
            matchesAssignment = (!log.assignedTo || log.assignedTo === currentDisplayName);
        }

        return matchesSearch && matchesCategory && matchesUser && matchesDate && matchesStatus && matchesAssignment;
    });
}

window.renderLogs = function() {
    const filteredLogs = getFilteredLogs();
    
    const feedEl = document.getElementById('logFeed');
    if(!feedEl) return;
    feedEl.innerHTML = '';
    
    if (filteredLogs.length === 0) {
        feedEl.innerHTML = `<div class="empty-state">No tickets found for current filters.</div>`; return;
    }

    filteredLogs.forEach(log => {
        let borderColor = "var(--border-color)";
        if(log.type === "Support") borderColor = "var(--danger-color)";
        if(log.type === "Maintenance") borderColor = "var(--warning-color)";
        if(log.type === "Update") borderColor = "var(--success-color)";
        if(log.type === "Note") borderColor = "var(--info-color)";

        let reqDisplay = log.requestorType === 'N/A' ? 'Internal IT' : (log.requestorType === 'Guest' ? `Room ${log.requestorSpecific}` : log.requestorSpecific);
        
        let actionHtml = `<div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color); display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center;">`;
        actionHtml += `<button type="button" class="btn-outline" style="border-color: var(--primary-color); color: var(--primary-color);" onclick="openRespondModal(${log.id})">&#128172; Reply to Thread</button>`;
        
        if (isITStaff) {
            if (!log.assignedTo) {
                actionHtml += `<button type="button" class="btn-outline" style="border-color: var(--warning-color); color: var(--warning-color);" onclick="assignToMe(event, ${log.id})">&#128587; Assign to Me</button>`;
            }
            if (canManage) {
                actionHtml += `<button type="button" class="btn-outline" onclick="editLog(${log.id})">&#9999; Edit Details</button>`;
                if(isSuperAdmin) actionHtml += `<button type="button" class="btn-danger-outline" style="width:auto;" onclick="deleteLog(${log.id})">&#128465; Delete</button>`;
            }
        }
        actionHtml += `</div>`;

        const avatarSrc = userProfiles[log.user] ? userProfiles[log.user] : defaultAvatar;
        const ticketHtml = log.ticketNumber !== "N/A" ? `<span style="color: var(--primary-color); margin-right: 0.5rem; font-family: monospace;">[${log.ticketNumber}]</span>` : '';

        let badgeStyle = '';
        if(['New', 'Open', 'In Progress'].includes(log.status)) badgeStyle = 'background: rgba(88, 166, 255, 0.15); color: var(--primary-color); border-color: rgba(88, 166, 255, 0.4);';
        else if(['Pending', 'Hold'].includes(log.status)) badgeStyle = 'background: rgba(210, 153, 34, 0.15); color: var(--warning-color); border-color: rgba(210, 153, 34, 0.4);';
        else if(['Resolved', 'Closed'].includes(log.status)) badgeStyle = 'background: rgba(139, 148, 158, 0.15); color: var(--muted-color); border-color: rgba(139, 148, 158, 0.4);';
        else badgeStyle = 'background: rgba(46, 160, 67, 0.15); color: var(--success-color); border-color: rgba(46, 160, 67, 0.4);';

        let statusHtml = `<span class="status-badge" style="${badgeStyle}">${log.status}</span>`;
        const jobTitleHtml = log.jobTitle ? `<span class="secondary-badge" style="background: var(--input-bg); color: var(--text-color);">&#128188; ${log.jobTitle}</span>` : '';
        
        let assignedHtml = '';
        if(isITStaff && log.assignedTo) {
            assignedHtml = `<span class="secondary-badge" style="background: rgba(47, 129, 247, 0.15); border-color: var(--admin-color); color: var(--admin-color);">&#128104;&#8205;&#128187; Assigned to: ${log.assignedTo}</span>`;
        } else if (isITStaff && !log.assignedTo) {
            assignedHtml = `<span class="secondary-badge" style="background: rgba(210, 153, 34, 0.15); border-color: var(--warning-color); color: var(--warning-color);">&#9888; Unassigned</span>`;
        }

        const clientFileHtml = log.clientAttachment 
            ? `<a href="${log.clientAttachment}" target="_blank" class="attach-link">&#128206; View Initial Attachment</a>` 
            : '';

        let threadHtml = '';
        
        if (log.adminResponse && !log.replies || log.replies === "[]") {
            let formattedDate = log.adminResponseAt ? new Date(log.adminResponseAt).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) : 'Unknown Time';
            let responderName = log.adminResponseBy ? log.adminResponseBy : 'IT Support';
            const adminFileHtml = log.adminAttachment ? `<div style="margin-top:0.5rem;"><a href="${log.adminAttachment}" target="_blank" class="attach-link">&#128206; View Attachment</a></div>` : '';
            
            threadHtml += `
                <div style="margin-top: 1rem; padding: 1rem; background: var(--hover-bg); border-left: 3px solid var(--primary-color); border-radius: 4px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <strong style="color: var(--primary-color);">${responderName} <span style="opacity:0.6; font-weight:normal;">(IT)</span></strong>
                        <span style="font-size: 0.8rem; color: var(--muted-color);">${formattedDate}</span>
                    </div>
                    <span style="color: var(--text-color); white-space: pre-wrap; font-size: 0.95rem; display: block;">${log.adminResponse}</span>
                    ${adminFileHtml}
                </div>
            `;
        }

        if (log.replies && log.replies !== "[]") {
            let repliesArray = [];
            try { repliesArray = JSON.parse(log.replies); } catch(e) {}
            
            if (repliesArray.length > 0) {
                threadHtml += `<div class="ticket-thread" style="margin-top: 1.5rem;">`;
                repliesArray.forEach(reply => {
                    const isReplyIT = reply.role === 'IT';
                    const align = isReplyIT ? 'border-left: 3px solid var(--primary-color); background: var(--hover-bg);' : 'border-left: 3px solid var(--success-color); background: var(--hover-bg);';
                    const nameColor = isReplyIT ? 'var(--primary-color)' : 'var(--success-color)';
                    const roleLabel = reply.role === 'End-User' ? 'End-User' : 'IT';
                    const attHtml = reply.attachment ? `<div style="margin-top:0.5rem;"><a href="${reply.attachment}" target="_blank" class="attach-link">&#128206; View Attachment</a></div>` : '';
                    
                    let replyAvatarSrc = defaultAvatar;
                    if (reply.username && userProfiles[reply.username]) {
                        replyAvatarSrc = userProfiles[reply.username];
                    }

                    threadHtml += `
                        <div style="margin-bottom: 0.8rem; padding: 1rem; border-radius: 4px; ${align} display: flex; gap: 1rem;">
                            <img src="${replyAvatarSrc}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color); flex-shrink: 0;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                                    <strong style="color: ${nameColor};">${reply.sender} <span style="opacity:0.6; font-weight:normal;">(${roleLabel})</span></strong>
                                    <span style="color: var(--muted-color);">${reply.timestamp}</span>
                                </div>
                                <div style="color: var(--text-color); white-space: pre-wrap; font-size: 0.95rem;">${reply.message}</div>
                                ${attHtml}
                            </div>
                        </div>
                    `;
                });
                threadHtml += `</div>`;
            }
        }

        const logElement = document.createElement('div');
        logElement.className = 'log-entry';
        logElement.style.borderLeftColor = borderColor;
        
        logElement.innerHTML = `
            <div class="log-meta">
                <span class="log-date">${log.date || 'N/A'}</span>
                <span class="log-time">${log.time || 'N/A'}</span>
                <span class="log-user"><img src="${avatarSrc}" class="feed-avatar">${log.user}</span>
            </div>
            <div class="log-content">
                <div class="log-header">
                    <div style="width: 100%;">
                        <div class="tag-group">
                            ${statusHtml}
                            ${isITStaff ? `<span class="badge ${log.type}">${log.type}</span>` : ''}
                            <span class="secondary-badge">${log.concern}</span>
                            ${log.subCategory ? `<span class="secondary-badge">${log.subCategory}</span>` : ''}
                            ${assignedHtml}
                            ${isITStaff ? `<span class="secondary-badge" style="background: var(--input-bg); border-color: var(--border-color); color: var(--text-color);">&#128100; Req: ${reqDisplay}</span>` : ''}
                            ${isITStaff ? jobTitleHtml : ''}
                            ${isITStaff && log.department ? `<span class="secondary-badge" style="background: var(--hover-bg); border-color: var(--info-color); color: var(--text-color);">&#127970; Dept: ${log.department}</span>` : ''}
                        </div>
                        <div class="log-title">${ticketHtml}${log.title || 'No Title'}</div>
                    </div>
                </div>
                <div class="log-message">${log.message || ''}</div>
                ${clientFileHtml}
                ${threadHtml}
                ${actionHtml}
            </div>
        `;
        feedEl.appendChild(logElement);
    });
}

window.openEndUserSettingsModal = function(e) {
    if(e) e.preventDefault();
    renderEUKBList();
    renderEUFormList();
    document.getElementById('endUserSettingsModal').style.display = 'flex';
}
window.closeEndUserSettingsModal = function() {
    document.getElementById('endUserSettingsModal').style.display = 'none';
}

window.openThemeModal = function(e) {
    if(e) e.preventDefault();
    document.getElementById('themeLoginBanner').value = appSettings.loginBanner || "Sign in with your standard network credentials to request support or view operations.";
    document.getElementById('themeModal').style.display = 'flex';
}
window.closeThemeModal = function() {
    document.getElementById('themeModal').style.display = 'none';
}

window.saveThemeBanner = async function() {
    appSettings.loginBanner = document.getElementById('themeLoginBanner').value;
    await saveSettingsToServer();
    showAppModal("Login banner updated successfully!");
}

window.openAuthLogsModal = async function(e) {
    if(e) e.preventDefault();
    document.getElementById('authLogsModal').style.display = 'flex';
    document.getElementById('authTerminalOutput').innerHTML = 'Fetching security logs...';
    try {
        const res = await fetch('api.php?action=get_auth_logs');
        const data = await res.json();
        document.getElementById('authTerminalOutput').innerHTML = data.logs.join('<br><div class="term-line"></div>') || 'No security logs found.';
    } catch(err) {
        document.getElementById('authTerminalOutput').innerHTML = '<span class="term-fail">Error loading security logs.</span>';
    }
}
window.closeAuthLogsModal = function() {
    document.getElementById('authLogsModal').style.display = 'none';
}

window.clearAllLogs = async function(e) {
    if(e) e.preventDefault();
    if(!await showAppConfirm("DANGER: Are you sure you want to permanently purge ALL tickets? This action cannot be undone.")) return;
    try {
        const res = await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'clear_logs' }) });
        const data = await res.json();
        if(data.status === 'success') {
            activityLogs = [];
            renderLogs();
            showAppModal("Database purged successfully.");
        }
    } catch(err) { console.error("Error purging DB", err); }
}

window.uploadThemeImage = async function(event, key, maxSize) {
    const file = event.target.files[0];
    if(!file) return;
    const base64 = await getBase64FromFile(file);
    appSettings[key] = base64;
    await saveSettingsToServer();
    showAppModal("Appearance updated! Refresh the page to apply the changes.");
}

window.resetTheme = async function() {
    if(!await showAppConfirm("Are you sure you want to reset the appearance to default?")) return;
    appSettings.logoImage = "";
    appSettings.bgImage = "";
    appSettings.loginBanner = "";
    await saveSettingsToServer();
    location.reload();
}

window.saveEUKB = async function() {
    const title = document.getElementById('euKbTitle').value;
    const category = document.getElementById('euKbCategory').value;
    const content = document.getElementById('euKbContent').value;
    const fileInput = document.getElementById('euKbFile');
    let fileBase64 = fileInput.files[0] ? await getBase64FromFile(fileInput.files[0]) : "";

    if(!title || !content) return showAppModal("Title and Content are required to create an article.", false);

    if(!appSettings.knowledgeBase) appSettings.knowledgeBase = [];
    appSettings.knowledgeBase.push({
        id: Date.now(),
        title: title,
        category: category,
        content: content,
        url: fileBase64 
    });
    await saveSettingsToServer();
    document.getElementById('euKbTitle').value = '';
    document.getElementById('euKbContent').value = '';
    if(fileInput) fileInput.value = '';
    renderEUKBList();
    showAppModal("Article saved successfully!");
}

window.renderEUKBList = function() {
    const list = document.getElementById('euKbList');
    if(!list) return;
    list.innerHTML = '';
    if(!appSettings.knowledgeBase) return;
    appSettings.knowledgeBase.forEach(item => {
        list.innerHTML += `<li>${item.title} <button onclick="deleteEUKB(${item.id})" class="mng-btn del" title="Delete">&#128465;</button></li>`;
    });
}

window.deleteEUKB = async function(id) {
    if(!await showAppConfirm("Delete this article?")) return;
    appSettings.knowledgeBase = appSettings.knowledgeBase.filter(i => i.id !== id);
    await saveSettingsToServer();
    renderEUKBList();
}

window.saveEUForm = async function() {
    const title = document.getElementById('euFormTitle').value;
    const fileInput = document.getElementById('euFormFile');
    if(!title || !fileInput.files[0]) return showAppModal("Title and File are required.", false);
    
    const fileBase64 = await getBase64FromFile(fileInput.files[0]);
    if(!appSettings.itForms) appSettings.itForms = [];
    
    appSettings.itForms.push({ 
        id: Date.now(), 
        title: title, 
        url: fileBase64, 
        filename: fileInput.files[0].name 
    });
    await saveSettingsToServer();
    document.getElementById('euFormTitle').value = '';
    if(fileInput) fileInput.value = '';
    renderEUFormList();
    showAppModal("Form saved successfully!");
}

window.renderEUFormList = function() {
    const list = document.getElementById('euFormList');
    if(!list) return;
    list.innerHTML = '';
    if(!appSettings.itForms) return;
    appSettings.itForms.forEach(item => {
        list.innerHTML += `<li>${item.title} <button onclick="deleteEUForm(${item.id})" class="mng-btn del" title="Delete">&#128465;</button></li>`;
    });
}

window.deleteEUForm = async function(id) {
    if(!await showAppConfirm("Delete this form?")) return;
    appSettings.itForms = appSettings.itForms.filter(i => i.id !== id);
    await saveSettingsToServer();
    renderEUFormList();
}

if(document.getElementById('filterStatus')) document.getElementById('filterStatus').value = 'Open';
populateUserFilter();