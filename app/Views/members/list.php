<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Members
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-container">
    <?= $this->include('sidebar') ?>

    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">Members</h1>
            <button class="add-user-btn" id="openAddMemberBtn" style="background:#28a745;color:#fff">Add Member</button>
        </div>

        <div class="content-panel">
            <p class="muted">List of active members</p>

            <?php if (empty($members)): ?>
                <div style="padding:20px;text-align:center">No members found.</div>
            <?php else: ?>
                <table class="data-table" style="font-size:0.9em;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>User Type</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Package</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $m): ?>
                            <tr>
                                <td><?= esc($m['id']) ?></td>
                                <td><?= esc($m['first_name'].' '.$m['last_name']) ?></td>
                                <td><?= esc($m['user_type']) ?></td>
                                <td><?= esc($m['department'] ?? '—') ?></td>
                                <td><?= esc($m['email'] ?? '—') ?></td>
                                <td><?= esc($m['phone_no'] ?? '—') ?></td>
                                <td><?= esc($m['package_name'] ?? '—') ?></td>
                                <td><?= esc($m['start_date'] ? date('m/d/Y', strtotime($m['start_date'])) : '—') ?></td>
                                <td><?= esc($m['end_date'] ? date('m/d/Y', strtotime($m['end_date'])) : '—') ?></td>
                                <td>
                                    <button class="edit-btn edit-member-btn" 
                                            data-id="<?= esc($m['id']) ?>"
                                            data-first-name="<?= esc($m['first_name']) ?>"
                                            data-middle-name="<?= esc($m['middle_name'] ?? '') ?>"
                                            data-last-name="<?= esc($m['last_name']) ?>"
                                            data-address="<?= esc($m['address'] ?? '') ?>"
                                            data-phone="<?= esc($m['phone_no'] ?? '') ?>"
                                            data-email="<?= esc($m['email'] ?? '') ?>"
                                            data-department="<?= esc($m['department'] ?? '') ?>"
                                            data-package="<?= esc($m['package_name'] ?? '') ?>"
                                            data-start-date="<?= esc($m['start_date'] ?? '') ?>"
                                            data-end-date="<?= esc($m['end_date'] ?? '') ?>"
                                            style="background:#0056b3;color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">Edit</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Add Member Modal -->
<style>
/* Center the modal using flexbox and make it responsive */
#addMemberModal.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.35);
    align-items: center;
    justify-content: center;
}
#addMemberModal .modal-content {
    background: #fff8f0;
    border-radius: 8px;
    max-width: 760px;
    width: 92%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.25);
    padding: 18px;
}
#addMemberModal .modal-header h2 { margin: 0 0 8px 0; }
#addMemberModal .close { cursor: pointer; }
</style>

