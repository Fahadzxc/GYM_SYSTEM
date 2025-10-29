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
            <h2>Add new user</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addUserForm">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="id">ID *</label>
                        <input type="text" id="id" name="id" placeholder="Enter ID" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter Address">
                    </div>
                    <div class="form-group">
                        <label for="phone_no">Phone No.</label>
                        <input type="text" id="phone_no" name="phone_no" placeholder="Enter Phone Number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                        <label for="user_type">User Type *</label>
                        <select id="user_type" name="user_type" required>
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
            <button class="add-user-submit-btn">Add User</button>
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
    const addUserSubmitBtn = document.querySelector('.add-user-submit-btn');
    const addUserForm = document.getElementById('addUserForm');

    // Edit modal elements
    const editModal = document.getElementById('editUserModal');
    const closeEditBtn = document.querySelector('.close-edit');
    const saveUserBtn = document.querySelector('.save-user-btn');
    const editUserForm = document.getElementById('editUserForm');

    // Open modal
    addUserBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        addUserForm.reset(); // Clear form when opening
    });

    // Close modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        addUserForm.reset(); // Clear form when closing
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            addUserForm.reset(); // Clear form when closing
        }
        if (event.target === editModal) {
            editModal.style.display = 'none';
            editUserForm.reset(); // Clear form when closing
        }
    });

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

    // Handle form submission
    addUserSubmitBtn.addEventListener('click', function() {
        submitForm();
    });

    // Handle form submission on Enter key
    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm();
    });

    function submitForm() {
        // Get form data
        const formData = new FormData(addUserForm);
        
        // Debug: Log form data
        console.log('Form data:', Object.fromEntries(formData));
        
        // Show loading state
        addUserSubmitBtn.disabled = true;
        addUserSubmitBtn.textContent = 'Adding...';

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
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Show success message
                showMessage(data.message, 'success');
                
                // Close modal
                modal.style.display = 'none';
                addUserForm.reset();
                
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
            showMessage('An error occurred while adding the member. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            addUserSubmitBtn.disabled = false;
            addUserSubmitBtn.textContent = 'Add User';
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
