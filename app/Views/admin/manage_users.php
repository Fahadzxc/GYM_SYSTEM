<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Manage Users
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-container">
    <?= $this->include('sidebar') ?>
    
    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">Manage Users</h1>
            <button class="add-user-btn">Add User</button>
        </div>
        
        <!-- Success/Error Message Area -->
        <div id="messageArea" style="display: none; margin: 10px 0; padding: 10px; border-radius: 4px;"></div>
        
        <div class="content-panel">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone No.</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="membersTableBody">
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?= esc($member['id']) ?></td>
                              <td><?= esc($member['first_name'] . ' ' . $member['last_name']) ?></td>
                                <td><?= esc($member['address']) ?></td>
                                <td><?= esc($member['phone_no']) ?></td>
                                <td><?= esc($member['email']) ?></td>
                                <td><?= ucfirst(esc($member['user_type'])) ?></td>
                                <td>
                                    <button class="action-btn edit-btn" onclick="editMember(<?= $member['id'] ?>)">Edit</button>
                                    <button class="action-btn delete-btn" onclick="deleteMember(<?= $member['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">No members found. Add your first member!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New User</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addUserForm">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="id">School ID * <span style="font-size: 12px; color: #666;">(Scan or enter)</span></label>
                        <input type="text" id="id" name="id" required 
                               placeholder="Scan ID card or enter manually" 
                               style="font-size: 18px; letter-spacing: 1px; text-align: center;"
                               autofocus>
                        <div id="scannerStatus" style="display:none; margin-top:5px; padding:5px; border-radius:4px; font-size:12px;"></div>
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
                        <label for="user_type">User Type *</label>
                        <select id="user_type" name="user_type" required>
                            <option value="">Select User Type</option>
                            <option value="staff">Staff/Admin</option>
                            <option value="athlete">Athlete</option>
                            <option value="faculty">Faculty</option>
                            <option value="student">Student</option>
                        </select>
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

                <!-- Payment fields for faculty and student -->
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
            <button class="add-user-submit-btn" id="registerUserBtn">Create User</button>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit User</h2>
            <span class="close-edit">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editUserForm">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_user_id" name="edit_user_id">
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_id">ID *</label>
                        <input type="text" id="edit_id" name="edit_id" placeholder="Enter ID" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_first_name">First Name *</label>
                        <input type="text" id="edit_first_name" name="edit_first_name" placeholder="Enter First Name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_middle_name">Middle Name</label>
                        <input type="text" id="edit_middle_name" name="edit_middle_name" placeholder="Enter Middle Name">
                    </div>
                    <div class="form-group">
                        <label for="edit_last_name">Last Name *</label>
                        <input type="text" id="edit_last_name" name="edit_last_name" placeholder="Enter Last Name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_address">Address</label>
                        <input type="text" id="edit_address" name="edit_address" placeholder="Enter Address">
                    </div>
                    <div class="form-group">
                        <label for="edit_phone_no">Phone No.</label>
                        <input type="text" id="edit_phone_no" name="edit_phone_no" placeholder="Enter Phone Number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" id="edit_email" name="edit_email" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                        <label for="edit_user_type">User Type *</label>
                        <select id="edit_user_type" name="edit_user_type" required>
                            <option value="">Select User Type</option>
                            <option value="staff">Staff</option>
                            <option value="athlete">Athlete</option>
                            <option value="faculty">Faculty</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="save-user-btn">SAVE</button>
        </div>
    </div>
</div>

