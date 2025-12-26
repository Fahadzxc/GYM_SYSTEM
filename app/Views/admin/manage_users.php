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
            <h2>Add New User - School ID Registration</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <!-- School ID Check Section -->
            <div id="schoolIdCheckSection">
                <div class="form-row" style="margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label for="school_id">School ID * <span style="font-size: 12px; color: #666;">(Scan or enter)</span></label>
                        <input type="text" id="school_id" name="school_id" placeholder="Scan school ID card or enter ID manually" 
                               style="font-size: 18px; letter-spacing: 1px; text-align: center;" autofocus>
                    </div>
                </div>
                <div style="text-align: center; margin-bottom: 15px;">
                    <button type="button" id="checkSchoolIdBtn" class="add-user-submit-btn" style="width: auto; padding: 10px 30px;">
                        Check School ID
                    </button>
                </div>
                <div id="schoolIdStatus" style="display: none; padding: 10px; border-radius: 8px; margin-bottom: 15px;"></div>
            </div>

            <!-- Registration Form (initially hidden) -->
            <form id="addUserForm" style="display: none;">
                <?= csrf_field() ?>
                <input type="hidden" id="id" name="id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name">
                    </div>
                    <div class="form-group">
                        <label for="user_type">User Type *</label>
                        <select id="user_type" name="user_type" required>
                            <option value="">Select User Type</option>
                            <option value="staff">Staff/Admin</option>
                            <option value="athlete">Athlete</option>
                            <option value="faculty">Faculty</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                        <label for="phone_no">Phone No.</label>
                        <input type="text" id="phone_no" name="phone_no" placeholder="Enter Phone Number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter Address">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="add-user-submit-btn" id="registerUserBtn" style="display: none;">Complete Registration</button>
        </div>
    </div>
</div>

