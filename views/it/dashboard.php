<div id="itMainView-dashboard" class="it-main-view">
    <div class="controls" style="justify-content: space-between;">
        <h2 style="color: var(--primary-color); margin:0;">Analytics Overview</h2>
        <div class="filter-group">
            <label style="margin-right:0.5rem; color:var(--text-color);">Date Range:</label>
            <select id="chartTimeFilter" onchange="renderDashboardCharts()">
                <option value="7" selected>Last 7 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="all">All Time</option>
            </select>
            <button class="btn-outline" onclick="fetchLogsFromDB()">&#8635; Refresh Data</button>
        </div>
    </div>

    <div class="summary-stats">
        <div class="stat-card" style="border-top: 3px solid var(--info-color);">
            <h3>Total Tickets</h3>
            <div class="number" id="dashTotal" style="color: var(--info-color);">0</div>
        </div>
        <div class="stat-card" style="border-top: 3px solid var(--primary-color);">
            <h3>New / Open</h3>
            <div class="number" id="dashOpen" style="color: var(--primary-color);">0</div>
        </div>
        <div class="stat-card" style="border-top: 3px solid var(--warning-color);">
            <h3>Pending / Hold</h3>
            <div class="number" id="dashPending" style="color: var(--warning-color);">0</div>
        </div>
        <div class="stat-card" style="border-top: 3px solid var(--success-color);">
            <h3>Resolved / Closed</h3>
            <div class="number" id="dashResolved" style="color: var(--success-color);">0</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
        
        <div class="log-form-card" style="margin-bottom:0;">
            <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
                <h4 style="color: var(--text-color); margin:0;">Ticket Status</h4>
                <span style="font-size:0.8rem; color:var(--muted-color);">Filter Applied</span>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-around; gap: 1.5rem; flex-wrap: wrap;">
                <div style="width: 250px; height: 250px; position: relative;">
                    <canvas id="chartStatus"></canvas>
                    <div id="chartStatusCenter" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; line-height: 1.2; color: var(--text-color);"></div>
                </div>
                <div id="chartStatusLegend" style="flex: 1; min-width: 120px; display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.95rem; padding-left: 1rem;"></div>
            </div>
        </div>

        <div class="log-form-card" style="margin-bottom:0;">
            <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
                <h4 style="color: var(--text-color); margin:0;">Ticket By Inbox</h4>
                <span style="font-size:0.8rem; color:var(--muted-color);">Filter Applied</span>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-around; gap: 1.5rem; flex-wrap: wrap;">
                <div style="width: 250px; height: 250px; position: relative;">
                    <canvas id="chartUsers"></canvas>
                </div>
                <div id="chartUsersLegend" style="flex: 1; min-width: 120px; display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.95rem; padding-left: 1rem;"></div>
            </div>
        </div>

        <div class="log-form-card" style="margin-bottom:0;">
            <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
                <h4 style="color: var(--text-color); margin:0;">Top Categories</h4>
                <span style="font-size:0.8rem; color:var(--muted-color);">Filter Applied</span>
            </div>
            <div style="width: 100%; height: 250px; position: relative;">
                <canvas id="chartCategories"></canvas>
            </div>
        </div>
    </div>

    <div class="log-form-card" style="margin-bottom: 1.5rem;">
        <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
            <h4 style="color: var(--text-color); margin:0;">Daily Ticket Creation Trend</h4>
            <span style="font-size:0.8rem; color:var(--muted-color);">Filter Applied</span>
        </div>
        <div style="width: 100%; height: 250px; position: relative;">
            <canvas id="chartTrend"></canvas>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="log-form-card" style="margin-bottom:0;">
            <h4 style="color: var(--danger-color); margin-bottom:1rem; text-transform:uppercase; font-size:0.9rem; letter-spacing:1px;">&#9888; Overdue Tickets (Open > 3 Days)</h4>
            <ul class="mng-list" id="overdueTicketsList"></ul>
        </div>

        <div class="log-form-card" style="margin-bottom:0;">
            <h4 style="color: var(--warning-color); margin-bottom:1rem; text-transform:uppercase; font-size:0.9rem; letter-spacing:1px;">&#127942; IT Top Performers (Tickets Handled)</h4>
            <ul class="mng-list" id="leaderboardList"></ul>
        </div>
    </div>
</div>