<div id="addMemberModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Member</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addMemberForm">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="id">School ID *</label>
                        <input type="text" id="id" name="id" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address">
                    </div>
                    <div class="form-group">
                        <label for="phone_no">Phone No.</label>
                        <input type="text" id="phone_no" name="phone_no">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <!-- Add Member flow is faculty-only -->
                        <input type="hidden" id="user_type_member" name="user_type" value="faculty">
                        <label>User Type</label>
                        <div style="padding:8px 10px;border:1px solid #e5e5e5;border-radius:4px;background:#fff8f0;">Faculty</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Teacher Education">Teacher Education</option>
                            <option value="Business">Business</option>
                            <option value="IT">IT</option>
                            <option value="Allied Health Sciences">Allied Health Sciences</option>
                        </select>
                    </div>
                </div>

                <!-- Payment fields for faculty only -->
                <div id="memberPaymentFields" style="display:none;margin-top:12px;border-top:1px solid #eee;padding-top:12px;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="package_name">Package</label>
                            <select id="package_name" name="package_name">
                                <option value="">Select package</option>
                                <option value="Monthly" data-amount="800">Monthly</option>
                                <option value="Semester" data-amount="3000">Semester</option>
                                <option value="Annual" data-amount="6000">Annual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount_paid">Amount Paid</label>
                            <input type="number" step="0.01" id="amount_paid" name="amount_paid" placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="payment_status">Payment Status</label>
                            <!-- Payment status is forced to paid for Add Member flow -->
                            <input type="hidden" id="payment_status" name="payment_status" value="paid">
                            <div style="padding:8px 10px;border:1px solid #e5e5e5;border-radius:4px;background:#fff8f0;">Paid</div>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="add-user-submit-btn" id="submitAddMember">Create Member</button>
        </div>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="editMemberModal" class="modal" style="display:none;position:fixed;z-index:1000;left:0;top:0;right:0;bottom:0;background:rgba(0,0,0,0.35);align-items:center;justify-content:center;">
    <div class="modal-content" style="background:#fff8f0;border-radius:8px;max-width:760px;width:92%;max-height:90vh;overflow-y:auto;box-shadow:0 10px 40px rgba(0,0,0,0.25);padding:18px;">
        <div class="modal-header">
            <h2>Update Member</h2>
            <span class="close" style="cursor:pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editMemberForm">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label>School ID (Readonly)</label>
                        <input type="text" id="editId" readonly style="background:#f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label for="editFirstName">First Name *</label>
                        <input type="text" id="editFirstName" name="first_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editMiddleName">Middle Name</label>
                        <input type="text" id="editMiddleName" name="middle_name">
                    </div>
                    <div class="form-group">
                        <label for="editLastName">Last Name *</label>
                        <input type="text" id="editLastName" name="last_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editAddress">Address</label>
                        <input type="text" id="editAddress" name="address">
                    </div>
                    <div class="form-group">
                        <label for="editPhoneNo">Phone No.</label>
                        <input type="text" id="editPhoneNo" name="phone_no">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" id="editEmail" name="email">
                    </div>
                    <div class="form-group">
                        <label for="editDepartment">Department</label>
                        <select id="editDepartment" name="department">
                            <option value="">Select Department</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Teacher Education">Teacher Education</option>
                            <option value="Business">Business</option>
                            <option value="IT">IT</option>
                            <option value="Allied Health Sciences">Allied Health Sciences</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPackageName">Package</label>
                        <select id="editPackageName" name="package_name">
                            <option value="">Select package</option>
                            <option value="Monthly" data-amount="800">Monthly</option>
                            <option value="Semester" data-amount="3000">Semester</option>
                            <option value="Annual" data-amount="6000">Annual</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Start Date (Readonly)</label>
                        <input type="date" id="editStartDate" readonly style="background:#f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label>End Date (Readonly)</label>
                        <input type="date" id="editEndDate" readonly style="background:#f0f0f0;">
                    </div>
                </div>
                <input type="hidden" id="editIdField" name="id">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="add-user-submit-btn" id="submitEditMember">Update Member</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var openBtn = document.getElementById('openAddMemberBtn');
    var modal = document.getElementById('addMemberModal');
    var closeBtn = modal ? modal.querySelector('.close') : null;
    var submitBtn = document.getElementById('submitAddMember');
    var userTypeEl = document.getElementById('user_type_member');
    var paymentSection = document.getElementById('memberPaymentFields');

    function setModalVisible(visible){
        if (!modal) return;
        try{
            if (modal.style) {
                modal.style.display = visible ? 'flex' : 'none';
            } else if (modal.classList) {
                modal.classList.toggle('visible', !!visible);
            }
        } catch(err){
            console.error('setModalVisible error', err, {modal, visible});
        }
    }
    function openModal(){ setModalVisible(true); }
    function closeModal(){ setModalVisible(false); }

    if (openBtn) {
        openBtn.addEventListener('click', function(){ try { openModal(); } catch(e){ console.error('openModal error', e, {modal, openBtn}); }});
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', function(){ try { closeModal(); } catch(e){ console.error('closeModal error', e, {modal, closeBtn}); }});
    }
    window.addEventListener('click', function(e){ try { if (modal && e.target === modal) closeModal(); } catch(err){ console.error('window click handler error', err, {modal, e}); } });

    // Always show payment fields for Add Member since it's faculty-only
    if (paymentSection) paymentSection.style.display = 'block';

    // Helper to format date to yyyy-mm-dd
    function formatDate(d){
        var y = d.getFullYear();
        var m = (d.getMonth()+1).toString().padStart(2,'0');
        var day = d.getDate().toString().padStart(2,'0');
        return y + '-' + m + '-' + day;
    }

    // Set start_date to today by default
    var today = new Date();
    var startEl = document.getElementById('start_date');
    var endEl = document.getElementById('end_date');
    if (startEl && !startEl.value) startEl.value = formatDate(today);

    // When package changes, set amount, start date, end date and payment status
    var packageEl = document.getElementById('package_name');
    if (packageEl) packageEl.addEventListener('change', function(){
        try {
            var opt = packageEl.options[packageEl.selectedIndex];
            var amt = opt ? opt.getAttribute('data-amount') : null;
            var amountEl = document.getElementById('amount_paid');
            if (amt && amountEl) amountEl.value = parseFloat(amt).toFixed(2);

            // set payment status to paid
            var pstat = document.getElementById('payment_status');
            if (pstat) pstat.value = 'paid';

            // set start date to today if empty
            if (startEl && !startEl.value) startEl.value = formatDate(new Date());

            // compute end date based on package
            if (endEl && startEl) {
                var sd = new Date(startEl.value);
                if (opt && opt.value === 'Monthly') sd.setMonth(sd.getMonth() + 1);
                else if (opt && opt.value === 'Semester') sd.setMonth(sd.getMonth() + 6);
                else if (opt && opt.value === 'Annual') sd.setFullYear(sd.getFullYear() + 1);
                endEl.value = formatDate(sd);
            }
        } catch (e) {
            console.error('package change handler error', e, {packageEl, startEl, endEl, paymentSection});
        }
    });

    // If page loads and package has a preselected value, trigger change to fill values
    if (packageEl && packageEl.value) {
        var ev = new Event('change');
        packageEl.dispatchEvent(ev);
    }

    if (submitBtn) submitBtn.addEventListener('click', function(e){
        e.preventDefault();
        try {
            var form = document.getElementById('addMemberForm');
            if (!form) throw new Error('Form not found');
            var fd = new FormData(form);

            fetch('<?= base_url('/members/add') ?>', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            }).then(function(response){
                console.log('members/add response', {status: response.status, url: response.url});
                return response.text().then(function(text){
                    try { return JSON.parse(text); } catch(e) { return { success:false, message: text || 'Invalid JSON response', raw:text }; }
                });
            }).then(function(j){
                console.log('members/add json', j);
                alert(j.message || (j.success ? 'Member created' : 'Error'));
                if (j.success) location.reload();
            }).catch(function(err){
                alert('Request failed — see console');
                console.error('fetch members/add error', err);
            });
        } catch (err) {
            console.error('submit handler error', err, {submitBtn});
            alert('Cannot submit form. See console for details.');
        }
    });

    // Edit Modal handlers inside DOMContentLoaded
    var editModal = document.getElementById('editMemberModal');
    
    // Event delegation for edit buttons
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('edit-member-btn')) {
            var btn = e.target;
            var id = btn.getAttribute('data-id');
            var firstName = btn.getAttribute('data-first-name') || '';
            var middleName = btn.getAttribute('data-middle-name') || '';
            var lastName = btn.getAttribute('data-last-name') || '';
            var address = btn.getAttribute('data-address') || '';
            var phone = btn.getAttribute('data-phone') || '';
            var email = btn.getAttribute('data-email') || '';
            var department = btn.getAttribute('data-department') || '';
            var packageName = btn.getAttribute('data-package') || '';
            var startDate = btn.getAttribute('data-start-date') || '';
            var endDate = btn.getAttribute('data-end-date') || '';
            
            if (!editModal) {
                console.error('Edit modal not found');
                return;
            }
            
            document.getElementById('editId').value = id;
            document.getElementById('editIdField').value = id;
            document.getElementById('editFirstName').value = firstName;
            document.getElementById('editMiddleName').value = middleName;
            document.getElementById('editLastName').value = lastName;
            document.getElementById('editAddress').value = address;
            document.getElementById('editPhoneNo').value = phone;
            document.getElementById('editEmail').value = email;
            document.getElementById('editDepartment').value = department;
            document.getElementById('editPackageName').value = packageName;
            document.getElementById('editStartDate').value = startDate;
            document.getElementById('editEndDate').value = endDate;
            
            editModal.style.display = 'flex';
        }
    });
    
    if (editModal) {
        var editCloseBtn = editModal.querySelector('.close');
        if (editCloseBtn) {
            editCloseBtn.addEventListener('click', function() { editModal.style.display = 'none'; });
        }
        window.addEventListener('click', function(e) { if (e.target === editModal) editModal.style.display = 'none'; });
        
        var editSubmitBtn = document.getElementById('submitEditMember');
        if (editSubmitBtn) {
            editSubmitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var form = document.getElementById('editMemberForm');
                if (!form) {
                    alert('Form not found');
                    return;
                }
                var fd = new FormData(form);
                fetch('<?= base_url('/members/edit') ?>', {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin'
                }).then(function(r) { 
                    return r.text().then(function(text) {
                        try { 
                            return JSON.parse(text); 
                        } catch(e) { 
                            return { success: false, message: text || 'Invalid JSON response', raw: text }; 
                        }
                    });
                })
                  .then(function(j) { 
                      alert(j.message || (j.success ? 'Member updated' : 'Error')); 
                      if (j.success) location.reload(); 
                  })
                  .catch(function(e) { 
                      alert('Request failed'); 
                      console.error(e); 
                  });
            });
        }
    }
});
</script>
<?= $this->endSection() ?>