<!-- Payment fields moved to separate Add Member flow -->

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
    const schoolIdInput = document.getElementById('school_id');
    const checkSchoolIdBtn = document.getElementById('checkSchoolIdBtn');
    const schoolIdStatus = document.getElementById('schoolIdStatus');
    const schoolIdCheckSection = document.getElementById('schoolIdCheckSection');
    const registerUserBtn = document.getElementById('registerUserBtn');
    let isSchoolIdVerified = false;

    // Edit modal elements
    const editModal = document.getElementById('editUserModal');
    const closeEditBtn = document.querySelector('.close-edit');
    const saveUserBtn = document.querySelector('.save-user-btn');
    const editUserForm = document.getElementById('editUserForm');

    // Open modal - reset everything
    addUserBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        resetAddUserModal();
    });

    // Close modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        resetAddUserModal();
    });

    // Reset add user modal
    function resetAddUserModal() {
        addUserForm.reset();
        schoolIdInput.value = '';
        schoolIdStatus.style.display = 'none';
        schoolIdStatus.innerHTML = '';
        schoolIdCheckSection.style.display = 'block';
        addUserForm.style.display = 'none';
        registerUserBtn.style.display = 'none';
        isSchoolIdVerified = false;
        schoolIdInput.focus();
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            resetAddUserModal();
        }
        if (event.target === editModal) {
            editModal.style.display = 'none';
            editUserForm.reset(); // Clear form when closing
        }
    });

    // School ID input - handle RFID scanning (auto-submit after delay)
    let schoolIdTimeout = null;
    schoolIdInput.addEventListener('input', function() {
        clearTimeout(schoolIdTimeout);
        schoolIdTimeout = setTimeout(function() {
            if (schoolIdInput.value.trim().length > 0) {
                checkSchoolId();
            }
        }, 500); // Wait 500ms after last keystroke
    });

    // Check School ID button
    checkSchoolIdBtn.addEventListener('click', function() {
        checkSchoolId();
    });

    // Enter key on school ID input
    schoolIdInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            checkSchoolId();
        }
    });

    // Check School ID function
    function checkSchoolId() {
        const schoolId = schoolIdInput.value.trim();
        
        if (!schoolId) {
            showSchoolIdStatus('Please enter or scan a school ID.', 'error');
            return;
        }

        checkSchoolIdBtn.disabled = true;
        checkSchoolIdBtn.textContent = 'Checking...';
        schoolIdStatus.style.display = 'none';

        const formData = new FormData();
        formData.append('school_id', schoolId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('manage-users/check-school-id') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            checkSchoolIdBtn.disabled = false;
            checkSchoolIdBtn.textContent = 'Check School ID';

            if (data.success) {
                // School ID is eligible and not registered
                showSchoolIdStatus('✓ School ID verified. Please complete the registration form.', 'success');
                isSchoolIdVerified = true;
                
                // Set the ID field
                document.getElementById('id').value = schoolId;
                
                // Auto-fill form if member info is available
                if (data.member_info) {
                    if (data.member_info.first_name) document.getElementById('first_name').value = data.member_info.first_name;
                    if (data.member_info.middle_name) document.getElementById('middle_name').value = data.member_info.middle_name;
                    if (data.member_info.last_name) document.getElementById('last_name').value = data.member_info.last_name;
                    if (data.member_info.email) document.getElementById('email').value = data.member_info.email;
                    if (data.member_info.phone_no) document.getElementById('phone_no').value = data.member_info.phone_no;
                    if (data.member_info.address) document.getElementById('address').value = data.member_info.address;
                    if (data.suggested_user_type) document.getElementById('user_type').value = data.suggested_user_type;
                }
                
                // Show registration form
                schoolIdCheckSection.style.display = 'none';
                addUserForm.style.display = 'block';
                registerUserBtn.style.display = 'block';
                
                // Focus on first name field
                document.getElementById('first_name').focus();
            } else {
                // Error - show message
                if (data.already_registered) {
                    showSchoolIdStatus('⚠ This ID is already registered. Member: ' + 
                        (data.member_data ? (data.member_data.first_name + ' ' + data.member_data.last_name) : 'N/A'), 
                        'error');
                } else if (!data.eligible) {
                    showSchoolIdStatus('✗ ' + data.message, 'error');
                } else {
                    showSchoolIdStatus('✗ ' + (data.message || 'An error occurred.'), 'error');
                }
                isSchoolIdVerified = false;
            }
        })
        .catch(error => {
            checkSchoolIdBtn.disabled = false;
            checkSchoolIdBtn.textContent = 'Check School ID';
            console.error('Error:', error);
            showSchoolIdStatus('✗ Network error. Please try again.', 'error');
            isSchoolIdVerified = false;
        });
    }

    // Show school ID status message
    function showSchoolIdStatus(message, type) {
        schoolIdStatus.innerHTML = message;
        schoolIdStatus.style.display = 'block';
        
        if (type === 'success') {
            schoolIdStatus.style.backgroundColor = '#d4edda';
            schoolIdStatus.style.color = '#155724';
            schoolIdStatus.style.border = '1px solid #c3e6cb';
        } else if (type === 'error') {
            schoolIdStatus.style.backgroundColor = '#f8d7da';
            schoolIdStatus.style.color = '#721c24';
            schoolIdStatus.style.border = '1px solid #f5c6cb';
        }
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
    registerUserBtn.addEventListener('click', function() {
        submitRegistrationForm();
    });

    // Handle form submission on Enter key
    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitRegistrationForm();
    });

    function submitRegistrationForm() {
        // Verify school ID was checked
        if (!isSchoolIdVerified) {
            showMessage('Please verify the school ID first.', 'error');
            return;
        }

        // Get form data
        const formData = new FormData(addUserForm);
        
        // Debug: Log form data
        console.log('Registration form data:', Object.fromEntries(formData));
        
        // Show loading state
        registerUserBtn.disabled = true;
        registerUserBtn.textContent = 'Registering...';

        // Submit form data
        fetch('<?= base_url('manage-users/add') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Show success message
                showMessage(data.message, 'success');
                
                // Close modal
                modal.style.display = 'none';
                resetAddUserModal();
                
                // Reload page to show new member
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
            showMessage('An error occurred while registering the member. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            registerUserBtn.disabled = false;
            registerUserBtn.textContent = 'Complete Registration';
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