<script>
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addUserModal');
    const addUserBtn = document.querySelector('.add-user-btn');
    const closeBtn = document.querySelector('.close');
    const addUserForm = document.getElementById('addUserForm');
    const registerUserBtn = document.getElementById('registerUserBtn');
    const userTypeEl = document.getElementById('user_type');
    const paymentSection = document.getElementById('memberPaymentFields');

    // Edit modal elements
    const editModal = document.getElementById('editUserModal');
    const closeEditBtn = document.querySelector('.close-edit');
    const saveUserBtn = document.querySelector('.save-user-btn');
    const editUserForm = document.getElementById('editUserForm');

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

    if (addUserBtn) {
        addUserBtn.addEventListener('click', function(){ 
            try { 
                openModal(); 
                // Focus on School ID field when modal opens for RFID scanning
                setTimeout(function() {
                    var schoolIdInput = document.getElementById('id');
                    if (schoolIdInput) {
                        schoolIdInput.focus();
                    }
                }, 100);
            } catch(e){ 
                console.error('openModal error', e, {modal, addUserBtn}); 
            }
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', function(){ try { closeModal(); } catch(e){ console.error('closeModal error', e, {modal, closeBtn}); }});
    }
    window.addEventListener('click', function(e){ try { if (modal && e.target === modal) closeModal(); } catch(err){ console.error('window click handler error', err, {modal, e}); } });

    // RFID Scanner functionality for School ID field
    var schoolIdInput = document.getElementById('id');
    var scannerStatus = document.getElementById('scannerStatus');
    var scanTimeout = null;
    var isProcessingScan = false;

    if (schoolIdInput) {
        // Auto-detect RFID scan (RFID readers typically send data quickly)
        schoolIdInput.addEventListener('input', function(e) {
            // Clear any existing timeout
            if (scanTimeout) {
                clearTimeout(scanTimeout);
            }

            // Wait for user to finish typing/scanning (RFID readers send data quickly)
            scanTimeout = setTimeout(function() {
                var scannedId = schoolIdInput.value.trim();
                
                if (scannedId.length > 0 && !isProcessingScan) {
                    // Show success status
                    showScannerStatus('✓ ID scanned successfully: ' + scannedId, 'success');
                    
                    // Auto-focus next field after scan
                    setTimeout(function() {
                        var firstNameInput = document.getElementById('first_name');
                        if (firstNameInput) {
                            firstNameInput.focus();
                        }
                    }, 500);
                }
            }, 300); // 300ms delay - adjust if needed for your RFID reader
        });

        // Handle Enter key (if user manually types and presses Enter)
        schoolIdInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var scannedId = schoolIdInput.value.trim();
                
                if (scannedId.length > 0) {
                    showScannerStatus('✓ ID entered: ' + scannedId, 'success');
                    // Move to next field
                    setTimeout(function() {
                        var firstNameInput = document.getElementById('first_name');
                        if (firstNameInput) {
                            firstNameInput.focus();
                        }
                    }, 100);
                }
            }
        });

        // Focus on School ID field when modal is visible
        var observer = new MutationObserver(function(mutations) {
            if (modal && modal.style.display === 'flex') {
                setTimeout(function() {
                    schoolIdInput.focus();
                }, 100);
            }
        });

        if (modal) {
            observer.observe(modal, { attributes: true, attributeFilter: ['style'] });
        }
    }

    // Function to show scanner status
    function showScannerStatus(message, type) {
        if (!scannerStatus) return;
        
        scannerStatus.textContent = message;
        scannerStatus.style.display = 'block';
        
        if (type === 'success') {
            scannerStatus.style.backgroundColor = '#d4edda';
            scannerStatus.style.color = '#155724';
            scannerStatus.style.border = '1px solid #c3e6cb';
        } else if (type === 'error') {
            scannerStatus.style.backgroundColor = '#f8d7da';
            scannerStatus.style.color = '#721c24';
            scannerStatus.style.border = '1px solid #f5c6cb';
        } else {
            scannerStatus.style.backgroundColor = '#d1ecf1';
            scannerStatus.style.color = '#0c5460';
            scannerStatus.style.border = '1px solid #bee5eb';
        }

        // Auto-hide after 3 seconds
        setTimeout(function() {
            scannerStatus.style.display = 'none';
        }, 3000);
    }

    // Show/hide payment fields based on user type (for faculty and student)
    function togglePaymentFields() {
        if (userTypeEl && paymentSection) {
            var selectedType = userTypeEl.value;
            if (selectedType === 'faculty' || selectedType === 'student') {
                paymentSection.style.display = 'block';
            } else {
                paymentSection.style.display = 'none';
                // Clear payment fields when no user type is selected
                var packageEl = document.getElementById('package_name');
                var amountEl = document.getElementById('amount_paid');
                var startDateEl = document.getElementById('start_date');
                var endDateEl = document.getElementById('end_date');
                if (packageEl) packageEl.value = '';
                if (amountEl) amountEl.value = '';
                if (startDateEl) startDateEl.value = '';
                if (endDateEl) endDateEl.value = '';
            }
        }
    }
    
    // Initial state - hide payment fields until user type is selected
    if (paymentSection) paymentSection.style.display = 'none';
    
    // Listen for user type changes
    if (userTypeEl) {
        userTypeEl.addEventListener('change', togglePaymentFields);
    }

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

    // Edit modal event listeners
    closeEditBtn.addEventListener('click', function() {
        editModal.style.display = 'none';
        editUserForm.reset();
    });

    // Handle edit form submission
    saveUserBtn.addEventListener('click', function() {
        submitEditForm();
    });

    editUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitEditForm();
    });

    // Handle registration form submission
    if (registerUserBtn) {
        registerUserBtn.addEventListener('click', function(e){
            e.preventDefault();
            try {
                var form = document.getElementById('addUserForm');
                if (!form) throw new Error('Form not found');
                var fd = new FormData(form);

                registerUserBtn.disabled = true;
                registerUserBtn.textContent = 'Creating...';

                fetch('<?= base_url('/manage-users/add') ?>', {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin'
                }).then(function(response){
                    console.log('manage-users/add response', {status: response.status, url: response.url});
                    return response.text().then(function(text){
                        try { return JSON.parse(text); } catch(e) { return { success:false, message: text || 'Invalid JSON response', raw:text }; }
                    });
                }).then(function(j){
                    console.log('manage-users/add json', j);
                    showMessage(j.message || (j.success ? 'User created' : 'Error'), j.success ? 'success' : 'error');
                    if (j.success) {
                        closeModal();
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                }).catch(function(err){
                    showMessage('Request failed — see console', 'error');
                    console.error('fetch manage-users/add error', err);
                }).finally(function() {
                    registerUserBtn.disabled = false;
                    registerUserBtn.textContent = 'Create User';
                });
            } catch (err) {
                console.error('submit handler error', err, {registerUserBtn});
                showMessage('Cannot submit form. See console for details.', 'error');
                registerUserBtn.disabled = false;
                registerUserBtn.textContent = 'Create User';
            }
        });
    }

    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (registerUserBtn) registerUserBtn.click();
        });
    }

    function submitEditForm() {
        // Get form data
        const formData = new FormData(editUserForm);
        
        // Debug: Log form data
        console.log('Edit form data:', Object.fromEntries(formData));
        
        // Show loading state
        saveUserBtn.disabled = true;
        saveUserBtn.textContent = 'Saving...';

        // Submit form data
        fetch('<?= base_url('manage-users/edit') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Show success message
                showMessage(data.message, 'success');
                
                // Close modal
                editModal.style.display = 'none';
                editUserForm.reset();
                
                // Reload page to show updated member
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                // Show error message
                let errorMessage = data.message;
                if (data.errors) {
                    errorMessage += '<br><br><strong>Errors:</strong><ul>';
                    for (const field in data.errors) {
                        errorMessage += `<li>${data.errors[field]}</li>`;
                    }
                    errorMessage += '</ul>';
                }
                showMessage(errorMessage, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred while updating the member. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            saveUserBtn.disabled = false;
            saveUserBtn.textContent = 'SAVE';
        });
    }

    // Function to edit member
    window.editMember = function(memberId) {
        // Find the member data
        const members = <?= json_encode($members) ?>;
        const member = members.find(m => m.id == memberId);
        
        if (member) {
            // Populate the edit form
            document.getElementById('edit_user_id').value = member.id;
            document.getElementById('edit_id').value = member.id;
            document.getElementById('edit_first_name').value = member.first_name;
            document.getElementById('edit_middle_name').value = member.middle_name || '';
            document.getElementById('edit_last_name').value = member.last_name;
            document.getElementById('edit_address').value = member.address || '';
            document.getElementById('edit_phone_no').value = member.phone_no || '';
            document.getElementById('edit_email').value = member.email || '';
            document.getElementById('edit_user_type').value = member.user_type;
            
            // Show the edit modal
            document.getElementById('editUserModal').style.display = 'block';
        }
    };

    // Delete functionality
    let deleteUserId = null;
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

    function deleteMember(userId) {
        deleteUserId = userId;
        deleteModal.style.display = 'block';
    }

    // Make deleteMember globally accessible
    window.deleteMember = deleteMember;

    // Close delete modal when clicking outside
    window.onclick = function(event) {
        if (event.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    }

    // Cancel delete
    cancelDeleteBtn.addEventListener('click', function() {
        deleteModal.style.display = 'none';
        deleteUserId = null;
    });

    // Confirm delete
    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteUserId) {
            fetch('<?= base_url('manage-users/delete') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    user_id: deleteUserId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    deleteModal.style.display = 'none';
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred while deleting the member. Please try again.', 'error');
            });
        }
    });

    // Function to show messages
    function showMessage(message, type) {
        const messageArea = document.getElementById('messageArea');
        messageArea.innerHTML = message;
        messageArea.style.display = 'block';
        
        if (type === 'success') {
            messageArea.style.backgroundColor = '#d4edda';
            messageArea.style.color = '#155724';
            messageArea.style.border = '1px solid #c3e6cb';
        } else if (type === 'error') {
            messageArea.style.backgroundColor = '#f8d7da';
            messageArea.style.color = '#721c24';
            messageArea.style.border = '1px solid #f5c6cb';
        }
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageArea.style.display = 'none';
        }, 5000);
    }
});
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal" style="padding-top: 0px;">
    <div class="modal-content" style="max-width: 400px; text-align: center; border-radius: 8px; background-color: white; border: 1px solid #0A2E73; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
        <div class="modal-body" style="padding: 30px 25px 20px 25px;">
            <p style="font-size: 16px; color: #333; margin: 0; font-weight: 500;">Do you want to delete it?</p>
        </div>
        <div class="modal-footer" style="text-align: center; padding: 0 25px 25px 25px;">
            <button id="cancelDeleteBtn" class="cancel-delete-btn" style="margin-right: 15px;">Cancel</button>
            <button id="confirmDeleteBtn" class="confirm-delete-btn">Ok</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
