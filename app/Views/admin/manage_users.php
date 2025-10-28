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

<script>
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addUserModal');
    const addUserBtn = document.querySelector('.add-user-btn');
    const closeBtn = document.querySelector('.close');
    const addUserSubmitBtn = document.querySelector('.add-user-submit-btn');
    const addUserForm = document.getElementById('addUserForm');

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

    // Function to edit member (placeholder)
    window.editMember = function(memberId) {
        alert('Edit functionality will be implemented soon! Member ID: ' + memberId);
    };

    // Function to delete member (placeholder)
    window.deleteMember = function(memberId) {
        if (confirm('Are you sure you want to delete this member?')) {
            alert('Delete functionality will be implemented soon! Member ID: ' + memberId);
        }
    };

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
<?= $this->endSection() ?>
