<div class="modal-overlay" id="respondModal">
    <div class="modal" style="max-width: 600px; border-color: var(--primary-color);">
        <div class="modal-header">
            <h2 id="respondModalTitle">Reply to Thread</h2>
            <button type="button" class="close-btn" onclick="closeRespondModal()">&times;</button>
        </div>
        <form id="respondForm">
            <input type="hidden" id="respondTicketId">
            <div style="margin-bottom: 1.5rem; background: var(--input-bg); padding: 1rem; border-radius: 6px; border: 1px solid var(--border-color);">
                <strong style="color: var(--text-color); display: block; margin-bottom: 0.5rem;" id="respondIssueTitle">Issue Title</strong>
                <p style="color: var(--muted-color); margin: 0; font-size: 0.9rem;" id="respondIssueMessage">Issue description...</p>
            </div>
            
            <?php if (isset($is_it_staff) && $is_it_staff): ?>
            <div class="form-row" id="respondStatusGroup">
                <div class="form-group full">
                    <label>Update Status</label>
                    <select id="respondStatus" required>
                        <option value="New">&#128309; New</option>
                        <option value="Open">&#128993; Open</option>
                        <option value="Pending">&#128308; Pending</option>
                        <option value="Hold">&#9888; Hold</option>
                        <option value="Resolved">&#128994; Resolved</option>
                        <option value="Closed">&#9899; Closed</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group full">
                    <label>Your Reply / Resolution</label>
                    <textarea id="respondMessage" placeholder="Type your response..." required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full">
                    <label>Attach File / Proof (Optional)</label>
                    <input type="file" id="threadAttachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                </div>
            </div>
            <button type="submit" class="btn-submit" style="width: 100%;">Submit Reply</button>
        </form>
    </div>
</div>